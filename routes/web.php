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



    //membres
    Route::prefix('membres')->group(function () {
        Route::get('/list', [EmployeeController::class, 'index'])->name('membres');
        Route::get('/pay', [EmployeeController::class, 'index'])->name('membres.pay');
        Route::get('/pay-form/v1', [EmployeeController::class, 'pay'])->name('membres.pay-form.v1');
        Route::get('/pay-form', [EmployeeController::class, 'paycode'])->name('membres.pay-form');

        Route::get('/pages', [EmployeeController::class, 'pages'])->name('membres.pages');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('membres.show');
        Route::post('/save', [EmployeeController::class, 'store'])->name('membres.store');
        Route::put('/update/{employee}', [EmployeeController::class, 'update'])->name('membres.update');
        Route::put('/update/pers/{id}', [EmployeeController::class, 'updatePres'])->name('membres.updatePres');
        Route::put('/update/bank/{employee}', [EmployeeController::class, 'updateBank'])->name('membres.updateBank');
        Route::get('/directions/list', [DepartmentController::class, 'index'])->name('directions');

    });
    Route::get('/api/membres/job/{id}', [EmployeeController::class, 'jobEmployees'])->name('membres.job');
    //mes données
    Route::prefix('employee')->group(function () {
        Route::get('/data', [EmployeeController::class, 'editEmployeeData'])->name('employee.data');
        Route::get('/pay/{employee}', [EmployeeController::class, 'mesFactures'])->name('employee.pay');    
    });

   
    Route::post('/employee/{id}/data', [EmployeeController::class, 'updateEmployeeData'])->name('membres.data.update');
   
    //directions
    Route::prefix('directions')->group(function () {
        Route::get('/{department}', [DepartmentController::class, 'show'])->name('directions.show');
        Route::post('/create', [DepartmentController::class, 'store'])->name('directions.store');
        Route::put('/{id}', [DepartmentController::class, 'update'])->name('directions.update');
        Route::delete('/{id}', [DepartmentController::class, 'destroy'])->name('directions.destroy');
    });

    //jobs
    Route::prefix('jobs')->group(function () {
        Route::post('/create', [JobController::class, 'store'])->name('jobs.store');
        Route::put('/{id}', [JobController::class, 'update'])->name('jobs.update');
        Route::delete('/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');
        
    });
    Route::get('/api/jobs/{departmentId}', [JobController::class, 'getJobsByDepartment']);

    //files
    Route::prefix('files')->group(function () {
        Route::post('/upload', [FileController::class, 'uploadFiles'])->name('files.uploads');
        Route::post('/upload/{employeeId}', [FileController::class, 'upload'])->name('files.upload');
        Route::put('/rename/{id}', [FileController::class, 'rename'])->name('files.rename');
        Route::delete('/delete/{fileId}', [FileController::class, 'delete'])->name('files.delete');
        Route::get('/download/{fileId}', [FileController::class, 'download'])->name('files.download');
        Route::get('/open/{fileId}', [FileController::class, 'openFile'])->name('files.open');
        Route::post('/invoices', [FileController::class, 'uploadInvoices'])->name('files.invoices');
        
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


