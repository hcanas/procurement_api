<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluatePpmp;
use App\Http\Services\PermissionService;
use App\Models\Ppmp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluatedPpmpController extends Controller
{
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
     * @param  EvaluatePpmp $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EvaluatePpmp $request)
    {
        try {
            DB::beginTransaction();
        
            $permission_service = new PermissionService();
            $ppmp = Ppmp::find($request->validated('ppmp_id'));
            $office_id = $ppmp->wfp()->first()->fundSource()->first()->office_id;
        
            if ($ppmp->status === 'for eval:l1') {
                $permission_service->authorize('ppmps:eval_l1', $office_id);
                $ppmp->fill([
                    'status' => $request->validated('evaluation') === 'approved'
                        ? 'for eval:l2'
                        : $request->validated('evaluation'),
                ])->save();
            } elseif ($ppmp->status === 'for eval:l2') {
                $permission_service->authorize('ppmps:eval_l2', $office_id);
                $ppmp->fill(['status' => $request->validated('evaluation')])
                    ->save();
            }
        
            $ppmp->evaluations()->create($request->validated());
            DB::commit();
            return response()->json($ppmp);
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return response()->json('Unable to evaluate PPMP. Please try again later.', 500);
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
