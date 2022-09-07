<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

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
Auth::routes(['verify'=>true]);
Route::get('/', function () {
    if (auth()->user()) {
        return redirect()->route('dashboard.index');
    }
    return view('auth/login');
});

Route::group(['middleware' => ['auth']], function() {
    Route::GET('profit-calculation', [HomeController::class, 'profit_calculation']);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('customers', CustomerController::class);
    Route::GET('/dashboard', [HomeController::class, 'index'])->name('dashboard.index');
    Route::GET('/activity-log', [SettingController::class, 'activity'])->name('activity-log.index');
    Route::GET('/transactions', [TransactionController::class, 'index'])->name('transaction.index');
    Route::DELETE('/transactions-delete/{id}', [ReportController::class, 'destroy'])->name('report.destory');
    Route::DELETE('/customers-delete/{id}', [CustomerController::class, 'destroy'])->name('customers.destory');
    Route::GET('/delete-transactions', [ReportController::class, 'deleteTranaction'])->name('delete.transaction');


    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::POST('save-transaction', [TransactionController::class, 'save_transaction']);
    Route::GET('edit-transaction/{transacton_id}', [TransactionController::class, 'edit_transaction']);
    Route::GET('get-transaction', [TransactionController::class, 'Get_transaction']);
    Route::GET('transaction-detail/{customer_id}',[TransactionController::class,'transaction_detail']);

    Route::GET('get-balance/{customer_id}', [ReportController::class, 'get_balance']);
    Route::GET('get-paid-received', [ReportController::class, 'get_paid_received']);
    Route::GET('receipt_data', [ReportController::class, 'receipt_data']);
    Route::GET('balanced-amount-details',[TransactionController::class, 'details']);
    Route::GET('pending-amount-details',[TransactionController::class, 'details']);
    Route::POST('invoice_data',[ReportController::class, 'invoice_data']);
    Route::POST('get-customer-paid-received',[ReportController::class, 'get_customer_paid_received']);
});
Auth::routes();

// Route::get('/reports', function () {
//     return view('pages.reports');
// });

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/invoice', function () {
    return view('pages.invoice');
});