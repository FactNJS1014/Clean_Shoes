<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetDataController;
/**
 * TODO: GET API FROM DATABASE
 *
 */
Route::get('/data-check', [GetDataController::class, 'DataCheck']);
