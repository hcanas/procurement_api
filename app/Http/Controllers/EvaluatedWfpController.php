<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluateWfp;
use App\Http\Services\PermissionService;
use App\Models\Wfp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluatedWfpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EvaluateWfp $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EvaluateWfp $request)
    {
        try {
            DB::beginTransaction();
            
            $permission_service = new PermissionService();
            $wfp = Wfp::find($request->validated('wfp_id'));
            $office_id = $wfp->fundSource()->first()->office_id;
            
            if ($wfp->status === 'for eval:l1') {
                $permission_service->authorize('wfps:eval_l1', $office_id);
                $wfp->fill([
                    'status' => $request->validated('evaluation') === 'approved'
                        ? 'for eval:l2'
                        : $request->validated('evaluation'),
                ])->save();
            } elseif ($wfp->status === 'for eval:l2') {
                $permission_service->authorize('wfps:eval_l2', $office_id);
                $wfp->fill(['status' => $request->validated('evaluation')])
                    ->save();
            }
            
            $wfp->evaluations()->create($request->validated());
            DB::commit();
            return response()->json($wfp);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return response()->json('Unable to evaluate WFP. Please try again later.', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
