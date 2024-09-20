<?php

use App\Http\Controllers\ControllerParameters;
use App\Http\Controllers\SensoresController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    return redirect()->route('sensores.index');
});

Route::resource('sensores', SensoresController::class)->names('sensores');
Route::resource('parameters', ControllerParameters::class)->names('parameters');
