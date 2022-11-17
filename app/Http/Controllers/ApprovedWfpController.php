<?php

namespace App\Http\Controllers;

use App\Models\Ppmp;
use App\Models\Wfp;
use Illuminate\Http\Request;

class ApprovedWfpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    
    /**
     * Display a listing of the resource with approved status.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $wfps = Wfp::addSelect(['allocated' => Ppmp::query()
                ->whereColumn('wfps.id', 'ppmps.wfp_id')
                ->selectRaw('SUM(abc)')
            ])
            ->whereHas('fundSource', function ($query) use ($request) {
                $query->where('year', $request->input('year', date('Y')));
                
                if ($request->input('office_id')) {
                    $query->where('office_id', $request->input('office_id'));
                }
            })
            ->where('status', 'approved')
            ->with('fundSource')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($wfps);
    }
}
