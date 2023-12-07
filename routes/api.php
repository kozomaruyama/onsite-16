<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// システム
// Route::get('/sys_items', 'App\Http\Controllers\SysItemController@index');
Route::get('/sys_items/taxratio', 'App\Http\Controllers\SysItemController@taxratio');

//
Route::get('/doc_files/filter/{params}', 'App\Http\Controllers\DocFileController@filter');
Route::post('/doc_files', 'App\Http\Controllers\DocFileController@fileupload');
Route::delete('/doc_files/{params}', 'App\Http\Controllers\DocFileController@destroy');

// 会社
// Route::get('/company', 'App\Http\Controllers\CompanyController@index');
// Route::get('/company/{id}', 'App\Http\Controllers\CompanyController@read');
// Route::put('/company/{company}', 'App\Http\Controllers\CompanyController@update');

// 商品
// Route::get('/products', 'App\Http\Controllers\ProductController@index');
// Route::get('/products/{id}', 'App\Http\Controllers\ProductController@read');
// Route::put('/products/{product}', 'App\Http\Controllers\ProductController@update');
// Route::post('/products', 'App\Http\Controllers\ProductController@store');

// 社員
// Route::get('/people', 'App\Http\Controllers\PersonController@index');
// Route::get('/people/{id}', 'App\Http\Controllers\PersonController@read');
// Route::get('/people/byclient/{client_id}', 'App\Http\Controllers\PersonController@byClient');
// Route::put('/people/{person}', 'App\Http\Controllers\PersonController@update');
// Route::post('/people', 'App\Http\Controllers\PersonController@store');
// Route::delete('/people/{person}', 'App\Http\Controllers\PersonController@destroy');

// クライアント
// Route::get('/clients', 'App\Http\Controllers\ClientController@index');
Route::get('/clients/prime', 'App\Http\Controllers\ClientController@prime');
Route::get('/clients/sub', 'App\Http\Controllers\ClientController@subcontracts');
// Route::get('/clients/{id}', 'App\Http\Controllers\ClientController@read')->where('id', '[0-9]+');
Route::get('/clients/invoice', 'App\Http\Controllers\ClientController@invoice');
// Route::put('/clients/{client}', 'App\Http\Controllers\ClientController@update');
// Route::post('/clients', 'App\Http\Controllers\ClientController@store');
// Route::delete('/clients/{client}', 'App\Http\Controllers\ClientController@destroy');

// 案件
Route::post('/subjects', 'App\Http\Controllers\SubjectController@store');
Route::post('/subjects/filter', 'App\Http\Controllers\SubjectController@filter');
Route::delete('/subjects/{subject}', 'App\Http\Controllers\SubjectController@destroy');

// タスク
Route::get('/tasks/{subjectId}', 'App\Http\Controllers\TaskController@subject');
Route::post('/tasks', 'App\Http\Controllers\TaskControlle@store');
Route::put('/tasks/{task}', 'App\Http\Controllers\TaskController@update');

// 請求情報
Route::get('/invoices', 'App\Http\Controllers\InvoiceController@index');
// Route::get('/invoices/{id}', 'App\Http\Controllers\InvoiceController@read')->where('id', '[0-9]+');
// Route::put('/invoices/{invoice}', 'App\Http\Controllers\InvoiceController@update');
// Route::post('/invoices', 'App\Http\Controllers\InvoiceController@group');
Route::post('/invoices/extract', 'App\Http\Controllers\InvoiceController@extract');
Route::post('/invoices/sql', 'App\Http\Controllers\InvoiceController@sql');
// Route::post('/invoices/close', 'App\Http\Controllers\InvoiceController@closing');
Route::post('/invoices/update', 'App\Http\Controllers\InvoiceController@update');
// Route::delete('/invoices/{id}', 'App\Http\Controllers\InvoiceController@destroy');

// 請求マスター
Route::get('/invoice_masters/{id}', 'App\Http\Controllers\InvoiceMasterController@read')->where('id', '[0-9]+');
Route::post('/invoice_masters/reOrder', 'App\Http\Controllers\InvoiceMasterController@reOrder');


//
Route::get('/invoice_details/{id}', 'App\Http\Controllers\InvoiceDetailController@read');


