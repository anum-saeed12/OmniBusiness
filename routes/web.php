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

Route::get('/', function () {
    return view('home');
})->name('landing');

// Auth routes
Route::get('/login', '\App\Http\Controllers\AuthController@login')->name('login');
Route::post('/login', '\App\Http\Controllers\AuthController@attemptLogin')->name('auth.login');
Route::get('/logout', '\App\Http\Controllers\AuthController@logout')->name('logout');
Route::get('/register', '\App\Http\Controllers\AuthController@register_step_1')->name('register');
Route::post('/register/info', '\App\Http\Controllers\AuthController@register_step_2')->name('register.second');
Route::post('/register', '\App\Http\Controllers\AuthController@newRegistration')->name('auth.register');
Route::get('/forgot-password', '\App\Http\Controllers\AuthController@password')->name('forgot');
Route::get('/reset/request', '\App\Http\Controllers\AuthController@register')->name('reset.request');
Route::post('/reset/password', '\App\Http\Controllers\AuthController@forgotpassword')->name('reset.password');

Route::get('/approval', '\App\Http\Controllers\BillingController@awaitingApproval')->name('approval.wait');
Route::get('/approval/unpaid', '\App\Http\Controllers\BillingController@unpaidClient')->name('approval.unpaid');
Route::get('/approval/accepted', '\App\Http\Controllers\BillingController@approved')->name('approval.accepted');
Route::get('/approval/rejected', '\App\Http\Controllers\BillingController@rejected')->name('approval.rejected');

Route::get('/receipt/submit', '\App\Http\Controllers\BillingController@create')->name('billing.submit.client');
Route::post('/receipt/save', '\App\Http\Controllers\BillingController@store')->name('billing.save.client');

