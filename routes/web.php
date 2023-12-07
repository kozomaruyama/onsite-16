<?php

use Illuminate\Support\Facades\Route;

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
    Route::post('/line/webhook/message', [App\Http\Controllers\LineWebhookController::class,'message'])->name('line.webhook.message');

    Route::get('/', [App\Http\Controllers\LoginController::class, 'getAuth'])->name('login');
    Route::get('/login', [App\Http\Controllers\LoginController::class, 'getAuth'])->name('login');
    Route::post('/login', [App\Http\Controllers\LoginController::class, 'postAuth_login']);
    Route::post('/logout', [App\Http\Controllers\LoginController::class, 'postAuth_logout']);

    
    // システム
    Route::get('/sys_items', [App\Http\Controllers\SysItemController::class, 'index'])->middleware(['auth'])->name('sys_items');
    Route::get('/sys_items/taxratio', 'App\Http\Controllers\SysItemController@taxratio');


    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['auth'])->name('home');
    Route::get('/calendar', [App\Http\Controllers\CalendarController::class, 'index'])->middleware(['auth'])->name('calendar');
    Route::get('/gantto', [App\Http\Controllers\GanttoController::class, 'index'])->middleware(['auth'])->name('gantto');


    // スケジュール
    Route::get('/schedule', [App\Http\Controllers\ScheduleController::class, 'index'])->middleware(['auth'])->name('schedule');
    Route::get('/schedule/{id}', [App\Http\Controllers\ScheduleController::class, 'read'])->middleware(['auth'])->name('read')->where('id', '[0-9]+');
    Route::get('/schedule/scheduleId/{id}', [App\Http\Controllers\ScheduleController::class, 'read_scheduleId'])->middleware(['auth'])->name('read_scheduleId')->where('id', '[0-9]+');
    Route::get('/schedule/personId/{id}', [App\Http\Controllers\ScheduleController::class, 'read_personId'])->middleware(['auth'])->name('read_personId')->where('id', '[0-9]+');
    Route::get('/schedule/subjectId/{id}', [App\Http\Controllers\ScheduleController::class, 'read_subjectId'])->middleware(['auth'])->name('read_subjectId')->where('id', '[0-9]+');
    Route::post('/schedule/filter', [App\Http\Controllers\ScheduleController::class, 'filter'])->middleware(['auth'])->name('schedule.filter');
    Route::post('/schedule/calendar', [App\Http\Controllers\ScheduleController::class, 'calendar'])->middleware(['auth'])->name('schedule.calendar');
    Route::post('/schedule/bill', [App\Http\Controllers\ScheduleController::class, 'bill'])->middleware(['auth'])->name('schedule.bill');
    Route::post('/schedule/store', [App\Http\Controllers\ScheduleController::class, 'store'])->middleware(['auth'])->name('schedule.store');
    // Route::put('/schedule', [App\Http\Controllers\ScheduleController::class, 'update'])->middleware(['auth'])->name('schedule.update');
    Route::delete('/schedule/{schedule}', [App\Http\Controllers\ScheduleController::class, 'destroy'])->middleware(['auth'])->name('schedule.destroy');
    Route::put('/schedule/{schedule}', [App\Http\Controllers\ScheduleController::class, 'update'])->middleware(['auth'])->name('schedule.update');
    Route::put('/schedule/update_percentage/{schedule}', [App\Http\Controllers\ScheduleController::class, 'update_percentage'])->middleware(['auth'])->name('schedule.update_percentage');

    // 案件
    Route::get('/subject/{id}', [App\Http\Controllers\SubjectController::class, 'read'])->middleware(['auth'])->name('read')->where('id', '[0-9]+');;
    Route::get('/subject/list', [App\Http\Controllers\SubjectController::class, 'list'])->middleware(['auth'])->name('subject.list');

    // 社員
    Route::get('/people/{id}', [App\Http\Controllers\PersonController::class, 'read'])->middleware(['auth']);

    //一般ユーザー
    Route::group(['middleware' => ['auth', 'can:user-higher']], function () {
        //ここにルートを記述 
    });

    // メール送受信
    Route::get('/mail/send', [App\Http\Controllers\MailController::class, 'send'])->middleware(['auth'])->name('send');
    
    // 管理者以上
    Route::group(['middleware' => ['auth', 'can:admin-higher']], function () {
        // 
        Route::get('/account/{id}', [App\Http\Controllers\UserController::class, 'read'])->middleware(['auth'])->name('read')->where('id', '[0-9]+');
        Route::post('/account', [App\Http\Controllers\UserController::class, 'update'])->middleware(['auth'])->name('account.update');
        Route::post('/account/store', [App\Http\Controllers\UserController::class, 'store'])->middleware(['auth'])->name('account.store');
        Route::post('/account/delete', [App\Http\Controllers\UserController::class, 'destroy'])->middleware(['auth'])->name('account.destroy');
        
        // 会社
        Route::get('/company', [App\Http\Controllers\CompanyController::class, 'index'])->middleware(['auth'])->name('company');
        Route::get('/company/{id}', [App\Http\Controllers\CompanyController::class, 'read'])->middleware(['auth'])->name('company.read');
        Route::put('/company/{company}', [App\Http\Controllers\CompanyController::class, 'update'])->middleware(['auth'])->name('company.update');
        
        // 案件
        Route::get('/subject', [App\Http\Controllers\SubjectController::class, 'date'])->middleware(['auth'])->name('subject');
        Route::get('/subject/date/{ym}', [App\Http\Controllers\SubjectController::class, 'date'])->middleware(['auth'])->name('subject.date');
        Route::get('/subject/prime/{id}', [App\Http\Controllers\SubjectController::class, 'prime'])->middleware(['auth'])->name('subject.prime');
        Route::get('/subject/subcontract/{id}', [App\Http\Controllers\SubjectController::class, 'subcontract'])->middleware(['auth'])->name('subject.subcontract');
        Route::get('/subject/ym', [App\Http\Controllers\SubjectController::class, 'ym'])->middleware(['auth'])->name('subject.ym');
        Route::post('/subject/calendar', [App\Http\Controllers\SubjectController::class, 'calendar'])->middleware(['auth'])->name('subject.calendar');
        Route::post('/subject/gantto', [App\Http\Controllers\SubjectController::class, 'gantto'])->middleware(['auth'])->name('subject.gantto');
        // Route::get('/subject/{id}', [App\Http\Controllers\SubjectController::class, 'read'])->middleware(['auth'])->name('read')->where('id', '[0-9]+');
        Route::post('/subject/read_in', [App\Http\Controllers\SubjectController::class, 'read_in'])->middleware(['auth'])->name('read_in');
        Route::post('/subjects/clients',  [App\Http\Controllers\SubjectController::class, 'clients'])->middleware(['auth'])->name('clients');
        Route::post('/subject', [App\Http\Controllers\SubjectController::class, 'update'])->middleware(['auth'])->name('subject.update');
        Route::post('/subject/group', [App\Http\Controllers\SubjectController::class, 'group'])->middleware(['auth'])->name('subject.group');
        Route::post('/subject/store', [App\Http\Controllers\SubjectController::class, 'store'])->middleware(['auth'])->name('subject.store');
        Route::post('/subject/storeTasks', [App\Http\Controllers\SubjectController::class, 'storeTasks'])->middleware(['auth'])->name('subject.storeTasks');
        Route::post('/subject/filter', [App\Http\Controllers\SubjectController::class, 'filter'])->middleware(['auth'])->name('subject.filter');
        Route::post('/subject/print', [App\Http\Controllers\SubjectController::class, 'print'])->middleware(['auth'])->name('subject.print');
        Route::delete('/subject/{subject}', [App\Http\Controllers\SubjectController::class, 'destroy'])->middleware(['auth'])->name('subject.destroy');

        // スケジュール
        Route::post('/schedule/mansheet', [App\Http\Controllers\ScheduleController::class, 'managimentSheet'])->middleware(['auth'])->name('schedule.mansheet');
        Route::get('/schedule/pay/{id}', [App\Http\Controllers\ScheduleController::class, 'pay'])->middleware(['auth'])->name('schedule.pay');

        // タスク
        Route::get('/task/{id}', [App\Http\Controllers\TaskController::class, 'read'])->middleware(['auth'])->name('read');
        Route::get('/tasks/subject/{id}', [App\Http\Controllers\TaskController::class, 'subject'])->middleware(['auth'])->name('task.subject');
        Route::post('/task', [App\Http\Controllers\TaskController::class, 'update'])->middleware(['auth'])->name('task.update');
        Route::post('/task/calendar', [App\Http\Controllers\TaskController::class, 'calendar'])->middleware(['auth'])->name('task.calendar');
        Route::post('/task/store', [App\Http\Controllers\TaskController::class, 'store'])->middleware(['auth'])->name('task.store');
        Route::post('/task/group', [App\Http\Controllers\TaskController::class, 'group'])->middleware(['auth'])->name('task.group');

        // 商品
        Route::get('/products',[App\Http\Controllers\ProductController::class, 'index'])->middleware(['auth'])->name('products');
        Route::get('/products/{id}',[App\Http\Controllers\ProductController::class, 'read'])->middleware(['auth'])->name('products.read');
        Route::put('/products/{product}',[App\Http\Controllers\ProductController::class, 'update'])->middleware(['auth'])->name('products.update');
        Route::delete('/products/{product}', [App\Http\Controllers\ProductController::class, 'destroy'])->middleware(['auth'])->name('product.destroy');
        Route::get('/products/export',[App\Http\Controllers\ProductController::class, 'export'])->middleware(['auth'])->name('products.export');
        Route::post('/products',[App\Http\Controllers\ProductController::class, 'store'])->middleware(['auth'])->name('products.store');

        // 請求
        Route::get('/invoice', [App\Http\Controllers\InvoiceController::class, 'index'])->middleware(['auth'])->name('invoice');
        Route::get('/invoice/{id}', [App\Http\Controllers\InvoiceController::class, 'read'])->middleware(['auth'])->name('invoice.read')->where('id', '[0-9]+');
        Route::put('/invoices/{invoice}', [App\Http\Controllers\InvoiceController::class, 'update'])->middleware(['auth'])->name('invoice.update');
        // Route::get('/invoice/subject', [App\Http\Controllers\InvoiceController::class, 'read_subject'])->middleware(['auth'])->name('read_subject');
        // Route::get('/invoice/refresh', [App\Http\Controllers\InvoiceController::class, 'refresh'])->middleware(['auth'])->name('refresh');
        Route::post('/invoices', [App\Http\Controllers\InvoiceController::class, 'group'])->middleware(['auth'])->name('group');
        Route::post('/invoices/close', [App\Http\Controllers\InvoiceController::class, 'closing'])->middleware(['auth'])->name('closing');
        Route::post('/invoices/extract', [App\Http\Controllers\InvoiceController::class, 'extract'])->middleware(['auth'])->name('extract');
        Route::delete('/invoices/{id}', [App\Http\Controllers\InvoiceController::class, 'destroy'])->middleware(['auth'])->name('invoice.destroy');
        
        // 請求マスター
        Route::get('/invoice_masters/{invoiceId}', [App\Http\Controllers\InvoiceMasterController::class, 'index'])->middleware(['auth'])->name('invoice_master');
        Route::get('/invoice_masters/read/{id}', [App\Http\Controllers\InvoiceMasterController::class, 'read'])->middleware(['auth'])->name('invoice_master.read');
        Route::delete('/invoice_masters/{id}', [App\Http\Controllers\InvoiceMasterController::class, 'destroy'])->middleware(['auth'])->name('invoice_master.destroy');
        Route::post('/invoice_master/print', [App\Http\Controllers\InvoiceMasterController::class, 'print'])->middleware(['auth'])->name('invoice_master.print');
        Route::post('/invoice_master/update', [App\Http\Controllers\InvoiceMasterController::class, 'update'])->middleware(['auth'])->name('invoice_master.update');
        Route::post('/invoice_master/marge', [App\Http\Controllers\InvoiceMasterController::class, 'marge'])->middleware(['auth'])->name('invoice_master.marge');

        // 請求明細
        Route::post('/invoice_details', [App\Http\Controllers\InvoiceDetailController::class, 'update'])->middleware(['auth'])->name('invoice_detail.update');
        Route::delete('/invoice_details/{id}', [App\Http\Controllers\InvoiceDetailController::class, 'destroy'])->middleware(['auth'])->name('invoice_detail.destroy');

        // クライアント
        Route::get('/clients', [App\Http\Controllers\ClientController::class, 'index'])->middleware(['auth'])->name('client');
        Route::get('/clients/{id}', [App\Http\Controllers\ClientController::class, 'read'])->where('id', '[0-9]+')->middleware(['auth'])->name('client.read');
        Route::get('/clients/prime', [App\Http\Controllers\ClientController::class, 'prime'])->middleware(['auth'])->name('client.prime');
        Route::get('/clients/subcontract', [App\Http\Controllers\ClientController::class, 'subcontracts'])->middleware(['auth'])->name('client.subcontracts');
        Route::get('/clients/withSchedule/{ym}', [App\Http\Controllers\ClientController::class, 'withSchedule'])->middleware(['auth'])->name('client.withSchedule');
        Route::get('/clients/class/{class}', [App\Http\Controllers\ClientController::class, 'class'])->middleware(['auth'])->name('client.class');
        Route::post('/clients', [App\Http\Controllers\ClientController::class, 'store'])->middleware(['auth'])->name('client.store');
        Route::put('/clients/{client}', [App\Http\Controllers\ClientController::class, 'update'])->middleware(['auth'])->name('client.update');
        Route::delete('/clients/{client}', [App\Http\Controllers\ClientController::class, 'destroy'])->middleware(['auth'])->name('client.destroy');

        // bill
        Route::get('/bill', [App\Http\Controllers\BillController::class, 'index'])->middleware(['auth']);
        Route::get('/bill/{ym}', [App\Http\Controllers\BillController::class, 'catrgry_ym'])->middleware(['auth'])->name('catrgry_ym');
        Route::get('/bill/all/{ym}', [App\Http\Controllers\BillController::class, 'all'])->middleware(['auth'])->name('bill.all');
        Route::get('/bill/opt/{ym}', [App\Http\Controllers\BillController::class, 'opt'])->middleware(['auth'])->name('bill.opt');
        Route::get('/bill/calendar/{ym}', [App\Http\Controllers\BillController::class, 'calendar'])->middleware(['auth'])->name('bill.calendar');
        Route::get('/bill/client/{id}', [App\Http\Controllers\BillController::class, 'client'])->middleware(['auth'])->name('bill.client');
        Route::post('/bill/details', [App\Http\Controllers\BillController::class, 'details'])->middleware(['auth'])->name('bill.details');
        Route::post('/bill/close', [App\Http\Controllers\BillController::class, 'close'])->middleware(['auth'])->name('bill.close');

        // pay
        Route::get('/pay', [App\Http\Controllers\PayController::class, 'index'])->middleware(['auth']);
        Route::get('/pay/date/{ym}', [App\Http\Controllers\PayController::class, 'date'])->middleware(['auth'])->name('pay.date');
        Route::get('/pay/date_master/{ym}', [App\Http\Controllers\PayController::class, 'date_master'])->middleware(['auth'])->name('pay.date_master');
        Route::post('/pay/details/date', [App\Http\Controllers\PayController::class, 'date_details'])->middleware(['auth'])->name('pay.date_details');
        Route::get('/pay/subcontracter', [App\Http\Controllers\PayController::class, 'subcontracter'])->middleware(['auth'])->name('pay.subcontracter');
        Route::get('/pay/subcontracter_master/{client_id}', [App\Http\Controllers\PayController::class, 'subcontracter_master'])->middleware(['auth'])->name('pay.subcontracter_master');
        Route::post('/pay/details/subcontracter', [App\Http\Controllers\PayController::class, 'subcontracter_details'])->middleware(['auth'])->name('pay.subcontracter_details');
        Route::get('/pay/read/{id}', [App\Http\Controllers\PayController::class, 'read'])->middleware(['auth'])->name('pay.read')->where('id', '[0-9]+');
        Route::post('/pay/extract', [App\Http\Controllers\PayController::class, 'extract'])->middleware(['auth'])->name('pay.extract');
        Route::post('/pay/details', [App\Http\Controllers\PayController::class, 'details'])->middleware(['auth'])->name('pay.details');
        Route::put('/pay/{pay}', [App\Http\Controllers\PayController::class, 'update'])->middleware(['auth'])->name('pay.update');
        Route::post('/pay/close', [App\Http\Controllers\PayController::class, 'close'])->middleware(['auth'])->name('pay.close');
        Route::post('/pay', [App\Http\Controllers\PayController::class, 'store'])->middleware(['auth'])->name('pay.store');
        Route::delete('/pay/{pay}', [App\Http\Controllers\PayController::class, 'destroy'])->middleware(['auth'])->name('pay.destroy');

        // マスター
        Route::get('/master', [App\Http\Controllers\MasterController::class, 'index'])->middleware(['auth'])->name('master');
        
        // 社員 
        Route::get('/people', [App\Http\Controllers\PersonController::class, 'index'])->middleware(['auth']);

        Route::get('/people/byclient/{client_id}', [App\Http\Controllers\PersonController::class, 'byClient'])->middleware(['auth']);
        Route::delete('/people/{person}', [App\Http\Controllers\PersonController::class, 'destroy'])->middleware(['auth']);
        Route::put('/people/{person}', [App\Http\Controllers\PersonController::class, 'update'])->middleware(['auth']);
        Route::post('/people', [App\Http\Controllers\PersonController::class, 'store'])->middleware(['auth']);
        // Route::get('/people/{id}', [App\Http\Controllers\PersonController::class, 'read'])->name('read')->middleware(['auth']);
        // Route::get('/people/byclient/{client_id}', [App\Http\Controllers\PersonController::class, 'byClient'])->name('byClient')->middleware(['auth']);
        // Route::delete('/people/{person}', [App\Http\Controllers\PersonController::class, 'destroy'])->name('people.destroy')->middleware(['auth']);
        // Route::put('/people/{person}', [App\Http\Controllers\PersonController::class, 'update'])->name('people.update')->middleware(['auth']);
        // Route::post('/people', [App\Http\Controllers\PersonController::class, 'store'])->name('people.store')->middleware(['auth']);

        Route::get('/setting', function () {
            return view('setting');
        })->middleware(['auth'])->name('setting');

        Route::get('/hoge', function () {
            return view('hoge');
        })->middleware(['auth'])->name('hoge');

    });


    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');


    Route::post('/doc_files/deleteDir', [App\Http\Controllers\DocFileController::class, 'deleteDir'])->middleware(['auth'])->name('deleteDir');

    // Test
    Route::get('/test', [App\Http\Controllers\TestController::class, 'index'])->middleware(['auth'])->name('test');
    Route::get('/test/{ym}', [App\Http\Controllers\TestController::class, 'read'])->middleware(['auth'])->name('test.read');
    Route::get('/test/export', [App\Http\Controllers\TestController::class, 'export'])->middleware(['auth'])->name('test.export');

    // Route::get('/print', [App\Http\Controllers\PrintController::class, 'index'])->middleware(['auth']);
    Route::get('/print', function () {
        return view('print');
    })->middleware(['auth'])->name('print');

    require __DIR__.'/auth.php';

