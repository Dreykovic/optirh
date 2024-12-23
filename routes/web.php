<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AbsenceTypeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmployeeController;

use App\Http\Controllers\HolidayController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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



    //Personnel job_employees 
    Route::get('/membres/list', [EmployeeController::class, 'index'])->name('membres');
    Route::get('/employee/data', [EmployeeController::class, 'updateEmployeeData'])->name('membres.data');
    Route::get('/membres/{employee}', [EmployeeController::class, 'show'])->name('membres.show');
    Route::get('/api/membres/job{id}', [EmployeeController::class, 'jobEmployees'])->name('membres.job');
    Route::post('/membres/save', [EmployeeController::class, 'store'])->name('membres.store');
    Route::put('/membres/update/{employee}', [EmployeeController::class, 'update'])->name('membres.update');
    Route::put('/membres/update/pers/{id}', [EmployeeController::class, 'updatePres'])->name('membres.updatePres');
    Route::put('/membres/update/bank/{employee}', [EmployeeController::class, 'updateBank'])->name('membres.updateBank');

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
    




    /*
     * Attendances
     */

    Route::prefix('/attendances')->group(function () {
        /*
        * Absences
        */

        Route::prefix('/absences')->group(function () {
            Route::get('/requests/{stage?}', [AbsenceController::class,  'index'])->name('absences.requests');
            Route::get('/request/create', [AbsenceController::class,  'create'])->name('absences.create');
            Route::post('/request/save', [AbsenceController::class,  'store'])->name('absences.save');
            Route::post('/request/approve/{absenceId}', [AbsenceController::class,  'approve'])->name('absences.approve');
            Route::post('/request/reject/{absenceId}', [AbsenceController::class,  'reject'])->name('absences.reject');
            Route::post('/request/comment/{absenceId}', [AbsenceController::class,  'comment'])->name('absences.comment');
            Route::post('/request/cancel/{absenceId}', [AbsenceController::class,  'cancel'])->name('absences.cancel');
        });
        /*
        * Absences Types
        */

        Route::prefix('/absence-types')->group(function () {
            Route::get('/list', [AbsenceTypeController::class,  'index'])->name('absenceTypes.index');
            Route::post('/save', [AbsenceTypeController::class,  'store'])->name('absenceTypes.save');
            Route::post('/update/{absenceTypeId}', [AbsenceTypeController::class,  'update'])->name('absenceTypes.update');
            Route::delete('/delete/{absenceTypeId}', [AbsenceTypeController::class,  'destroy'])->name('absenceTypes.destroy');
        });

        /*
        * Holidays
        */

        Route::prefix('/holidays')->group(function () {
            Route::get('/list/{stage?}', [HolidayController::class,  'index'])->name('holidays.index');
            Route::post('/save', [HolidayController::class,  'store'])->name('holidays.save');
            Route::post('/update/{holidayId}', [HolidayController::class,  'update'])->name('holidays.update');
            Route::delete('/delete/{holidayId}', [HolidayController::class,  'destroy'])->name('holidays.destroy');
        });
    });

    /*
    * Users Management
    */

    Route::prefix('/users-management')->group(function () {
        /*
        * Identifiants
        */
        Route::prefix('/credentials')->group(function () {
            Route::get('/list/{status?}', [UserController::class,   'index'])->name('credentials.index');
            Route::post('/save', [UserController::class,   'store'])->name('credentials.save');
        });

        /*
         * Rôles
         */
        Route::prefix('/roles')->group(function () {
            Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('roles.delete');

            Route::post('/add', [RoleController::class, 'store'])->name('roles.add');
            Route::post('/update/{id}', [RoleController::class, 'update'])->name('roles.update');

            Route::get('/list', [RoleController::class, 'index'])->name('roles.index');
            Route::get('/details/{id}', [RoleController::class, 'show'])->name('roles.details');
        });

        /*
         * Permissions
         */

        Route::prefix('/permissions')->group(function () {
            Route::get('/list', [RoleController::class, 'get_permissions'])->name('permissions.index');
        });
    });
});