# Route:: Admin
# Route: website.com/admin
# These routes will be used for admin with admin middleware
Route::prefix('/admin')->middleware('admin')->group(function(){
    # Dashboard
    Route::get('/', '\App\Http\Controllers\DashboardController@admin')->name('dashboard.admin');
    # To update or see his/her user
    Route::get('/profile', '\App\Http\Controllers\Admin\ProfileController@show')->name('profile.admin');
    Route::post('/profile/update', '\App\Http\Controllers\Admin\ProfileController@update')->name('profile.update.admin');
    # Route: website.com/admin/user
    # These routes will be use for ADMIN (USER)
    # Admin can add,update,delete,see other admin too
    Route::prefix('/settings')->group(function() {
        Route::get('/', '\App\Http\Controllers\Admin\SettingController@index')->name('setting.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\SettingController@index')->name('setting.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\SettingController@add')->name('setting.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\SettingController@store')->name('setting.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\SettingController@edit')->name('setting.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\SettingController@update')->name('setting.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\SettingController@delete')->name('setting.delete.admin');
    });
    Route::prefix('/user')->group(function() {
        Route::get('/', '\App\Http\Controllers\Admin\UserController@index')->name('user.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\UserController@index')->name('user.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\UserController@add')->name('user.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\UserController@store')->name('user.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\UserController@edit')->name('user.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\UserController@update')->name('user.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\UserController@delete')->name('user.delete.admin');
    });
    # Route: website.com/admin/client
    # These routes will be use for ADMIN (CLIENT)
    # Admin can add,update,delete,see Client/Company/anything else
    Route::prefix('client')->group(function() {
        Route::get('/', '\App\Http\Controllers\Admin\ClientController@index')->name('client.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\ClientController@index')->name('client.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\ClientController@add')->name('client.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\ClientController@store')->name('client.store.admin');
        // TO DO IMPORTANT
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\ClientController@edit')->name('client.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\ClientController@update')->name('client.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\ClientController@delete')->name('client.delete.admin');
       #Route::get('/active/{id}', '\App\Http\Controllers\Admin\ClientController@active')->name('client.active.admin');
        Route::get('/status/{client_id}/{action}', '\App\Http\Controllers\Admin\ClientController@status')->name('client.status.admin');
        Route::post('/status/{client_id}', '\App\Http\Controllers\Admin\ClientController@activate')->name('client.activate.admin');
        Route::post('/disapprove-request/{client_id}', '\App\Http\Controllers\Admin\ClientController@disapprove')->name('client.disapprove.admin');
    });
    #Client department ma add nhi krwaiyaa
    Route::prefix('/department')->group(function(){
        Route::get('/', '\App\Http\Controllers\Admin\DepartmentController@index')->name('department.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\DepartmentController@index')->name('department.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\DepartmentController@add')->name('department.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\DepartmentController@store')->name('department.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\DepartmentController@edit')->name('department.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\DepartmentController@update')->name('department.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\DepartmentController@delete')->name('department.delete.admin');
    });
    Route::prefix('/job')->group(function(){
        Route::get('/', '\App\Http\Controllers\Admin\JobPositionController@index')->name('job.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\JobPositionController@index')->name('job.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\JobPositionController@add')->name('job.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\JobPositionController@store')->name('job.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\JobPositionController@edit')->name('job.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\JobPositionController@update')->name('job.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\JobPositionController@delete')->name('job.delete.admin');
    });
/*    Route::prefix('/products')->group(function(){
        Route::get('/', '\App\Http\Controllers\Admin\ProductController@index');
        Route::get('/index', '\App\Http\Controllers\Admin\ProductController@index');
        Route::post('/add', '\App\Http\Controllers\Admin\ProductController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\ProductController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Admin\ProductController@delete');
    });
    Route::prefix('/products/category')->group(function(){
        Route::get('/', '\App\Http\Controllers\Admin\ProductCategoryController@index');
        Route::get('/index', '\App\Http\Controllers\Admin\ProductCategoryController@index');
        Route::post('/add', '\App\Http\Controllers\Admin\ProductCategoryController@store');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\ProductCategoryController@update');
        Route::post('/delete/{id}', '\App\Http\Controllers\Admin\ProductCategoryController@delete');
    });*/
});
# We are putting this route outside the middleware so it does
# not redirect the client on the same page if the client is
# logging in for the first time
Route::get('/client/hello', '\App\Http\Controllers\Client\ProfileController@hello')->name('first.login.client');
# Route:: Client
Route::prefix('/client')->middleware('client')->group(function(){
    # Dashboard
    Route::get('/', '\App\Http\Controllers\DashboardController@client')->name('dashboard.client');
    Route::get('/profile', '\App\Http\Controllers\Client\ProfileController@show')->name('profile.client');
    Route::get('/subscription', '\App\Http\Controllers\Client\ProfileController@subscription')->name('subscription.client');
    Route::get('/subscription/upload', '\App\Http\Controllers\Client\ProfileController@uploadReceipt')->name('subscription.add.client');
    Route::post('/subscription/save-receipt', '\App\Http\Controllers\Client\ProfileController@saveReceipt')->name('subscription.save.client');
    Route::post('/profile/update', '\App\Http\Controllers\Client\ProfileController@update')->name('profile.update.client');
    Route::get('/profile/edit', '\App\Http\Controllers\Client\ProfileController@edit')->name('profile.edit.client');
    #Route:: website.com/client/user
    Route::prefix('/user')->group(function() {
        Route::get('/', '\App\Http\Controllers\Client\UserController@index')->name('user.index.client');
        Route::get('/index', '\App\Http\Controllers\Client\UserController@index')->name('user.list.client');
        Route::get('/add', '\App\Http\Controllers\Client\UserController@add')->name('user.add.client');
        Route::post('/store', '\App\Http\Controllers\Client\UserController@store')->name('user.store.client');
        Route::get('/edit/{id}', '\App\Http\Controllers\client\AccountantController@edit')->name('user.edit.client');
        Route::post('/update/{id}', '\App\Http\Controllers\Client\UserController@update')->name('user.update.client');
        Route::get('/delete/{id}', '\App\Http\Controllers\Client\UserController@delete')->name('user.delete.client');
    });
    #Route:: website.com/client/manager
    Route::prefix('/manager')->group(function(){
        Route::get('/', '\App\Http\Controllers\Client\ManagerController@index')->name('manager.index.client');
        Route::get('/index', '\App\Http\Controllers\Client\ManagerController@index')->name('manager.list.client');
        Route::get('/add', '\App\Http\Controllers\Client\ManagerController@add')->name('manager.add.client');
        Route::post('/store', '\App\Http\Controllers\Client\ManagerController@store')->name('manager.store.client');
        Route::get('/edit/{id}', '\App\Http\Controllers\Client\ManagerController@edit')->name('manager.edit.client');
        Route::post('/update/{id}', '\App\Http\Controllers\Client\ManagerController@update')->name('manager.update.client');
        Route::get('/delete/{id}', '\App\Http\Controllers\Client\ManagerController@delete')->name('manager.delete.client');
    });
    #Route:: website.com/client/accountant
    Route::prefix('/accountant')->group(function(){
        Route::get('/', '\App\Http\Controllers\Client\AccountantController@index')->name('accountant.index.client');
        Route::get('/index', '\App\Http\Controllers\Client\AccountantController@index')->name('accountant.list.client');
        Route::get('/add', '\App\Http\Controllers\Client\AccountantController@add')->name('accountant.add.client');
        Route::post('/store', '\App\Http\Controllers\Client\AccountantController@store')->name('accountant.store.client');
        Route::get('/edit/{id}', '\App\Http\Controllers\Client\AccountantController@edit')->name('accountant.edit.client');
        Route::post('/update/{id}', '\App\Http\Controllers\Client\AccountantController@update')->name('accountant.update.client');
        Route::get('/delete/{id}', '\App\Http\Controllers\Client\AccountantController@delete')->name('accountant.delete.client');
    });
    #Route:: website.com/client/employee
    Route::prefix('/employee')->group(function(){
        Route::get('/', '\App\Http\Controllers\Client\EmployeeController@index')->name('employee.index.client');
        Route::get('/index', '\App\Http\Controllers\Client\EmployeeController@index')->name('employee.list.client');
        Route::get('/add', '\App\Http\Controllers\Client\EmployeeController@add')->name('employee.add.client');
        Route::post('/store', '\App\Http\Controllers\Client\EmployeeController@store')->name('employee.store.client');
        Route::get('/mail', '\App\Http\Controllers\Client\EmployeeController@sendemail')->name('employee.mail.client');
        Route::get('/edit/{id}', '\App\Http\Controllers\Client\EmployeeController@edit')->name('employee.edit.client');
        Route::post('/update/{id}', '\App\Http\Controllers\Client\EmployeeController@update')->name('employee.update.client');
        Route::get('/delete/{id}', '\App\Http\Controllers\Client\EmployeeController@delete')->name('employee.delete.client');
        Route::get('/{id}', '\App\Http\Controllers\Client\EmployeeController@view')->name('employee.view.client');
    });
    Route::prefix('/department')->group(function(){
        Route::get('/', '\App\Http\Controllers\Client\DepartmentController@index')->name('department.index.client');
        Route::get('/index', '\App\Http\Controllers\Client\DepartmentController@index')->name('department.list.client');
        Route::get('/add', '\App\Http\Controllers\Client\DepartmentController@add')->name('department.add.client');
        Route::post('/store', '\App\Http\Controllers\Client\DepartmentController@store')->name('department.store.client');
        #Route::get('/edit/{id}', '\App\Http\Controllers\Client\DepartmentController@edit')->name('department.edit.client');
        //Route::post('/update/{id}', '\App\Http\Controllers\Client\DeparmentController@update')->name('department.update.client');
        Route::get('/delete/{id}', '\App\Http\Controllers\Client\DepartmentController@delete')->name('department.delete.client');
    });
    Route::prefix('/job')->group(function(){
        Route::get('/', '\App\Http\Controllers\Client\JobPositionController@index')->name('job.index.client');
        Route::get('/index', '\App\Http\Controllers\Client\JobPositionController@index')->name('job.list.client');
        Route::get('/add', '\App\Http\Controllers\Client\JobPositionController@add')->name('job.add.client');
        Route::post('/store', '\App\Http\Controllers\Client\JobPositionController@store')->name('job.store.client');
        Route::get('/edit/{id}', '\App\Http\Controllers\Client\JobPositionController@edit')->name('job.edit.client');
        # 1. Insert main table 2. Bridge table
        #Route::post('/update/{id}', '\App\Http\Controllers\Client\JobPositionController@update')->name('job.update.client');
        Route::get('/delete/{id}', '\App\Http\Controllers\Client\JobPositionController@delete')->name('job.delete.client');
    });
    Route::prefix('/products')->group(function(){
        Route::get('/', '\App\Http\Controllers\Client\ProductController@index')->name('product.index.client');
        Route::get('/index', '\App\Http\Controllers\Client\ProductController@index')->name('product.list.client');
        Route::get('/add', '\App\Http\Controllers\Client\ProductController@add')->name('product.add.client');
        Route::post('/store', '\App\Http\Controllers\Client\ProductController@store')->name('product.store.client');
        Route::get('/edit/{id}', '\App\Http\Controllers\Client\ProductController@edit')->name('product.edit.client');
        Route::post('/update/{id}', '\App\Http\Controllers\Client\ProductController@update')->name('product.update.client');
        Route::get('/delete/{id}', '\App\Http\Controllers\Client\ProductController@delete')->name('product.delete.client');
        Route::get('/{id}', '\App\Http\Controllers\Client\ProductController@view')->name('product.view.client');
        Route::get('/overview/{id}', '\App\Http\Controllers\Client\ProductController@overview')->name('product.overview.client');

        Route::prefix('/category')->group(function(){
            Route::get('/', '\App\Http\Controllers\Client\ProductCategoryController@index')->name('category.index.client');
            Route::get('/index', '\App\Http\Controllers\Client\ProductCategoryController@index')->name('category.list.client');
            Route::get('/add', '\App\Http\Controllers\Client\ProductCategoryController@add')->name('category.add.client');
            Route::post('/store', '\App\Http\Controllers\Client\ProductCategoryController@store')->name('category.store.client');
            Route::get('/edit/{id}', '\App\Http\Controllers\Client\ProductCategoryController@edit')->name('category.edit.client');
            Route::post('/update/{id}', '\App\Http\Controllers\Client\ProductCategoryController@update')->name('category.update.client');
            Route::get('/delete/{id}', '\App\Http\Controllers\Client\ProductCategoryController@delete')->name('category.delete.client');
        });

        Route::post('/import', '\App\Http\Controllers\Common\ImportController@importItem')->name('product.import.client');
        #Route::get('/export', '\App\Http\Controllers\Common\ImportController@exportItem')->name('product.export');
        Route::post('/import/approve', '\App\Http\Controllers\Common\ImportController@approve')->name('product.approve.client');
        Route::get('/import/{id}', '\App\Http\Controllers\Common\ImportController@viewImport')->name('product.review.client');
    });
    Route::prefix('/sales')->group(function(){
        Route::get('/', '\App\Http\Controllers\Client\SaleOrderController@overview')->name('sale.overview.client');
        Route::get('/index', '\App\Http\Controllers\Client\SaleOrderController@index')->name('sale.list.client');
        Route::get('/add', '\App\Http\Controllers\Client\SaleOrderController@add')->name('sale.add.client');
        Route::post('/store', '\App\Http\Controllers\Client\SaleOrderController@store')->name('sale.store.client');
        Route::get('/point', '\App\Http\Controllers\Client\SaleOrderController@point')->name('sale.point.client');
        Route::post('/point/store', '\App\Http\Controllers\Client\SaleOrderController@pointsale')->name('sale.pos.client');
        Route::get('/autocomplete/{search}','\App\Http\Controllers\Client\SaleOrderController@search');
        Route::get('/edit/{id}', '\App\Http\Controllers\Client\SaleOrderController@edit')->name('sale.edit.client');
        Route::post('/update/{id}', '\App\Http\Controllers\Client\SaleOrderController@update')->name('store.update.client');
        Route::get('/delete/{id}', '\App\Http\Controllers\Client\SaleOrderController@delete')->name('sale.delete.client');
        Route::get('/view/{id}', '\App\Http\Controllers\Client\SaleOrderController@view')->name('sale.view.client');
        Route::get('/invoice/{id}', '\App\Http\Controllers\Client\SaleOrderController@invoice')->name('sale.invoice.client');
        Route::get('/invoice/print/{id}', '\App\Http\Controllers\Client\SaleOrderController@printInvoice')->name('sale.invoice.print.client');
        Route::get('/posview/{id}', '\App\Http\Controllers\Client\SaleOrderController@posview')->name('sale.posview.client');
        Route::get('/posinvoice/{id}', '\App\Http\Controllers\Client\SaleOrderController@posinvoice')->name('possale.invoice.client');
        Route::get('/posinvoice/print/{id}', '\App\Http\Controllers\Client\SaleOrderController@printposInvoice')->name('possale.invoice.print.client');
    });
    Route::prefix('/purchase')->group(function(){
        Route::get('/', '\App\Http\Controllers\Client\PurchaseOrderController@overview')->name('purchase.overview.client');
        Route::get('/index', '\App\Http\Controllers\Client\PurchaseOrderController@index')->name('purchase.list.client');
        Route::get('/add', '\App\Http\Controllers\Client\PurchaseOrderController@add')->name('purchase.add.client');
        Route::post('/store', '\App\Http\Controllers\Client\PurchaseOrderController@store')->name('purchase.store.client');
        Route::get('/autocomplete/{search}','\App\Http\Controllers\Client\PurchaseOrderController@search');
        Route::get('/edit/{id}', '\App\Http\Controllers\Client\PurchaseOrderController@edit')->name('purchase.edit.client');
        Route::post('/update/{id}', '\App\Http\Controllers\Client\PurchaseOrderController@update')->name('purchase.update.client');
        Route::get('/delete/{id}', '\App\Http\Controllers\Client\PurchaseOrderController@delete')->name('purchase.delete.client');
        Route::get('/view/{id}', '\App\Http\Controllers\Client\PurchaseOrderController@view')->name('purchase.view.client');
        Route::get('/invoice/{id}', '\App\Http\Controllers\Client\PurchaseOrderController@invoice')->name('purchase.invoice.client');
        Route::get('/invoice/print/{id}', '\App\Http\Controllers\Client\PurchaseOrderController@printInvoice')->name('purchase.invoice.print.client');
    });
    Route::prefix('/invoice')->group(function(){
        Route::get('/', '\App\Http\Controllers\Client\InvoiceController@index')->name('invoice.index.client');
        Route::get('/index', '\App\Http\Controllers\Client\InvoiceController@index')->name('invoice.list.client');
        Route::get('/add', '\App\Http\Controllers\Client\InvoiceController@add')->name('invoice.add.client');
        Route::post('/store', '\App\Http\Controllers\Client\InvoiceController@store')->name('invoice.store.client');
        Route::get('/edit/{id}', '\App\Http\Controllers\Client\InvoiceController@edit')->name('invoice.edit.client');
        Route::post('/update/{id}', '\App\Http\Controllers\Client\InvoiceController@update')->name('invoice.update.client');
        Route::get('/delete/{id}', '\App\Http\Controllers\Client\InvoiceController@delete')->name('invoice.delete.client');
        Route::get('/view/{id}', '\App\Http\Controllers\Client\InvoiceController@view')->name('invoice.view.client');
        Route::get('/invoice/{id}', '\App\Http\Controllers\Client\InvoiceController@invoice')->name('invoice.invoice.client');
        Route::get('/invoice/print/{id}', '\App\Http\Controllers\Client\InvoiceController@printInvoice')->name('invoice.invoice.print.client');
    });
    Route::prefix('/quotation')->group(function(){
        Route::get('/', '\App\Http\Controllers\Client\QuotationController@index')->name('quotation.index.client');
        Route::get('/index', '\App\Http\Controllers\Client\QuotationController@index')->name('quotation.list.client');
        Route::get('/add', '\App\Http\Controllers\Client\QuotationController@add')->name('quotation.add.client');
        Route::post('/store', '\App\Http\Controllers\Client\QuotationController@store')->name('quotation.store.client');
        Route::get('/edit/{id}', '\App\Http\Controllers\Client\QuotationController@edit')->name('quotation.edit.client');
        Route::get('/view/{id}', '\App\Http\Controllers\Client\QuotationController@view')->name('quotation.view.client');
        Route::get('/ajax/{id}', '\App\Http\Controllers\Client\QuotationController@ajax')->name('quotation.ajax.client');
        Route::get('/ajax/accept/{id}', '\App\Http\Controllers\Client\QuotationController@ajaxAccept')->name('quotation.ajax.accept.client');
        Route::post('/update/{id}', '\App\Http\Controllers\Client\QuotationController@update')->name('quotation.update.client');
        Route::get('/delete/{id}', '\App\Http\Controllers\Client\QuotationController@delete')->name('quotation.delete.client');
        Route::post('/reject/{id}', '\App\Http\Controllers\Client\QuotationController@reject_at')->name('quotation.reject.client');
        Route::post('/accept/{id}', '\App\Http\Controllers\Client\QuotationController@accept_at')->name('quotation.accept.client');
    });
    Route::prefix('/vendor')->group(function(){
        Route::get('/', '\App\Http\Controllers\Client\VendorController@index')->name('vendor.index.client');
        Route::get('/index', '\App\Http\Controllers\Client\VendorController@index')->name('vendor.list.client');
        Route::get('/add', '\App\Http\Controllers\Client\VendorController@add')->name('vendor.add.client');
        Route::post('/store', '\App\Http\Controllers\Client\VendorController@store')->name('vendor.store.client');
        Route::get('/edit/{id}', '\App\Http\Controllers\Client\VendorController@edit')->name('vendor.edit.client');
        Route::post('/update/{id}', '\App\Http\Controllers\Client\VendorController@update')->name('vendor.update.client');
        Route::get('/delete/{id}', '\App\Http\Controllers\Client\VendorController@delete')->name('vendor.delete.client');
    });

    Route::prefix('/accounting')->group(function(){
        Route::resource('/charts', '\App\Http\Controllers\Client\AccountingController', ['as' => 'client']);
    });

    Route::resource('petty-cash', '\App\Http\Controllers\Common\PettyCashController', ['as' => 'client']);
    Route::resource('bank', '\App\Http\Controllers\Common\BankController', ['as' => 'client']);

    Route::get('ledger', '\App\Http\Controllers\Common\AccountingController@ledger')->name('ledger.index.client');
    Route::get('balance-sheet', '\App\Http\Controllers\Common\AccountingController@balanceSheet')->name('balanceSheet.index.client');
    Route::get('income-statement', '\App\Http\Controllers\Common\AccountingController@incomeStatement')->name('incomeStatement.index.client');
});

