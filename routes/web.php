<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ESDCheckInsertController;


Route::get('/{any}', function () {
    return view('app'); // or whatever your main blade view is
})->where('any', '.*');

/**
 * TODO: Insert Data ESD Settings
 * ? use post and get input from Input Form in App.vue
 * * function handleSubmit in App.vue
 */

Route::post('/insert-check',[ESDCheckInsertController::class,'insertCheck']);
