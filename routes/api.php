<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetDataController;
/**
 * TODO: GET API FROM DATABASE
 *
 */
Route::get('/data-check', [GetDataController::class, 'DataCheck']);
Route::get('/search-check', [GetDataController::class, 'SearchCheck']);
Route::get('/data-sec', [GetDataController::class, 'GetSection']);
Route::get('/data-cleaning-all',[GetDataController::class, 'GetCleaningCheckAll']);
Route::get('/filter-date-check',[GetDataController::class, 'FilterDateCheck']);
Route::get('/get-cleanl',[GetDataController::class, 'GetCleanL']);
Route::get('/get-procedure-clean',[GetDataController::class, 'GetProcedureCleanOfDay']);
Route::get('/filter-clean',[GetDataController::class, 'FilterCleanPSC']);
Route::get('/join-data-clean',[GetDataController::class, 'JoinDataCleanUser']);
