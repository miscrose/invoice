<?php

use App\Http\Controllers\admin_contr;
use App\Http\Controllers\devis_contr;
use App\Http\Controllers\home_contr;
use App\Http\Controllers\invoice;
use App\Http\Controllers\invoice_contr;
use App\Http\Controllers\login_contr;
use App\Http\Controllers\profile_contr;
use App\Http\Controllers\sign_up_contr;
use Illuminate\Support\Facades\Route;
/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/sign_up',[sign_up_contr::class,'sign_up'])->name('sign_up');
Route::post('/sign_up',[sign_up_contr::class,'do_sign_up']);

Route::get('/login',[login_contr::class,'login'])->name('login');
Route::post('/login',[login_contr::class,'do_login']);
Route::post('/logout',[login_contr::class,'do_logout'])->name('logout');

Route::get('/home',[home_contr::class,'home'])->middleware('auth')->name('home');



Route::get('/setting',[profile_contr::class,'setting'] )->middleware('auth')->name('setting');

Route::post('/setting.update',[profile_contr::class,'update'] )->name('setting.update');

//Route::match(['get', 'post'],'/admin_list_acc',[admin_contr::class,'admin_list_acc'])->name('admin_list_acc');
Route::get('/admin_list_acc',[admin_contr::class,'admin_list_acc'])->name('admin_list_acc');
Route::post('/admin_list_acc/search',[admin_contr::class,'search_users_email'])->name('search_users_email');

Route::post('/account/update/{user}',[admin_contr::class,'account_update'])->name('account_update');
Route::post('/account/do_update/{user}',[admin_contr::class,'do_update'])->name('do_update');

Route::post('/delete_account/{user}',[admin_contr::class,'delete_account'])->name('delete_account');

//Route::match(['get', 'post'],'/validation_list',[admin_contr::class,'validation_list'])->name('validation_list');
Route::get('/validation_list',[admin_contr::class,'validation_list'])->name('validation_list');
Route::post('/validation/{user}',[admin_contr::class,'validation_acc'])->name('validation_acc');
Route::post('/validation_change/{user}',[admin_contr::class,'validation_change'])->name('validation_change');

Route::match(['get', 'post'],'/information',[profile_contr::class,'information'])->name('information');
Route::post('/add_update_information',[profile_contr::class,'add_update_information'])->name('add_update_information');

Route::match(['get', 'post'],'/client_information/{id}',[admin_contr::class,'client_information'])->middleware('admin')->name('admin_client');
Route::post('/add_update_client/{id}',[admin_contr::class,'add_update_client'])->name('add_update_client');

Route::match(['get', 'post'],'/invoice', [invoice_contr::class, 'invoice_form'])->middleware(['auth', 'admin'])->name('invoice_form');
Route::post('/save_invoice_admin', [invoice_contr::class, 'save_invoice_admin'])->name('save_invoice_admin');

Route::match(['get', 'post'],'/company_info', [admin_contr::class, 'company_info'])->name('company_info');
Route::post('/company_info_save', [admin_contr::class, 'company_info_save'])->name('company_info_save');

Route::match(['get', 'post'],'/invoice_client', [invoice_contr::class, 'invoice_form_client'])->name('invoice_form_client');
Route::post('/save_invoice_client', [invoice_contr::class, 'saveInvoiceClient'])->name('save_invoice_client');

Route::post('/detail_invoice/{type}/{id}',[invoice_contr::class,'detail_invoice'])->name('detail_invoice');


Route::get('/sort_invoice',[home_contr::class,'sort_invoice'])->middleware('auth')->name('sort_invoice');


Route::get('/list_client_invoice/{id}',[admin_contr::class,'list_client_invoice'])->middleware('admin')->name('list_client_invoice');
Route::get('/sort_client_invoice',[admin_contr::class,'sort_client_invoice'])->middleware('admin')->name('sort_client_invoice');


Route::match(['get', 'post'],'/devis', [devis_contr::class, 'devis_form'])->middleware('admin')->name('devis_form');
Route::post('/save_devis_admin', [devis_contr::class, 'save_devis_admin'])->middleware('admin')->name('save_devis_admin');

Route::match(['get', 'post'],'/devis_client', [devis_contr::class, 'devis_form_client'])->name('devis_form_client');
Route::post('/save_devis_client', [devis_contr::class, 'save_devis_client'])->name('save_devis_client');



Route::get('/list_client_devis/{id}',[admin_contr::class,'list_client_devis'])->middleware('admin')->name('list_client_devis');
Route::get('/sort_client_devis',[admin_contr::class,'sort_client_devis'])->middleware('admin')->name('/sort_client_devis');

Route::post('/detail_devis/{type}/{id}',[devis_contr::class,'detail_devis'])->name('detail_devis');

Route::post('/search_client_name',[admin_contr::class,'search_client_name'])->name('search_client_name');

Route::get('/quote_list',[devis_contr::class,'quote_list'])->name('quote_list');
Route::get('/sort_devis',[home_contr::class,'sort_devis'])->name('sort_devis');



Route::post('/add_client_ajax', [admin_contr::class, 'addClientAjax'])->name('add_client_ajax');

Route::post('/link_user_client/{id}', [admin_contr::class, 'link_user_client'])->name('link_user_client');

Route::get('/dashboard',[home_contr::class,'dashboard'])->name('dashboard');


Route::post('/paye_change_admin', [invoice_contr::class, 'paye_change_admin'])->name('paye_change_admin');
Route::post('/paye_change_client', [invoice_contr::class, 'paye_change_client'])->name('paye_change_client');