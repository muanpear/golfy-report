<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('landing');
});

Route::get('generator', [ChartController::class, 'index1']);

Route::post('chart', [ChartController::class, 'index'])->name('chart.index');
Route::get('chart/pdf', [ChartController::class, 'createPDF']);