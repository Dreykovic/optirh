<?php

use App\Http\Controllers\Admin\ArticleAdminController;
use App\Http\Controllers\Admin\DisciplineAdminController;
use App\Http\Controllers\Admin\IssueAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\VolumeAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Web\ArticleWebController;
use App\Http\Controllers\Web\IssueWebController;
use App\Http\Controllers\Web\WebsiteWebController;
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

Route::get('/', [WebsiteWebController::class, 'home'])->name('home');

Route::prefix('/web')->group(function () {
    /*
     * Instructions
     */
    Route::prefix('/instructions')->group(function () {
        Route::get('/rapporteurs', function () { return view('pages.web.normes.rapporteurs'); })->name('web.instructions.rapporteurs');

        Route::get('/normes', function () { return view('pages.web.normes.index'); })->name('web.instructions.normes');
    });
    /*
     * Administration
     */
    Route::prefix('/administration')->group(function () {
        Route::get('/redaction', function () { return view('pages.web.normes.redaction'); })->name('web.administration.redaction');
        Route::get('/gestion', function () {return view('pages.web.normes.gestion'); })->name('web.administration.gestion');
        Route::get('/scientifique', function () {return view('pages.web.normes.scientifique'); })->name('web.administration.scientifique');
        Route::get('/about', [WebsiteWebController::class, 'aboutUs'])->name('web.administration.about');
    });
    /*
     * Articles
     */
    Route::prefix('/articles')->group(function () {
        Route::get('/list', [ArticleWebController::class, 'index']
        )->name('web.articles.list');
        Route::get('/détails/{articleId}', [ArticleWebController::class, 'show']
        )->name('web.articles.show');
    });

    /*
     * Volumes
     */
    Route::prefix('/volumes')->group(function () {
        Route::get('/list', [IssueWebController::class, 'volumes']
        )->name('web.volumes.list');
    });
    /*
     * Issues
     */
    Route::prefix('/issues')->group(function () {
        Route::get('/list/{volumeID}', [IssueWebController::class, 'index']
        )->name('web.issues.list');
    });
});

Route::group(['middleware' => 'guest'], function () {
    /*
     * Auth
     */
    Route::get('/login', [AuthController::class, 'login']
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
    /*
     * Admin
     */
    Route::prefix('/admin')->group(function () {
        /*
         * Articles
         */

        Route::prefix('/articles')->group(function () {
            Route::get('/list/{status?}', [ArticleAdminController::class, 'index'])->name('admin.articles.list');

            Route::post('/store', [ArticleAdminController::class, 'store'])->name('admin.articles.store');

            Route::get('/publish/{articleId}', [ArticleAdminController::class, 'publish'])->name('admin.articles.publish');
            Route::get('/hide/{articleId}', [ArticleAdminController::class, 'hide'])->name('admin.articles.hide');
            Route::post('/submit', [ArticleAdminController::class, 'submit'])->name('admin.articles.submit');
            Route::delete('/author/detach/{id}', [ArticleAdminController::class, 'detachAuthor'])->name('admin.articles.author.detach');
            Route::delete('/delete/{id}', [ArticleAdminController::class, 'destroy'])->name('admin.articles.delete');
            Route::prefix('/view')->group(function () {
                Route::get('/files/{matricule?}', [ArticleAdminController::class, 'article'])->name('admin.articles.view.files');
                Route::get('/authors/{matricule?}', [ArticleAdminController::class, 'article'])->name('admin.articles.view.authors');
                Route::get('/metadata/{matricule?}', [ArticleAdminController::class, 'article'])->name('admin.articles.view.metadata');
            });
            Route::prefix('/store')->group(function () {
                Route::post('/files', [ArticleAdminController::class, 'storeFiles'])->name('admin.articles.store.files');
                Route::post('/author/sync', [ArticleAdminController::class, 'syncAuthor'])->name('admin.articles.store.author.sync');
                Route::post('/author/create-and-sync', [ArticleAdminController::class, 'createAndSyncAuthor'])->name('admin.articles.store.author.createSync');
                Route::post('/metadata', [ArticleAdminController::class, 'storeMetadata'])->name('admin.articles.store.metadata');
            });
        });
        /*
         * Users
         */

        Route::prefix('/users')->group(function () {
            Route::get('/list', [UserAdminController::class,  'index'])->name('admin.users.list');
            Route::post('/store', [UserAdminController::class,  'store'])->name('admin.users.store');
            Route::post('/update', [UserAdminController::class,  'update'])->name('admin.users.update');
            Route::delete('/delete/{userId}', [UserAdminController::class,  'destroy'])->name('admin.users.delete');
        });

        /*
         * Discipline
         */

        Route::prefix('/disciplines')->group(function () {
            Route::get('/list', [DisciplineAdminController::class,  'index'])->name('admin.disciplines.list');
            Route::post('/store', [DisciplineAdminController::class,  'store'])->name('admin.disciplines.store');
            Route::post('/update', [DisciplineAdminController::class,  'update'])->name('admin.disciplines.update');
            Route::delete('/delete/{disciplineId}', [DisciplineAdminController::class,  'destroy'])->name('admin.disciplines.delete');
        });

        /*
         * Issues
         */

        Route::prefix('/issues')->group(function () {
            Route::get('/list/{volumeID}', [IssueAdminController::class,  'index'])->name('admin.issues.list');
            Route::post('/store', [IssueAdminController::class,  'store'])->name('admin.issues.store');
            Route::post('/update', [IssueAdminController::class,  'update'])->name('admin.issues.update');
            Route::delete('/delete/{issueId}', [IssueAdminController::class,  'destroy'])->name('admin.issues.delete');
        });

        /*
         * Volumes
         */

        Route::prefix('/volumes')->group(function () {
            Route::get('/list', [VolumeAdminController::class,  'index'])->name('admin.volumes.list');
            Route::post('/store', [VolumeAdminController::class,  'store'])->name('admin.volumes.store');
            Route::post('/update', [VolumeAdminController::class,  'update'])->name('admin.volumes.update');
            Route::delete('/delete/{volumeID}', [VolumeAdminController::class,  'destroy'])->name('admin.volumes.delete');
        });
        /*
         * Account
         */
        Route::prefix('/account')->group(function () {
            Route::get('/settings', [UserAdminController::class, 'settings'])->name('admin.account.settings');
            Route::post('/password/update', [UserAdminController::class, 'updatePassword'])->name('admin.account.update.password');
            Route::post('/personal-info/update', [UserAdminController::class, 'updatePersonalInfo'])->name('admin.account.update.info');
        });
    });
    /*
     * Logout
     */
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
