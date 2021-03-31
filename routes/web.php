<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneratorController;
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


Auth::routes();
Route::group(['middleware' => ['auth']], function() {
    Route::get('generator', [GeneratorController::class, 'index']);
    Route::get('/customer-list', [GeneratorController::class, 'customer_list']);
    Route::get('/get-circuit', [GeneratorController::class, 'get_circuit']);

    Route::get('chart', [ChartController::class, 'index'])->name('chart.index');
    Route::get('chart/pdf', [ChartController::class, 'createPDF']);

    Route::get('edit-data', [ChartController::class, 'edit'])->name('edit-data.edit');
    Route::post('edit-update', [ChartController::class, 'update']);
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});