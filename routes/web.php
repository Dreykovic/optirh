<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\FileController;

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
    Route::get('/login', [AuthController::class,  'login']
    )->name('login');
    Route::post('/login', [AuthController::class, 'logUser']
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



    //Personnel job_employees
    Route::get('/membres/list', [EmployeeController::class, 'index'])->name('membres');
    Route::get('/membres/{employee}', [EmployeeController::class, 'show'])->name('membres.show');
    Route::get('/api/membres/job{id}', [EmployeeController::class, 'jobEmployees'])->name('membres.job');
    Route::post('/membres/save', [EmployeeController::class, 'store'])->name('membres.store');
    Route::post('/membres/update', [EmployeeController::class, 'update'])->name('membres.update');

    Route::get('/membres/directions/list', [DepartmentController::class, 'index'])->name('directions');
    Route::get('/directions/{department}', [DepartmentController::class, 'show'])->name('directions.show');
    Route::post('/directions/create', [DepartmentController::class, 'store'])->name('directions.store');
    Route::put('/directions/{id}', [DepartmentController::class, 'update'])->name('directions.update');
    Route::delete('/directions/{id}', [DepartmentController::class, 'destroy'])->name('directions.destroy');


    Route::post('/jobs/create', [JobController::class, 'store'])->name('jobs.store');
    Route::put('/jobs/{id}', [JobController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');

    Route::get('/api/jobs/{departmentId}', [JobController::class, 'getJobsByDepartment']);

    //files
    Route::prefix('files')->group(function () {
        Route::post('/upload/{employeeId}', [FileController::class, 'upload'])->name('files.upload');
        Route::post('/rename', [FileController::class, 'rename'])->name('files.rename');
        Route::delete('/delete/{fileId}', [FileController::class, 'delete'])->name('files.delete');
        Route::get('/download/{fileId}', [FileController::class, 'download'])->name('files.download');
        Route::get('/open/{fileId}', [FileController::class, 'openFile'])->name('files.open');
        
    });
    Route::get('/api/files/{employeeId}', [FileController::class, 'getFiles']);
    



});


