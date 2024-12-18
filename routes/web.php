<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AbsenceTypeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
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

Route::group(['middleware' => 'guest'], function () {
    /*
     * Auth
     */
    Route::get(
        '/login',
        [AuthController::class,  'login']
    )->name('login');
    Route::post(
        '/login',
        [AuthController::class, 'logUser']
    );

    Route::get('/login/forgot-password', [AuthController::class, 'forgotPasswordFormGet'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'sendEmail'])->name('send.mail');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPass'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'changePassword'])->name('password.update');
    Route::get('/pass/success', [AuthController::class, 'passwordChanged'])->name('password.changed');
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
    /*
     * Logout
     */
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
      * Help
      */
    Route::get('/help', function () {
        return view('pages.admin.help');
    })->name('help');


    /*
     * Attendances
     */

    Route::prefix('/attendances')->group(function () {
        /*
        * Absences
        */

        Route::prefix('/absences')->group(function () {
            Route::get('/requests', [AbsenceController::class,  'absencesRequests'])->name('absences.requests');
        });
        /*
        * Absences Types
        */

        Route::prefix('/absence-types')->group(function () {
            Route::get('/list', [AbsenceTypeController::class,  'index'])->name('absenceTypes.index');
        });
    });



});
