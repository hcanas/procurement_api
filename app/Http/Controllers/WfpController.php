<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWfp;
use App\Http\Requests\UpdateWfp;
use App\Http\Services\PermissionService;
use App\Models\Wfp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WfpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $permission_service = new PermissionService();
        $permission_service->authorize('wfps:manage');
        
        $wfps = Wfp::whereHas('fundSource', function ($query) use ($permission_service, $request) {
                $query->whereIn('office_id', $permission_service->offices(true))
                    ->where('year', $request->input('year', date('Y')));
            })
            ->with('fundSource')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($wfps);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateWfp  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateWfp $request)
    {
        try {
            DB::beginTransaction();
            $wfp = Wfp::create($request->validated());
            DB::commit();
            
            return $this->show($wfp->id);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return response()->json('Unable to create WFP. Please try again later.', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // TODO: add ppmp allocation for delete restriction
            $wfp = Wfp::with('fundSource')
                ->with('evaluations')
                ->find($id);
    
            if ($wfp === null) return response()->json('WFP not found.', 404);
    
            (new PermissionService())->authorize('wfps:manage', $wfp->toArray()['fund_source']['office_id']);
    
            return response()->json($wfp);
        } catch (\Exception $e) {
            logger($e);
            return response()->json('Unable to retrieve WFP, please try again later.', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateWfp $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateWfp $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $wfp = Wfp::find($id);
            
            $wfp->fill($request->validated());
            
            if ($wfp->isDirty()) {
                if (in_array($wfp->getOriginal()['status'], ['approved', 'rejected'])) {
                    $wfp->evaluations()->delete();
                    $wfp->status = 'for eval:l1';
                }
                
                $wfp->save();
                DB::commit();
            }
            
            return $this->show($id);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return response()->json('Unable to update WFP. Please try again later.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $wfp = Wfp::find($id);
            
            (new PermissionService())->authorize('wfps:manage', $wfp->fundSource()->first()->office_id);
            
            if ($wfp === null) return response()->json('WFP not found.', 404);
            // TODO: Prevent deletion if has existing PPMPs
            
            $wfp->delete();
            DB::commit();
            return response()->json('', 204);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return response()->json('Unable to delete WFP. Please try again later.', 500);
        }
    }
}
