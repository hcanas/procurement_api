<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CustomAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('Authorization')) {
            throw new HttpResponseException(response()->json('Unauthorized.', 401));
        }
        
        $permissions = ['fund_sources,wfps,ppmps,apps,procurements'];
        
        $response = Http::withHeaders([
            'Authorization' => $request->header('Authorization'),
        ])->post(env('PORTAL_API_URL').'/auth?permissions='.implode($permissions));
        
        if ($response->status() !== 200) {
            throw new HttpResponseException(response()->json('Unauthorized.', 401));
        }
        
        $user = (new User())->fill([
            'id' => $response->json('id'),
            'permissions' => $response->json('permissions'),
        ]);
        
        Auth::login($user);
        
        return $next($request);
    }
}
