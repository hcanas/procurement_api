<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePpmp;
use App\Http\Requests\UpdatePpmp;
use App\Http\Services\PermissionService;
use App\Models\Ppmp;
use App\Models\Wfp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PpmpController extends Controller
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
        $permission_service->authorize('ppmps:manage');
        
        $ppmps = Ppmp::whereHas('wfp.fundSource', function ($query) use ($permission_service, $request) {
                $query->whereIn('office_id', $permission_service->offices(true))
                    ->where('year', $request->input('year', date('Y')));
            })
            ->with(['wfp' => function ($query) {
                $query->addSelect(['allocated' => Ppmp::query()
                        ->whereColumn('ppmps.wfp_id', 'wfps.id')
                        ->selectRaw('SUM(quantity * abc)')
                    ])
                    ->with('fundSource');
            }])
            ->with('item.category')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($ppmps);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatePpmp $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreatePpmp $request)
    {
        try {
            DB::beginTransaction();
            
            $ppmp = Ppmp::create($request->validated());
            
            DB::commit();
            return $this->show($ppmp->id);
        } catch (\Exception $e) {
            logger($e);
            DB::rollBack();
            return response()->json('Unable to create ppmp. Please try again later.', 500);
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
        $ppmp = Ppmp::with(['wfp' => function ($query) {
                $query->addSelect(['allocated' => Ppmp::query()
                    ->whereColumn('ppmps.wfp_id', 'wfps.id')
                    ->selectRaw('SUM(quantity * abc)')
                ])
                    ->with('fundSource');
            }])
            ->with('item.category')
            ->find($id);
        
        if ($ppmp === null) return response()->json('PPMP not found.', 404);
        
        (new PermissionService())->authorize('ppmps:manage', $ppmp->toArray()['wfp']['fund_source']['office_id']);
        
        return response()->json($ppmp);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePpmp $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePpmp $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $ppmp = Ppmp::find($id);
            $ppmp->fill($request->validated());
            
            if ($ppmp->isDirty()) {
                if (in_array($ppmp->getOriginal()['status'], ['approved', 'rejected'])) {
                    $ppmp->evaluations()->delete();
                    $ppmp->status = 'for eval:l1';
                }
                
                $ppmp->save();
                DB::commit();
            }
            
            return $this->show($ppmp->id);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return response()->json('Unable to update PPMP. Please try again later.', 500);
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
            $ppmp = Ppmp::find($id);
        
            (new PermissionService())->authorize('ppmps:manage', $ppmp->wfp()
                ->first()
                ->fundSource()
                ->first()
                ->office_id
            );
        
            if ($ppmp === null) return response()->json('PPMP not found.', 404);
            // TODO: Prevent deletion if has existing PPMPs
        
            $ppmp->delete();
            DB::commit();
            return response()->json('', 204);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return response()->json('Unable to delete PPMP. Please try again later.', 500);
        }
    }
}
