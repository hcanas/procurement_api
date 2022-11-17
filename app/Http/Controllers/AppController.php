<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\PermissionService;
use App\Models\App;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $permission_service = new PermissionService();
        $permission_service->authorize('apps:manage');
        
        $ppmps = App::whereHas('ppmp.wfp.fundSource', function ($query) use ($permission_service, $request) {
                $query->whereIn('office_id', $permission_service->offices(true))
                    ->where('year', $request->input('year', date('Y')));
            })
            ->with('ppmp.wfp.fundSource')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($ppmps);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