# Route:: Manager
#Route:: website.com/manager
Route::prefix('/manager')->middleware('manager')->group(function(){
    # Dashboard
    Route::get('/', '\App\Http\Controllers\DashboardController@manager')->name('dashboard.manager');
    Route::get('/profile/{id}', '\App\Http\Controllers\Manager\ProfileController@show')->name('profile.manager');
    Route::get('profile/edit/{id}', '\App\Http\Controllers\Manager\ProfileController@edit')->name('profile.edit.manager');
    Route::post('/profile/update/{id}', '\App\Http\Controllers\Manager\ProfileController@update')->name('profile.update.manager');
    #Route:: website.com/manager/accountant
    Route::prefix('/accountant')->group(function(){
        Route::get('/', '\App\Http\Controllers\Manager\AccountantController@index')->name('accountant.index.manager');
        Route::get('/index', '\App\Http\Controllers\Manager\AccountantController@index')->name('accountant.list.manager');
        Route::get('/add', '\App\Http\Controllers\Manager\AccountantController@add')->name('accountant.add.manager');
        Route::post('/store', '\App\Http\Controllers\Manager\AccountantController@store')->name('accountant.store.manager');
        Route::get('/edit/{id}', '\App\Http\Controllers\Manager\AccountantController@edit')->name('accountant.edit.manager');
        Route::post('/update/{id}', '\App\Http\Controllers\Manager\AccountantController@update')->name('accountant.update.manager');
        Route::get('/delete/{id}', '\App\Http\Controllers\Manager\AccountantController@delete')->name('accountant.delete.manager');
    });
    #Route:: website.com/manager/employee
    Route::prefix('/employee')->group(function(){
        Route::get('/', '\App\Http\Controllers\Manager\EmployeeController@index')->name('employee.index.manager');
        Route::get('/index', '\App\Http\Controllers\Manager\EmployeeController@index')->name('employee.list.manager');
        Route::get('/add', '\App\Http\Controllers\Manager\EmployeeController@add')->name('employee.add.manager');
        Route::post('/store', '\App\Http\Controllers\Manager\EmployeeController@store')->name('employee.store.manager');
        Route::get('/edit/{id}', '\App\Http\Controllers\Manager\EmployeeController@edit')->name('employee.edit.manager');
        Route::post('/update/{id}', '\App\Http\Controllers\Manager\EmployeeController@update')->name('employee.update.manager');
        #Route::get('/delete/{id}', '\App\Http\Controllers\Manager\EmployeeController@delete')->name('employee.delete.manager');
        Route::get('/{id}', '\App\Http\Controllers\Manager\EmployeeController@view')->name('employee.view.manager');
    });
    Route::prefix('/products')->group(function(){
        Route::get('/', '\App\Http\Controllers\Manager\ProductController@index')->name('product.index.manager');
        Route::get('/index', '\App\Http\Controllers\Manager\ProductController@index')->name('product.list.manager');
        Route::get('/add', '\App\Http\Controllers\Manager\ProductController@add')->name('product.add.manager');
        Route::post('/store', '\App\Http\Controllers\Manager\ProductController@store')->name('product.store.manager');
        Route::get('/edit/{id}', '\App\Http\Controllers\Manager\ProductController@edit')->name('product.edit.manager');
        Route::post('/update/{id}', '\App\Http\Controllers\Manager\ProductController@update')->name('product.update.manager');
        Route::get('/delete/{id}', '\App\Http\Controllers\Manager\ProductController@delete')->name('product.delete.manager');
        Route::get('/{id}', '\App\Http\Controllers\Manager\ProductController@view')->name('product.view.manager');
        Route::get('/overview/{id}', '\App\Http\Controllers\Manager\ProductController@overview')->name('product.overview.manager');

        Route::post('/import', '\App\Http\Controllers\Common\ImportController@importItem')->name('product.import.manager');
        #Route::get('/export', '\App\Http\Controllers\Common\ImportController@exportItem')->name('product.export');
        Route::post('/import/approve', '\App\Http\Controllers\Common\ImportController@approve')->name('product.approve.manager');
        Route::get('/import/{id}', '\App\Http\Controllers\Common\ImportController@viewImport')->name('product.review.manager');
    });
    Route::prefix('/products/category')->group(function(){
        Route::get('/', '\App\Http\Controllers\Manager\ProductCategoryController@index')->name('category.index.manager');
        Route::get('/index', '\App\Http\Controllers\Manager\ProductCategoryController@index')->name('category.list.manager');
        Route::get('/add', '\App\Http\Controllers\Manager\ProductCategoryController@add')->name('category.add.manager');
        Route::post('/store', '\App\Http\Controllers\Manager\ProductCategoryController@store')->name('category.store.manager');
        Route::get('/edit/{id}', '\App\Http\Controllers\Manager\ProductCategoryController@edit')->name('category.edit.manager');
        Route::post('/update/{id}', '\App\Http\Controllers\Manager\ProductCategoryController@update')->name('category.update.manager');
        Route::get('/delete/{id}', '\App\Http\Controllers\Manager\ProductCategoryController@delete')->name('category.delete.manager');
    });
    Route::prefix('/sales')->group(function(){
        Route::get('/', '\App\Http\Controllers\Manager\SaleOrderController@overview')->name('sale.overview.manager');
        Route::get('/index', '\App\Http\Controllers\Manager\SaleOrderController@index')->name('sale.list.manager');
        Route::get('/add', '\App\Http\Controllers\Manager\SaleOrderController@add')->name('sale.add.manager');
        Route::post('/store', '\App\Http\Controllers\Manager\SaleOrderController@store')->name('sale.store.manager');
        Route::get('/point', '\App\Http\Controllers\Manager\SaleOrderController@point')->name('sale.point.manager');
        Route::post('/point/store', '\App\Http\Controllers\Manager\SaleOrderController@pointsale')->name('sale.pos.manager');
        Route::get('/autocomplete/{search}','\App\Http\Controllers\Manager\SaleOrderController@search');
        Route::get('/edit/{id}', '\App\Http\Controllers\Manager\SaleOrderController@edit')->name('sale.edit.manager');
        Route::post('/update/{id}', '\App\Http\Controllers\Manager\SaleOrderController@update')->name('sale.update.manager');
        Route::get('/delete/{id}', '\App\Http\Controllers\Manager\SaleOrderController@delete')->name('sale.delete.manager');
        Route::get('/view/{id}', '\App\Http\Controllers\Manager\SaleOrderController@view')->name('sale.view.manager');
        Route::get('/invoice/{id}', '\App\Http\Controllers\Manager\SaleOrderController@invoice')->name('sale.invoice.manager');
        Route::get('/invoice/print/{id}', '\App\Http\Controllers\Manager\SaleOrderController@printInvoice')->name('sale.invoice.print.manager');
        Route::get('/posview/{id}', '\App\Http\Controllers\Manager\SaleOrderController@posview')->name('sale.posview.manager');
        Route::get('/posinvoice/{id}', '\App\Http\Controllers\Manager\SaleOrderController@posinvoice')->name('possale.invoice.manager');
        Route::get('/posinvoice/print/{id}', '\App\Http\Controllers\Manager\SaleOrderController@printposInvoice')->name('possale.invoice.print.manager');
    });
    Route::prefix('/purchase')->group(function(){
        Route::get('/', '\App\Http\Controllers\Manager\PurchaseOrderController@overview')->name('purchase.overview.manager');
        Route::get('/index', '\App\Http\Controllers\Manager\PurchaseOrderController@index')->name('purchase.list.manager');
        Route::get('/add', '\App\Http\Controllers\Manager\PurchaseOrderController@add')->name('purchase.add.manager');
        Route::post('/store', '\App\Http\Controllers\Manager\PurchaseOrderController@store')->name('purchase.store.manager');
        Route::get('/autocomplete/{search}','\App\Http\Controllers\Manager\PurchaseOrderController@search');
        Route::get('/edit/{id}', '\App\Http\Controllers\Manager\PurchaseOrderController@edit')->name('purchase.edit.manager');
        Route::post('/update/{id}', '\App\Http\Controllers\Manager\PurchaseOrderController@update')->name('purchase.update.manager');
        Route::get('/delete/{id}', '\App\Http\Controllers\Manager\PurchaseOrderController@delete')->name('purchase.delete.manager');
        Route::get('/view/{id}', '\App\Http\Controllers\Manager\PurchaseOrderController@view')->name('purchase.view.manager');
        Route::get('/invoice/{id}', '\App\Http\Controllers\Manager\PurchaseOrderController@invoice')->name('purchase.invoice.manager');
        Route::get('/invoice/print/{id}', '\App\Http\Controllers\Manager\PurchaseOrderController@printInvoice')->name('purchase.invoice.print.manager');
    });
    Route::prefix('/invoice')->group(function(){
        Route::get('/', '\App\Http\Controllers\Manager\InvoiceController@index')->name('invoice.index.manager');
        Route::get('/index', '\App\Http\Controllers\Manager\InvoiceController@index')->name('invoice.list.manager');
        Route::get('/view/{id}', '\App\Http\Controllers\Manager\InvoiceController@view')->name('invoice.view.manager');
        Route::get('/invoice/{id}', '\App\Http\Controllers\Manager\InvoiceController@invoice')->name('invoice.invoice.manager');
        Route::get('/invoice/print/{id}', '\App\Http\Controllers\Manager\InvoiceController@printInvoice')->name('invoice.invoice.print.manager');
    });
    Route::prefix('/quotation')->group(function(){
        Route::get('/', '\App\Http\Controllers\Manager\QuotationController@index')->name('quotation.index.manager');
        Route::get('/index', '\App\Http\Controllers\Manager\QuotationController@index')->name('quotation.list.manager');
        Route::get('/add', '\App\Http\Controllers\Manager\QuotationController@add')->name('quotation.add.manager');
        Route::post('/store', '\App\Http\Controllers\Manager\QuotationController@store')->name('quotation.store.manager');
        #Route::get('/edit/{id}', '\App\Http\Controllers\Manager\QuotationController@edit')->name('quotation.edit.manager');
        Route::get('/view/{id}', '\App\Http\Controllers\Manager\QuotationController@view')->name('quotation.view.manager');
        Route::get('/ajax/{id}', '\App\Http\Controllers\Manager\QuotationController@ajax')->name('quotation.ajax.manager');
        Route::get('/ajax/accept/{id}', '\App\Http\Controllers\Manager\QuotationController@ajaxAccept')->name('quotation.ajax.accept.manager');
        #Route::post('/update/{id}', '\App\Http\Controllers\Manager\QuotationController@update')->name('quotation.update.manager');
        Route::post('/reject/{id}', '\App\Http\Controllers\Manager\QuotationController@reject_at')->name('quotation.reject.manager');
        Route::post('/accept/{id}', '\App\Http\Controllers\Manager\QuotationController@accept_at')->name('quotation.accept.manager');
    });

    Route::resource('petty-cash', '\App\Http\Controllers\Common\PettyCashController', ['as' => 'manager']);

    Route::get('ledger', '\App\Http\Controllers\Common\AccountingController@ledger')->name('ledger.index.manager');
    Route::get('balance-sheet', '\App\Http\Controllers\Common\AccountingController@balanceSheet')->name('balanceSheet.index.manager');
    Route::get('income-statement', '\App\Http\Controllers\Common\AccountingController@incomeStatement')->name('incomeStatement.index.manager');
});

