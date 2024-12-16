<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionTypeController;
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
    * Clients
    */
    Route::prefix('/clients')->group(function () {
        Route::get('/list', [ClientController::class,  'index'])->name('clients.index');
        Route::get('/details/{clientId}', [ClientController::class,  'show'])->name('clients.show');
        Route::post('/store', [ClientController::class,  'store'])->name('clients.save');
        Route::post('/update/{client}', [ClientController::class,  'update'])->name('clients.update');
        Route::post('/update-status/{client}', [ClientController::class,  'updateStatus'])->name('clients.updateStatus');
    });
    /*
      * Help
      */
    Route::get('/help', function () {
        return view('pages.admin.help');
    })->name('help');
    /*
    * Employees
    */
    Route::prefix('/employees')->group(callback: function () {
        Route::get('/list', [UserController::class,  'index'])->name('employees.index');
        Route::post('/store', [UserController::class,  'store'])->name('employees.save');
    });

    /*
    * Accounts
    */
    Route::prefix('/accounts')->group(function () {
        Route::get('/list', [AccountController::class,  'index'])->name('accounts.index');
        Route::get('/cotisations/{accountId}', [AccountController::class,  'cotisations'])->name('accounts.cotisations');

        Route::post('/store', [AccountController::class,  'store'])->name('accounts.save');
        /*
         * Types Accounts
         */
        Route::prefix('/types')->group(function () {
            Route::get('/list', [AccountTypeController::class,  'index'])->name('accounts.types.index');

            Route::post('/store', [AccountTypeController::class,  'store'])->name('accounts.types.save');
        });
    });
    /*
    * Transactions
    */
    Route::prefix('/transactions')->group(function () {
        Route::get('/history/{employeeId?}', [TransactionController::class, 'index'])->name('transactions.history');
        Route::get('/client-accounts/{clientID}', [TransactionController::class,  'getClientAccounts'])->name('transactions.clientAccounts');
        Route::get('/related-types/{relatedTo}', [TransactionController::class,  'getRelatedTransactionTypes'])->name('transactions.relatedTypes');
        Route::post('/store', [TransactionController::class, 'store'])->name('transactions.store');

        /*
        * Types Accounts
        */
        Route::prefix('/types')->group(function () {
            Route::get('/list', [TransactionTypeController::class,  'index'])->name('transactions.types.index');

            // Route::post('/store', [TransactionTypeController::class,  'store'])->name('transactions.types.save');
        });
    });
});

Route::prefix('/credits')->middleware(['auth'])->group(function () {
    Route::get('/list', [CreditController::class, 'index']
    )->name('web.credits.list');
    Route::get('/détails/{id}/credits', [CreditController::class, 'show']
    )->name('web.credits.show');
    Route::post('/store', [CreditController::class, 'store'])->name('web.credits.store');

    Route::get('/delete/{articleId}', [CreditController::class, 'destroy']
    )->name('web.credits.delete');

    Route::get('/enregistrements', [CreditController::class, 'orders'])->name('web.credits.order');
    Route::put('/update/{id}', [CreditController::class, 'update'])->name('web.credits.update');
});
