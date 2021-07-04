<?php
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin'], function () {
    Route::group(['namespace' => 'Auth', 'as' => 'auth.'], function () {
        Route::get('login', 'LoginController@showAdminLoginForm')->name('showLoginForm');
        Route::post('login', 'LoginController@adminLogin')->name('adminLogin');
        Route::post('logout', 'LoginController@adminLogout')->name('adminLogout');
    });


    Route::group(['middleware' => 'auth:admin'], function () {
        //
        Route::get('/', 'DashboardController@index')->name('home');
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
        Route::group(['prefix' => 'inventories', 'as' => 'inventories.'], function () {
            Route::get('/', 'InventoryController@dashboard')->name('dashboard');
            Route::group(['prefix' => 'valuation', 'as' => 'valuation.'], function () {
                Route::get('/', 'InventoryValuationController@index')->name('index');
                Route::get('/upload', 'InventoryValuationController@upload')->name('upload');
                Route::post('/upload', 'InventoryValuationController@uploadExcel')->name('upload_excel');
                Route::post('/start_import', 'InventoryValuationController@start_import')->name('start_import');
            });
        });
        Route::group(['prefix' => 'leads', 'as' => 'leads.'], function () {
            Route::get('/', 'LeadsController@dashboard')->name('dashboard');
            Route::get('/all', 'LeadsController@index')->name('index');
            Route::get('/create', 'LeadsController@create')->name('create');
            Route::get('/deleted', 'LeadsController@deleted')->name('deleted');
            Route::get('/import', 'LeadsController@import')->name('import');
            Route::get('/logs', 'LeadsController@logs')->name('logs');

            Route::get('/status', 'LeadsController@status')->name('status');
            Route::get('/status/create', 'LeadsController@statuscreate')->name('status.create');
            Route::post('/status/save', 'LeadsController@statussave')->name('status.save');
            Route::get('/status/edit/{id}', 'LeadsController@editstatus')->name('status.edit');
            Route::get('/status/delete/{id}', 'LeadsController@deletestatus')->name('status.delete');
            Route::get('/tags', 'LeadsController@tags')->name('tags');
            Route::get('/tags/create', 'LeadsController@createtags')->name('tags.create');
            Route::get('/tags/edit/{id}', 'LeadsController@edittags')->name('tags.edit');
            Route::get('/tags/delete/{id}', 'LeadsController@deletetags')->name('tags.delete');
            Route::post('/tags/save', 'LeadsController@savetags')->name('tags.save');
        });
        Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
            Route::get('/', 'CustomersController@dashboard')->name('dashboard');
            Route::get('/all', 'CustomersController@index')->name('index');
            Route::get('/create', 'CustomersController@create')->name('create');
            Route::get('/deleted', 'CustomersController@deleted')->name('deleted');
            Route::get('/import', 'CustomersController@import')->name('import');
            Route::get('/logs', 'CustomersController@logs')->name('logs');

            Route::get('/status', 'CustomersController@status')->name('status');
            Route::get('/status/create', 'CustomersController@statuscreate')->name('status.create');
            Route::post('/status/save', 'CustomersController@statussave')->name('status.save');
            Route::get('/status/edit/{id}', 'CustomersController@editstatus')->name('status.edit');
            Route::get('/status/delete/{id}', 'CustomersController@deletestatus')->name('status.delete');
            Route::get('/tags', 'CustomersController@tags')->name('tags');
            Route::get('/tags/create', 'CustomersController@createtags')->name('tags.create');
            Route::get('/tags/edit/{id}', 'CustomersController@edittags')->name('tags.edit');
            Route::get('/tags/delete/{id}', 'CustomersController@deletetags')->name('tags.delete');
            Route::post('/tags/save', 'CustomersController@savetags')->name('tags.save');
        });
        Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
            Route::get('/', 'UsersController@dashboard')->name('dashboard');
            Route::get('/all', 'UsersController@index')->name('index');
            Route::get('/create', 'UsersController@create')->name('create');
            Route::get('/deleted', 'UsersController@deleted')->name('deleted');
            Route::get('/roles', 'UsersController@roles')->name('roles');
            Route::get('/logs', 'UsersController@logs')->name('logs');
        });
        Route::group(['prefix' => 'calendar', 'as' => 'calendar.'], function () {
            Route::get('/', 'CalendarController@dashboard')->name('dashboard');
            Route::get('/events', 'CalendarController@events')->name('events');
            Route::get('/types', 'CalendarController@types')->name('types');
            Route::get('/notifications', 'CalendarController@notifications')->name('notifications');
            Route::get('/logs', 'CalendarController@logs')->name('logs');
        });
        Route::group(['prefix' => 'locations', 'as' => 'locations.'], function () {
            Route::get('/', 'LocationController@index')->name('index');
            Route::get('/create', 'LocationController@create')->name('create');
            Route::get('/deleted', 'LocationController@deleted')->name('deleted');
            Route::get('/types', 'LocationController@types')->name('types');
            Route::get('/logs', 'LocationController@logs')->name('logs');
        });
        Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
            Route::get('/logs', 'ReportController@logs')->name('logs');
        });
        Route::group(['prefix' => 'documents', 'as' => 'documents.'], function () {
            Route::get('/index', 'ReportController@index')->name('index');
            Route::get('/create', 'ReportController@create')->name('create');
            Route::get('/deleted', 'ReportController@deleted')->name('deleted');
            Route::get('/tags', 'ReportController@tags')->name('tags');
            Route::get('/logs', 'ReportController@logs')->name('logs');
        });
        Route::group(['prefix' => 'transactions', 'as' => 'transactions.'], function () {
            Route::get('/index', 'TransactionController@index')->name('index');
            Route::get('/create_invoice', 'TransactionController@create_invoice')->name('create_invoice');
            Route::get('/create_quote', 'TransactionController@create_quote')->name('create_quote');
            Route::get('/deleted', 'TransactionController@deleted')->name('deleted');
            Route::get('/tags', 'TransactionController@tags')->name('tags');
            Route::get('/logs', 'TransactionController@logs')->name('logs');
        });
        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::get('/profile', 'SettingsController@profile')->name('profile');
        });
    });
});
