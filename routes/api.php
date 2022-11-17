<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('fund_sources', \App\Http\Controllers\FundSourceController::class);

Route::apiResource('wfps', \App\Http\Controllers\WfpController::class);

Route::apiResource('evaluated_wfps', \App\Http\Controllers\EvaluatedWfpController::class);

Route::get('approved_wfps', \App\Http\Controllers\ApprovedWfpController::class);

Route::apiResource('ppmps', \App\Http\Controllers\PpmpController::class);

Route::apiResource('evaluated_ppmps', \App\Http\Controllers\EvaluatedPpmpController::class);

Route::apiResource('apps', \App\Http\Controllers\AppController::class);

Route::get('items', \App\Http\Controllers\ItemController::class);