# Route:: Accountant
# Route:: website.com/accountant
Route::prefix('/accountant')->middleware('accountant')->group(function(){
    # Dashboard
    Route::get('/', '\App\Http\Controllers\DashboardController@accountant')->name('dashboard.accountant');
    Route::get('/profile', '\App\Http\Controllers\Accountant\ProfileController@show')->name('profile.accountant');
    Route::post('/profile/update', '\App\Http\Controllers\Accountant\ProfileController@update')->name('profile.update.accountant');

    Route::prefix('/sales')->group(function(){
        Route::get('/', '\App\Http\Controllers\Accountant\SaleOrderController@index')->name('sale.index.accountant');
        Route::get('/index', '\App\Http\Controllers\Accountant\SaleOrderController@index')->name('sale.list.accountant');
        Route::get('/delete/{id}', '\App\Http\Controllers\Accountant\SaleOrderController@delete')->name('sale.delete.accountant');
        Route::get('/view/{id}', '\App\Http\Controllers\Accountant\SaleOrderController@view')->name('sale.view.accountant');
        Route::get('/invoice/{id}', '\App\Http\Controllers\Accountant\SaleOrderController@invoice')->name('sale.invoice.accountant');
        Route::get('/invoice/print/{id}', '\App\Http\Controllers\Accountant\SaleOrderController@printInvoice')->name('sale.invoice.print.accountant');
        Route::get('/posview/{id}', '\App\Http\Controllers\Accountant\SaleOrderController@posview')->name('sale.posview.accountant');
        Route::get('/posinvoice/{id}', '\App\Http\Controllers\Accountant\SaleOrderController@posinvoice')->name('possale.invoice.accountant');
        Route::get('/posinvoice/print/{id}', '\App\Http\Controllers\Accountant\SaleOrderController@printposInvoice')->name('possale.invoice.print.accountant');
    });

    Route::prefix('/purchase')->group(function(){
        Route::get('/', '\App\Http\Controllers\Accountant\PurchaseOrderController@index')->name('purchase.index.accountant');
        Route::get('/index', '\App\Http\Controllers\Accountant\PurchaseOrderController@index')->name('purchase.list.accountant');
        Route::get('/delete/{id}', '\App\Http\Controllers\Accountant\PurchaseOrderController@delete')->name('purchase.delete.accountant');
        Route::get('/view/{id}', '\App\Http\Controllers\Accountant\PurchaseOrderController@view')->name('purchase.view.accountant');
        Route::get('/invoice/{id}', '\App\Http\Controllers\Accountant\PurchaseOrderController@invoice')->name('purchase.invoice.accountant');
        Route::get('/invoice/print/{id}', '\App\Http\Controllers\Accountant\PurchaseOrderController@printInvoice')->name('purchase.invoice.print.accountant');

    });

    Route::prefix('/invoice')->group(function(){
        Route::get('/', '\App\Http\Controllers\Accountant\InvoiceController@index')->name('invoice.index.accountant');
        Route::get('/index', '\App\Http\Controllers\Accountant\InvoiceController@index')->name('invoice.list.accountant');
        Route::get('/delete/{id}', '\App\Http\Controllers\Accountant\InvoiceController@delete')->name('invoice.delete.accountant');
        Route::get('/view/{id}', '\App\Http\Controllers\Accountant\InvoiceController@view')->name('invoice.view.accountant');
        Route::get('/invoice/{id}', '\App\Http\Controllers\Accountant\InvoiceController@invoice')->name('invoice.invoice.accountant');
        Route::get('/invoice/print/{id}', '\App\Http\Controllers\Accountant\InvoiceController@printInvoice')->name('invoice.invoice.print.accountant');

    });

    Route::prefix('/quotation')->group(function(){
        Route::get('/', '\App\Http\Controllers\Accountant\QuotationController@index')->name('quotation.index.accountant');
        Route::get('/index', '\App\Http\Controllers\Accountant\QuotationController@index')->name('quotation.list.accountant');
        Route::get('/view/{id}', '\App\Http\Controllers\Accountant\QuotationController@view')->name('quotation.view.accountant');
        Route::post('/delete/{id}', '\App\Http\Controllers\Accountant\QuotationController@delete')->name('quotation.delete.accountant');

    });

    Route::prefix('/accounting')->group(function(){
        Route::resource('/charts', '\App\Http\Controllers\Client\AccountingController', ['as' => 'accountant']);
    });
    Route::resource('/petty-cash', '\App\Http\Controllers\Common\PettyCashController', ['as' => 'accountant']);
    Route::resource('/bank', '\App\Http\Controllers\Common\BankController', ['as' => 'accountant']);

    Route::get('ledger', '\App\Http\Controllers\Common\AccountingController@ledger')->name('ledger.index.accountant');
    Route::get('balance-sheet', '\App\Http\Controllers\Common\AccountingController@balanceSheet')->name('balanceSheet.index.accountant');
    Route::get('income-statement', '\App\Http\Controllers\Common\AccountingController@incomeStatement')->name('incomeStatement.index.accountant');
});

