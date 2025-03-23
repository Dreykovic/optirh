<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OptiHr\AbsenceController;
use App\Http\Controllers\OptiHr\AbsenceTypeController;
use App\Http\Controllers\OptiHr\AnnualDecisionController;
use App\Http\Controllers\OptiHr\DepartmentController;
use App\Http\Controllers\OptiHr\DocumentRequestController;
use App\Http\Controllers\OptiHr\DocumentTypeController;
use App\Http\Controllers\OptiHr\DutyController;
use App\Http\Controllers\OptiHr\EmployeeController;
use App\Http\Controllers\OptiHr\FileController;
use App\Http\Controllers\OptiHr\HolidayController;
use App\Http\Controllers\OptiHr\JobController;
use App\Http\Controllers\OptiHr\PublicationController;
use App\Http\Controllers\Recours\DacController;
use App\Http\Controllers\Recours\RecoursController;
use App\Http\Controllers\Recours\StatsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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
    /*
    * Logout
    */
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    /*
     * OptiHR
     */
    Route::prefix('/opti-hr')->group(function () {
        Route::get('/', [HomeController::class, 'home'])->name('home');

        /*
          * Help
        */
        Route::get('/help', function () {
            return view('pages.admin.help');
        })->name('help');

        // membres
        Route::prefix('membres')->group(function () {
            Route::get('/list', [EmployeeController::class, 'index'])->name('membres');
            Route::get('/pay', [EmployeeController::class, 'index'])->name('membres.pay');
            Route::get('/pay-form/v1', [EmployeeController::class, 'pay'])->name('membres.pay-form.v1');
            Route::get('/pay-form', [EmployeeController::class, 'paycode'])->name('membres.pay-form');
            Route::get('/contrats', [DutyController::class, 'index'])->name('contrats.index');

            Route::get('/pages', [EmployeeController::class, 'pages'])->name('membres.pages');
            Route::get('/pages/{employee}', [EmployeeController::class, 'show'])->name('membres.show');
            Route::post('/save', [EmployeeController::class, 'store'])->name('membres.store');
            Route::put('/update/{employee}', [EmployeeController::class, 'update'])->name('membres.update');
            Route::put('/update/pers/{id}', [EmployeeController::class, 'updatePres'])->name('membres.updatePres');
            Route::put('/update/pers/identity/{id}', [EmployeeController::class, 'updatePresIdentity'])->name('membres.updatePresIdentity');
            Route::put('/update/bank/{employee}', [EmployeeController::class, 'updateBank'])->name('membres.updateBank');
            Route::get('/directions/list', [DepartmentController::class, 'index'])->name('directions');
            Route::get('/directions/list/{department}', [DepartmentController::class, 'show'])->name('directions.show'); // direction show
        });
        // mes données
        Route::prefix('employee')->group(function () {
            Route::get('/data', [EmployeeController::class, 'editEmployeeData'])->name('employee.data');
            Route::get('/pay/{employee}', [EmployeeController::class, 'mesFactures'])->name('employee.pay');
        });

        Route::post('/employee/{id}/data', [EmployeeController::class, 'updateEmployeeData'])->name('membres.data.update');

        // directions
        Route::prefix('directions')->group(function () {
            // Route::get('/{department}', [DepartmentController::class, 'show'])->name('directions.show');
            Route::post('/create', [DepartmentController::class, 'store'])->name('directions.store');
            Route::put('/{id}', [DepartmentController::class, 'update'])->name('directions.update');
            Route::delete('/{id}', [DepartmentController::class, 'destroy'])->name('directions.destroy');
        });

        // jobs
        Route::prefix('jobs')->group(function () {
            Route::post('/create', [JobController::class, 'store'])->name('jobs.store');
            Route::put('/{id}', [JobController::class, 'update'])->name('jobs.update');
            Route::delete('/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');
        });

        // files
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
         * contrats
         */
        Route::prefix('contrats')->group(function () {
            Route::get('/request/{ev}', [DutyController::class, 'contrats'])->name('contrats.encours');
            Route::put('/{id}/suspended', [DutyController::class, 'suspended'])->name('contrats.suspended');
            Route::put('/{id}/ongoing', [DutyController::class, 'ongoing'])->name('contrats.ongoing');
            Route::put('/{id}/ended', [DutyController::class, 'ended'])->name('contrats.ended');
            Route::put('/{id}/resigned', [DutyController::class, 'resigned'])->name('contrats.resigned');
            Route::put('/{id}/dismissed', [DutyController::class, 'dismissed'])->name('contrats.dismissed');
            Route::put('/{id}/deleted', [DutyController::class, 'deleted'])->name('contrats.deleted');
            Route::post('/add', [DutyController::class, 'add'])->name('contrats.add');
        });

        Route::prefix('api')->group(function () {
            Route::get('/files/{employeeId}', [FileController::class, 'getFiles']);
            Route::get('/jobs/{departmentId}', [JobController::class, 'getJobsByDepartment']);
            Route::get('/membres/job/{id}', [EmployeeController::class, 'jobEmployees'])->name('membres.job');
        });

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
                Route::get('/request/download/{absenceId}', [AbsenceController::class,  'download'])->name('absences.download');
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

            /*
       * Decisions
       */

            Route::prefix('/annual-decisions')->group(function () {
                Route::get('/view', [AnnualDecisionController::class,  'show'])->name('decisions.show');
                Route::post('/save/{annualDecisionId?}', [AnnualDecisionController::class,  'storeOrUpdate'])->name('decisions.save');
            });
        });
        /*
         * Documents
         */

        Route::prefix('/documents')->group(function () {
            /*
            * Document Request
            */

            Route::prefix('/requests')->group(function () {
                Route::get('/index/{stage?}', [DocumentRequestController::class,  'index'])->name('documents.requests');
                Route::get('/create', [DocumentRequestController::class,  'create'])->name('documents.create');
                Route::post('/save', [DocumentRequestController::class,  'store'])->name('documents.save');
                Route::post('/approve/{absenceId}', [DocumentRequestController::class,  'approve'])->name('documents.approve');
                Route::post('/reject/{absenceId}', [DocumentRequestController::class,  'reject'])->name('documents.reject');
                Route::post('/comment/{absenceId}', [DocumentRequestController::class,  'comment'])->name('documents.comment');
                Route::post('/cancel/{absenceId}', [DocumentRequestController::class,  'cancel'])->name('documents.cancel');
                Route::get('/download/{absenceId}', [DocumentRequestController::class,  'download'])->name('documents.download');
            });
            /*
            * Document Types
            */

            Route::prefix('/document-types')->group(function () {
                Route::get('/list', [DocumentTypeController::class,  'index'])->name('documentTypes.index');
                Route::post('/save', [DocumentTypeController::class,  'store'])->name('documentTypes.save');
                Route::post('/update/{documentTypeId}', [DocumentTypeController::class,  'update'])->name('documentTypes.update');
                Route::delete('/delete/{documentTypeId}', [DocumentTypeController::class,  'destroy'])->name('documentTypes.destroy');
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
                Route::post('/update-details/{userId}', [UserController::class,   'updateDetails'])->name('credentials.updateDetails');
                Route::post('/update-password/{userId}', [UserController::class,   'updatePassword'])->name('credentials.updatePwd');
                Route::post('/change-password/{userId}', [UserController::class,   'changePassword'])->name('credentials.changePassword');
                Route::post('/change-role/{userId}', [UserController::class,   'updateRole'])->name('credentials.updateRole');
                Route::delete('/delete/{userId}', [UserController::class,   'destroy'])->name('credentials.destroy');
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
            /*
             * Activity Log
            */

            Route::prefix('/activity-logs')->group(function () {
                // Routes pour les logs d'activité
                Route::get('/', [ActivityLogController::class, 'index'])->name('activity-logs.index');

                // Si vous souhaitez ajouter une route pour voir les détails d'un log spécifique
                Route::get('/{log}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
            });
        });

        /*
        * Publications
        */

        Route::prefix('/publications')->group(function () {
            /*
            * Identifiants
            */
            Route::prefix('/config')->group(function () {
                Route::get('/list/{status?}', [PublicationController::class,   'index'])->name('publications.config.index');
                Route::post('/save', [PublicationController::class,   'store'])->name('publications.config.save');
                Route::post('/update-status/{status}/{publicationId}', [PublicationController::class,   'updateStatus'])->name('publications.config.updateStatus');
                Route::delete('/delete/{userId}', [PublicationController::class,   'destroy'])->name('publications.config.destroy');
            });
            /*
            * Pdf
            */
            Route::prefix('/pdf')->group(function () {
                Route::get('/preview/{publicationId}', [PublicationController::class,   'preview'])->name('publications.pdf.preview');
            });
        });
    });
    /*
     * Recours
     */
    Route::prefix('recours')->group(function () {
        Route::get('/', [HomeController::class, 'recours_home'])->name('recours.home');
        Route::post('/', [HomeController::class, 'recours_home'])->name('recours.home');

        Route::get('/index', [RecoursController::class, 'index'])->name('recours.index');
        Route::get('/show/{id}', [RecoursController::class, 'show'])->name('recours.show');
        Route::get('/new', [RecoursController::class, 'create'])->name('recours.new');
        Route::put('/update/{id}', [RecoursController::class, 'update'])->name('recours.update');
        Route::delete('/delete/{id}', [RecoursController::class, 'destroy'])->name('recours.delete');
        Route::put('/accepted/{id}', [RecoursController::class, 'accepted'])->name('recours.accepted');
        Route::put('/rejected/{id}', [RecoursController::class, 'rejected'])->name('recours.rejected');

        Route::get('/api/data', [RecoursController::class, 'appeal_loading'])->name('recours.loaging');
        Route::post('/store', [RecoursController::class, 'store'])->name('recours.store');
        Route::post('/dacs/store', [DacController::class, 'dacStore'])->name('dac.store');
        Route::post('/applicants/store', [DacController::class, 'applicantStore'])->name('applicant.store');
        Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');
    });
});
