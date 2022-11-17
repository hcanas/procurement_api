<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFundSource;
use App\Http\Requests\UpdateFundSource;
use App\Http\Services\PermissionService;
use App\Models\FundSource;
use App\Models\Wfp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FundSourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $permission_service = new PermissionService();
        $permission_service->authorize('fund_sources:manage');
        
        $fund_sources = FundSource::addSelect(['allocated' => Wfp::query()
                ->whereColumn('wfps.fund_source_id', 'fund_sources.id')
                ->whereIn('status', ['for eval:l1', 'for eval:l2', 'approved'])
                ->selectRaw('SUM(cost)')
            ])
            ->where('year', request()->input('year', date('Y')));
        
        if (request()->input('office_id')) {
            $fund_sources->where('office_id', request()->input('office_id'));
        } else {
            $fund_sources->whereIn('office_id', $permission_service->offices(true));
        }
        
        return response()->json($fund_sources->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateFundSource $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateFundSource $request)
    {
        try {
            DB::beginTransaction();
            $res = FundSource::create($request->validated());
            DB::commit();
            
            return $this->show($res->id);
        } catch (\Exception $e) {
            logger($e);
            DB::rollBack();
            return response()->json('Unable to create fund source. Please try again later.', 500);
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
        $fund_source = FundSource::addSelect(['allocated' => Wfp::query()
                ->whereColumn('wfps.fund_source_id', 'fund_sources.id')
                ->selectRaw('SUM(cost)')
            ])
            ->find($id);
        
        (new PermissionService())->authorize('fund_sources:manage', $fund_source->office_id);
        
        return response()->json($fund_source);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateFundSource $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateFundSource $request, $id)
    {
        try {
            DB::beginTransaction();
            
            FundSource::find($id)
                ->fill($request->validated())
                ->save();
            
            DB::commit();
            
            return $this->show($id);
        } catch (\Exception $e) {
            logger($e);
            DB::rollBack();
            return response()->json('Unable to update fund source. Please try again later.', 500);
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

            // TODO: Check WFP binding
            $fund_source = FundSource::find($id);
            
            if ($fund_source === null) return response()->json('Fund source not found.', 404);
            
            $fund_source->delete();
            DB::commit();
            
            return response()->json('', 204);
        } catch (\Exception $e) {
            logger($e);
            DB::rollBack();
            return response()->json('Unable to delete fund source. Please try again later.', 500);
        }
    }
}