# Route:: Employee
# Route:: website/employee
Route::prefix('/employee')->middleware('employee')->group(function(){
    # Dashboard
    Route::get('/', '\App\Http\Controllers\DashboardController@employee')->name('dashboard.employee');
    Route::get('/profile/{id}', '\App\Http\Controllers\Employee\ProfileController@show')->name('profile.employee');
    Route::get('profile/edit/{id}', '\App\Http\Controllers\Employee\ProfileController@edit')->name('profile.edit.employee');
    Route::post('/profile/update/{id}', '\App\Http\Controllers\Employee\ProfileController@update')->name('profile.update.employee');

    /*Route::prefix('/products')->group(function(){
        Route::get('/', '\App\Http\Controllers\Employee\ProductController@index')->name('product.index.employee');
        Route::get('/index', '\App\Http\Controllers\Employee\ProductController@index')->name('product.list.employee');
        Route::get('/add', '\App\Http\Controllers\Employee\ProductController@add')->name('product.add.employee');
        Route::post('/store', '\App\Http\Controllers\Employee\ProductController@store')->name('product.store.employee');
        Route::get('/{id}', '\App\Http\Controllers\Employee\ProductController@view')->name('product.view.employee');
    });*/
    Route::prefix('/sales')->group(function(){
        Route::get('/index', '\App\Http\Controllers\Employee\SaleOrderController@index')->name('sale.list.employee');
        Route::get('/add', '\App\Http\Controllers\Employee\SaleOrderController@add')->name('sale.add.employee');
        Route::post('/store', '\App\Http\Controllers\Employee\SaleOrderController@store')->name('sale.store.employee');
        Route::get('/point', '\App\Http\Controllers\Employee\SaleOrderController@point')->name('sale.point.employee');
        Route::post('/point/store', '\App\Http\Controllers\Employee\SaleOrderController@pointsale')->name('sale.pos.employee');
        Route::get('/autocomplete/{search}','\App\Http\Controllers\Employee\SaleOrderController@search');
        Route::get('/view/{id}', '\App\Http\Controllers\Employee\SaleOrderController@view')->name('sale.view.employee');
        Route::get('/invoice/{id}', '\App\Http\Controllers\Employee\SaleOrderController@invoice')->name('sale.invoice.employee');
        Route::get('/invoice/print/{id}', '\App\Http\Controllers\Employee\SaleOrderController@printInvoice')->name('sale.invoice.print.employee');
        Route::get('/posview/{id}', '\App\Http\Controllers\Employee\SaleOrderController@posview')->name('sale.posview.employee');
        Route::get('/posinvoice/{id}', '\App\Http\Controllers\Employee\SaleOrderController@posinvoice')->name('possale.invoice.employee');
        Route::get('/posinvoice/print/{id}', '\App\Http\Controllers\Employee\SaleOrderController@printposInvoice')->name('possale.invoice.print.employee');
    });
    Route::prefix('/purchase')->group(function(){
        Route::get('/index', '\App\Http\Controllers\Employee\PurchaseOrderController@index')->name('purchase.list.employee');
        Route::get('/add', '\App\Http\Controllers\Employee\PurchaseOrderController@add')->name('purchase.add.employee');
        Route::post('/store', '\App\Http\Controllers\Employee\PurchaseOrderController@store')->name('purchase.store.employee');
        Route::get('/autocomplete/{search}','\App\Http\Controllers\Employee\PurchaseOrderController@search');
        Route::get('/view/{id}', '\App\Http\Controllers\Employee\PurchaseOrderController@view')->name('purchase.view.employee');
        Route::get('/invoice/{id}', '\App\Http\Controllers\Employee\PurchaseOrderController@invoice')->name('purchase.invoice.employee');
        Route::get('/invoice/print/{id}', '\App\Http\Controllers\Employee\PurchaseOrderController@printInvoice')->name('purchase.invoice.print.employee');
    });
    Route::prefix('/invoice')->group(function(){
        Route::get('/', '\App\Http\Controllers\Employee\InvoiceController@index')->name('invoice.index.employee');
        Route::get('/index', '\App\Http\Controllers\Employee\InvoiceController@index')->name('invoice.list.employee');
        Route::get('/view/{id}', '\App\Http\Controllers\Employee\InvoiceController@view')->name('invoice.view.employee');
        Route::get('/invoice/{id}', '\App\Http\Controllers\Employee\InvoiceController@invoice')->name('invoice.invoice.employee');
        Route::get('/invoice/print/{id}', '\App\Http\Controllers\Employee\InvoiceController@printInvoice')->name('invoice.invoice.print.employee');
    });
    Route::prefix('/quotation')->group(function(){
        Route::get('/', '\App\Http\Controllers\Employee\QuotationController@index')->name('quotation.index.employee');
        Route::get('/index', '\App\Http\Controllers\Employee\QuotationController@index')->name('quotation.list.employee');
        Route::get('/add', '\App\Http\Controllers\Employee\QuotationController@add')->name('quotation.add.employee');
        Route::post('/store', '\App\Http\Controllers\Employee\QuotationController@store')->name('quotation.store.employee');
        Route::get('/view/{id}', '\App\Http\Controllers\Employee\QuotationController@view')->name('quotation.view.employee');
        Route::get('/ajax/{id}', '\App\Http\Controllers\Employee\QuotationController@ajax')->name('quotation.ajax.employee');
        Route::get('/ajax/accept/{id}', '\App\Http\Controllers\Employee\QuotationController@ajaxAccept')->name('quotation.ajax.accept.employee');
    });
});
