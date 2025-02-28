<?php

use Illuminate\Support\Facades\Route;
use Modules\Recours\App\Http\Controllers\RecoursController;
use Modules\Recours\App\Http\Controllers\DacController;

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

Route::prefix('recours')->group(function () {
    Route::get('/index', [ RecoursController::class, 'index'])->name('recours.index');
    Route::get('/new', [ RecoursController::class, 'create'])->name('recours.new');
    Route::get('/api/data', [ RecoursController::class, 'appeal_loading'])->name('recours.loaging');
    Route::post('/store', [ RecoursController::class, 'store'])->name('recours.store');
    Route::post('/dacs/store', [ DacController::class, 'dacStore'])->name('dac.store');
    Route::post('/applicants/store', [ DacController::class, 'applicantStore'])->name('applicant.store');
});

// Route::group([], function () {
//     Route::resource('recours', RecoursController::class)->names('recours');
// });
