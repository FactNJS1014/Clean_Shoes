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
