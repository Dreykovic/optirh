<?php

use Illuminate\Support\Facades\Route;
use Modules\Recours\App\Http\Controllers\RecoursController;
use Modules\Recours\App\Http\Controllers\DacController;
use App\Http\Controllers\HomeController;

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
    Route::get('/', [HomeController::class, 'recours_home'])->name('recours.home');

    Route::get('/index', [ RecoursController::class, 'index'])->name('recours.index');
    Route::get('/show/{id}', [ RecoursController::class, 'show'])->name('recours.show');
    Route::get('/new', [ RecoursController::class, 'create'])->name('recours.new');
    Route::put('/update/{id}', [ RecoursController::class, 'update'])->name('recours.update');
    Route::delete('/delete/{id}', [ RecoursController::class, 'destroy'])->name('recours.delete');
    Route::put('/accepted/{id}', [ RecoursController::class, 'accepted'])->name('recours.accepted');
    Route::put('/rejected/{id}', [ RecoursController::class, 'rejected'])->name('recours.rejected');

    Route::get('/api/data', [ RecoursController::class, 'appeal_loading'])->name('recours.loaging');
    Route::post('/store', [ RecoursController::class, 'store'])->name('recours.store');
    Route::post('/dacs/store', [ DacController::class, 'dacStore'])->name('dac.store');
    Route::post('/applicants/store', [ DacController::class, 'applicantStore'])->name('applicant.store');
});

// Route::group([], function () {
//     Route::resource('recours', RecoursController::class)->names('recours');
// });
