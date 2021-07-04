<?php

use Illuminate\Support\Facades\Log;

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    //

    Route::get('/register_settings', 'SettingsController@register');
    Route::get('/', 'HomeController@index')->name('dashboard');
    Route::get('/dashboard', 'HomeController@index')->name('dashboard');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/search', 'HomeController@search')->name('search');

    Route::group(['prefix' => 'inventories', 'as' => 'inventories.'], function () {

        Route::get('/dashboard', 'InventoryController@dashboard')->name('dashboard');
        Route::get('/all', 'InventoryController@index')->name('index');
        Route::get('/filter', 'InventoryController@filter')->name('filter');
        Route::get('/search', 'InventoryController@search')->name('search');
        Route::get('/params', 'InventoryController@params')->name('params');
        Route::get('/valuation', 'InventoryController@valuation')->name('valuation');
        Route::get('/demand_requests', 'InventoryController@demandRequests')->name('demand_requests');
        Route::get('/ajax_load_demand_requests', 'InventoryController@ajaxLoadDemandRequests')->name('ajax_load_demand_requests');
        Route::get('/ajax_load', 'InventoryController@ajaxLoad')->name('ajaxLoad');
        Route::get('/ajax_load/{id}', 'InventoryController@ajaxLoadDetail')->name('ajaxLoadDetail');
        Route::get('/create', 'InventoryController@create')->name('create');
        Route::get('/edit/{id}', 'InventoryController@edit')->name('edit');
        Route::get('/getimages/{id}', 'InventoryController@getimages')->name('getimages');
        Route::get('/view/{id}', 'InventoryController@view')->name('view');
        Route::get('/cities/{country}', 'InventoryController@getCitiesByCountry')->name('getCitiesByCountry');
        Route::get('/delete/{id}', 'InventoryController@delete')->name('delete');
        Route::get('/draft/{id}', 'InventoryController@draft')->name('draft');
        Route::get('/publish/{id}', 'InventoryController@publish')->name('publish');
        Route::post('/save', 'InventoryController@save')->name('save');
        Route::get('/{id}/photoedit', 'InventoryController@photoedit')->name('photoedit');
        Route::post('/{id}/photosave', 'InventoryController@photosave')->name('photosave');
        Route::post('/uploadphoto', 'InventoryController@uploadphoto')->name('uploadphoto');
        Route::get('/import', 'InventoryController@import')->name('import');
        Route::get('/export', 'InventoryController@export')->name('export');
        Route::post('/import/upload', 'InventoryController@uploadImportFile')->name('uploadImportFile');
        Route::post('/import/start', 'InventoryController@startImport')->name('startImport');
        Route::get('/removephoto', 'InventoryController@removephoto')->name('removephoto');


        Route::get('/draft', 'InventoryController@draftlistings')->name('draft');
        Route::get('/deleted', 'InventoryController@deletedlistings')->name('deleted');
        Route::get('/import', 'InventoryController@import')->name('import');
        Route::get('/logs', 'InventoryController@logs')->name('logs');
        Route::get('/options', 'InventoryController@options')->name('options');
        Route::get('/status', 'InventoryController@status')->name('status');
        Route::get('/status/create', 'InventoryController@statuscreate')->name('status.create');
        Route::post('/status/save', 'InventoryController@statussave')->name('status.save');
        Route::get('/status/edit/{id}', 'InventoryController@editstatus')->name('status.edit');
        Route::get('/status/delete/{id}', 'InventoryController@deletestatus')->name('status.delete');
        Route::get('/tags', 'InventoryController@tags')->name('tags');
        Route::get('/tags/create', 'InventoryController@createtags')->name('tags.create');
        Route::get('/tags/edit/{id}', 'InventoryController@edittags')->name('tags.edit');
        Route::get('/tags/delete/{id}', 'InventoryController@deletetags')->name('tags.delete');
        Route::post('/tags/save', 'InventoryController@savetags')->name('tags.save');

        /* Option Management */
        Route::get('/options', 'InventoryController@options')->name('options');
        Route::get('/options/create', 'InventoryController@createoptions')->name('options.create');
        Route::get('/options/edit/{id}', 'InventoryController@editoptions')->name('options.edit');
        Route::get('/options/delete/{id}', 'InventoryController@deleteoptions')->name('options.delete');
        Route::post('/options/save', 'InventoryController@saveoptions')->name('options.save');

        Route::group(['prefix' => 'documents', 'as' => 'documents.'], function () {
            Route::post('/upload/{id}', 'InventoryController@documentupload')->name('upload');
            Route::get('/load/{id}', 'InventoryController@documentload')->name('load');
        });
    });
    Route::group(['prefix' => 'leads', 'as' => 'leads.'], function () {
        Route::get('/dashboard', 'LeadsController@dashboard')->name('dashboard');
        Route::get('/all', 'LeadsController@index')->name('index');
        Route::get('/filter', 'LeadsController@filter')->name('filter');
        Route::get('/ajax_load', 'LeadsController@ajax_load')->name('ajax_load');
        Route::get('/ajax_load/{id}', 'LeadsController@ajax_load_detail')->name('ajax_load_detail');
        Route::get('/create', 'LeadsController@create')->name('create');
        Route::get('/edit/{id}', 'LeadsController@edit')->name('edit');
        Route::get('/view/{id}', 'LeadsController@view')->name('view');
        Route::get('/deleted', 'LeadsController@deleted')->name('deleted');
        Route::post('/ajax_phone_exist', 'LeadsController@ajax_phone_exist')->name('ajax_phone_exist');
        Route::get('/delete/{id}', 'LeadsController@delete')->name('delete');
        Route::get('/add_email', 'LeadsController@add_email')->name('add_email');
        // Route::get('/import', 'LeadsController@import')->name('import');
        Route::group(['prefix' => 'import', 'as' => 'import.'], function () {
            Route::get('/', 'LeadImportController@index')->name('index');
            Route::post('/upload', 'LeadImportController@uploadImportFile')->name('upload');
        });
        Route::get('/logs', 'LeadsController@logs')->name('logs');
        Route::post('/assign/{id}', 'LeadsController@assign')->name('assign');
        Route::get('/export', 'LeadsController@export')->name('export');
        Route::post('/getbyinterested', 'LeadsController@getbyinterested')->name('getbyinterested');
        Route::post('/save', 'LeadsController@save')->name('save');
        Route::post('/convert', 'LeadsController@convert')->name('convert');
        Route::post('/verify_email', 'LeadsController@verify_email')->name('verify_email');

        Route::group(['prefix' => 'status'], function () {
            Route::get('/', 'LeadStatusController@index')->name('status');
            Route::get('/create', 'LeadStatusController@create')->name('status.create');
            Route::post('/save', 'LeadStatusController@save')->name('status.save');
            Route::get('/edit/{id}', 'LeadStatusController@edit')->name('status.edit');
            Route::get('/delete/{id}', 'LeadStatusController@delete')->name('status.delete');
        });
        Route::group(['prefix' => 'documents', 'as' => 'documents.'], function () {
            Route::post('/upload/{id}', 'LeadDocumentsController@upload')->name('upload');
            Route::post('/convert_upload', 'LeadDocumentsController@convert_upload')->name('convert_upload');
            Route::get('/load/{id}', 'LeadDocumentsController@load')->name('load');
        });
        Route::group(['prefix' => 'interests', 'as' => 'interests.'], function () {
            Route::post('/add/{lead_id}', 'LeadInterestsController@add')->name('add');
            Route::post('/delete', 'LeadInterestsController@delete')->name('delete');
            Route::get('/load/{id}', 'LeadInterestsController@load')->name('load');
            Route::get('/detail/{id}', 'LeadInterestsController@detail')->name('detail');
            Route::get('/load_edit_detail/{id}', 'LeadInterestsController@load_edit_detail')->name('load_edit_detail');
        });
        Route::group(['prefix' => 'notes', 'as' => 'notes.'], function () {
            Route::post('/add/{id}', 'LeadNotesController@add')->name('add');
            Route::get('/load/{id}', 'LeadNotesController@load')->name('load');
        });

        Route::group(['prefix' => 'tags'], function () {
            Route::get('/', 'LeadTagController@index')->name('tags');
            Route::get('/create', 'LeadTagController@create')->name('tags.create');
            Route::get('/edit/{id}', 'LeadTagController@edit')->name('tags.edit');
            Route::get('/delete/{id}', 'LeadTagController@delete')->name('tags.delete');
            Route::post('/save', 'LeadTagController@save')->name('tags.save');
        });
    });
    Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
        Route::get('/', 'CustomersController@dashboard')->name('dashboard');
        Route::get('/all', 'CustomersController@index')->name('index');
        Route::get('/create', 'CustomersController@create')->name('create');
        Route::get('/edit/{id}', 'CustomersController@edit')->name('edit');
        Route::get('/view/{id}', 'CustomersController@view')->name('view');
        Route::get('/delete/{id}', 'CustomersController@delete')->name('delete');
        Route::post('/assign/{id}', 'CustomersController@assign')->name('assign');
        
        Route::group(['prefix' => 'transactions'], function () {
            Route::get('/{id}', 'TransactionController@loadByCustomer');
        });
        Route::post('/save', 'CustomersController@save')->name('save');
        Route::get('/deleted', 'CustomersController@deleted')->name('deleted');
        Route::get('/import', 'CustomersController@import')->name('import');
        Route::get('/export', 'CustomersController@export')->name('export');
        Route::get('/logs', 'CustomersController@logs')->name('logs');
        Route::get('/ajax_load', 'CustomersController@ajaxLoad')->name('ajaxLoad');
        Route::get('/ajax_load/{id}', 'CustomersController@ajaxLoadDetail')->name('ajaxLoadDetail');
        Route::group(['prefix' => 'documents', 'as' => 'documents.'], function () {
            Route::post('/upload/{id}', 'CustomerDocumentsController@upload')->name('upload');
            Route::get('/load/{id}', 'CustomerDocumentsController@load')->name('load');
        });
        Route::group(['prefix' => 'notes', 'as' => 'notes.'], function () {
            Route::post('/add/{id}', 'CustomerNotesController@add')->name('add');
            Route::get('/load/{id}', 'CustomerNotesController@load')->name('load');
        });
        Route::get('/status', 'CustomersController@status')->name('status');
        Route::get('/status/create', 'CustomersController@statuscreate')->name('status.create');
        Route::post('/status/save', 'CustomersController@statussave')->name('status.save');
        Route::get('/status/edit/{id}', 'CustomersController@editstatus')->name('status.edit');
        Route::get('/status/delete/{id}', 'CustomersController@deletestatus')->name('status.delete');
        Route::group(['prefix' => 'tags'], function () {
            Route::get('/', 'CustomerTagController@index')->name('tags');
            Route::get('/create', 'CustomerTagController@create')->name('tags.create');
            Route::get('/edit/{id}', 'CustomerTagController@edit')->name('tags.edit');
            Route::get('/delete/{id}', 'CustomerTagController@delete')->name('tags.delete');
            Route::post('/save', 'CustomerTagController@save')->name('tags.save');
        });
        Route::post('/filter', 'CustomersController@filter')->name('filter');
    });
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', 'UsersController@dashboard')->name('dashboard');
        Route::get('/all', 'UsersController@index')->name('index');
        Route::get('/create', 'UsersController@create')->name('create');
        Route::get('/edit/{id}', 'UsersController@edit')->name('edit');
        Route::get('/ajax_load', 'UsersController@ajax_load')->name('ajax_load');
        Route::get('/ajax_load/{id}', 'UsersController@ajax_load_details')->name('ajax_load_details');
        Route::get('/deleted', 'UsersController@deleted')->name('deleted');
        Route::get('/logs', 'UsersController@logs')->name('logs');
        Route::post('/save', 'UsersController@save')->name('save');

        Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
            Route::get('/', 'UserRolesController@index')->name('index');
            Route::get('/index', 'UserRolesController@index')->name('index');
            Route::get('/create', 'UserRolesController@create')->name('create');
            Route::get('/edit/{id}', 'UserRolesController@edit')->name('edit');
            Route::get('/ajax_load/{id}', 'UserRolesController@ajax_load_detail')->name('ajax_load_detail');
            Route::get('/delete/{id}', 'UserRolesController@delete')->name('delete');
            Route::post('/save', 'UserRolesController@save')->name('save');
        });
    });
    Route::group(['prefix' => 'calendar', 'as' => 'calendar.'], function () {
        Route::get('/', 'CalendarController@dashboard')->name('dashboard');

        Route::group(['prefix' => 'events', 'as' => 'events.'], function () {
            Route::get('/', 'CalendarEventController@index')->name('index');
            Route::post('/save', 'CalendarEventController@save')->name('save');
            Route::post('/delete', 'CalendarEventController@delete')->name('delete');
            Route::get('/load', 'CalendarEventController@load')->name('load');
            Route::get('/load_basic_data', 'CalendarEventController@load_basic_data')->name('load_basic_data');
            Route::get('/detail', 'CalendarEventController@detail')->name('detail');
        });
        Route::group(['prefix' => 'types', 'as' => 'types.'], function () {
            Route::get('/', 'CalendarEventTypeController@index')->name('index');
            Route::get('/create', 'CalendarEventTypeController@create')->name('create');
            Route::get('/edit/{id}', 'CalendarEventTypeController@edit')->name('edit');
            Route::get('/delete/{id}', 'CalendarEventTypeController@delete')->name('delete');
            Route::post('/save', 'CalendarEventTypeController@save')->name('save');
        });
        // Route::get('/types', 'CalendarController@types')->name('types');
        Route::get('/notifications', 'CalendarController@notifications')->name('notifications');
        Route::get('/logs', 'CalendarController@logs')->name('logs');
    });
    Route::group(['prefix' => 'locations', 'as' => 'locations.'], function () {
        Route::get('/', 'LocationController@index')->name('index');
        Route::get('/all', 'LocationController@all')->name('all');

        Route::get('/create', 'LocationController@create')->name('create');
        Route::get('/edit/{id}', 'LocationController@edit')->name('edit');
        Route::get('/delete/{id}', 'LocationController@delete')->name('delete');
        Route::get('/deleted', 'LocationController@deleted')->name('deleted');
        Route::post('/save', 'LocationController@save')->name('save');
        Route::post('/logoUpload', 'LocationController@logoUpload')->name('logoUpload');
        Route::post('/uploadphoto', 'LocationController@uploadphoto')->name('uploadphoto');
        Route::get('/ajax_load', 'LocationController@ajax_load')->name('ajax_load');
        Route::get('/ajax_load/{id}', 'LocationController@ajax_load_details')->name('ajax_load_details');

        Route::group(['prefix' => 'types', 'as' => 'types.'], function () {
            //
            Route::get('/', 'LocationTypeController@index')->name('index');
            Route::get('/create', 'LocationTypeController@create')->name('create');
            Route::get('/edit/{id}', 'LocationTypeController@edit')->name('edit');
            Route::get('/delete/{id}', 'LocationTypeController@delete')->name('delete');
            Route::post('/save', 'LocationTypeController@save')->name('save');
        });


        Route::get('/logs', 'LocationController@logs')->name('logs');
    });
    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('/logs', 'ReportController@logs')->name('logs');
    });
    Route::group(['prefix' => 'documents', 'as' => 'documents.'], function () {
        Route::get('/index', 'DocumentsController@index')->name('index');
        Route::get('/create', 'DocumentsController@create')->name('create');
        Route::post('/upload', 'DocumentsController@upload')->name('upload');
        Route::get('/delete/{id}', 'DocumentsController@delete')->name('delete');
        Route::get('/download/{id}', 'DocumentsController@download')->name('download');
        Route::get('/ajax_load', 'DocumentsController@ajax_load')->name('ajax_load');
        Route::get('/deleted', 'DocumentsController@deleted')->name('deleted');
        //
        Route::group(['prefix' => 'tags'], function () {
            Route::get('/', 'DocumentTagController@index')->name('tags');
            Route::group(['as' => 'tags.'], function () {
                Route::get('/create', 'DocumentTagController@create')->name('create');
                Route::get('/edit/{id}', 'DocumentTagController@edit')->name('edit');
                Route::get('/delete/{id}', 'DocumentTagController@delete')->name('delete');
                Route::post('/save', 'DocumentTagController@save')->name('save');
            });
        });
        Route::get('/logs', 'DocumentsController@logs')->name('logs');
    });
    Route::group(['prefix' => 'tools', 'as' => 'tools.'], function () {
        Route::get('/car_recognition', 'ToolsController@carRecognition')->name('car_recognition');
        Route::get('/money_conversion', 'ToolsController@moneyConversion')->name('money_conversion');
        Route::get('/distance_conversion', 'ToolsController@distanceConversion')->name('distance_conversion');
        Route::get('/vin_identification', 'ToolsController@vinIdentification')->name('vin_identification');
        Route::get('/valuation', 'ToolsController@valuation')->name('valuation');
        Route::get('/verify_phone', 'ToolsController@verify_phone')->name('verify_phone');
        Route::get('/mortgage_calculator', 'ToolsController@mortgage_calculator')->name('mortgage_calculator');
    });
    Route::group(['prefix' => 'transactions', 'as' => 'transactions.'], function () {
        Route::get('/index', 'TransactionController@index')->name('index');
        Route::get('/create', 'TransactionController@create')->name('create');
        Route::get('/edit/{id}', 'TransactionController@edit')->name('edit');
        Route::get('/load_details/{id}', 'TransactionController@loadDetails')->name('load_details');
        Route::get('/basicdata', 'TransactionController@basicdata')->name('basicdata');
        // Route::get('/view/{id}', 'TransactionController@create')->name('create');
        Route::get('/ajax_load', 'TransactionController@ajaxLoad')->name('ajax_load');
        Route::post('/save', 'TransactionController@save')->name('save');
        Route::group(['prefix' => 'documents', 'as' => 'documents.'], function () {
            Route::post('/upload', 'TransactionDocumentController@upload')->name('upload');
        });

        Route::group(['prefix' => 'invoice', 'as' => 'invoice.'], function () {
            Route::get('/create', 'TransactionInvoiceController@create')->name('create');
            Route::get('/load_basic_data', 'TransactionInvoiceController@load_basic_data')->name('load_basic_data');
            Route::post('/get_customer_info', 'TransactionInvoiceController@get_customer_info')->name('get_customer_info');
            Route::post('/send_invoice', 'TransactionInvoiceController@send_invoice')->name('send_invoice');
            Route::post('/get_price_with_currency', 'TransactionInvoiceController@get_price_with_currency')->name('get_price_with_currency');
            
            
        });

        Route::get('/create_quote', 'TransactionInvoiceController@create')->name('create_quote');
        Route::get('/deleted', 'TransactionController@deleted')->name('deleted');
        Route::get('/tags', 'TransactionController@tags')->name('tags');
        Route::get('/logs', 'TransactionController@logs')->name('logs');
    });
    Route::group(['prefix' => 'marketings', 'as' => 'marketings.'], function () {
        Route::get('/', 'MarkettingController@index')->name('index');
        Route::group(['prefix' => 'sms_campaigns'], function () {
            Route::get('/', 'SmsMarketingController@index')->name('sms_campaigns');
            Route::get('/create', 'SmsMarketingController@create')->name('sms_campaigns.create');
            Route::post('/send', 'SmsMarketingController@send')->name('sms_campaigns.send');
        });
        Route::group(['prefix' => 'email_campaigns'], function () {
            Route::get('/', 'EmailMarketingController@index')->name('email_campaigns');
            Route::get('/create', 'EmailMarketingController@create')->name('email_campaigns.create');
            Route::post('/send', 'EmailMarketingController@send')->name('email_campaigns.send');
        });

        Route::get('/email_campaigns', 'MarkettingController@email_campaigns')->name('email_campaigns');
        Route::get('/whatsapp_campaigns', 'MarkettingController@whatsapp_campaigns')->name('whatsapp_campaigns');
        Route::get('/reports', 'MarkettingController@reports')->name('reports');
        Route::get('/logs', 'MarkettingController@logs')->name('logs');
        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::get('/', 'MarkettingController@settings')->name('index');
            Route::post('/facebook/pages', 'MarkettingController@getFBPages')->name('facebook.pages');
            Route::post('/facebook/subscribe', 'MarkettingController@subscribeFBPage')->name('facebook.subscribe');
        });
    });
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/index', 'SettingsController@index')->name('index');
        Route::get('/profile', 'SettingsController@profile')->name('profile');
        Route::post('/save', 'SettingsController@save')->name('save');
    });
    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', 'ProfileController@index')->name('index');
        Route::get('/edit', 'ProfileController@edit')->name('edit');
    });
    Route::group(['prefix' => 'help', 'as' => 'help.'], function () {
        Route::get('/faq', 'SettingsController@faq')->name('faq');
        Route::get('/tutorials', 'SettingsController@tutorials')->name('tutorials');
        Route::get('/support', 'SettingsController@support')->name('support');
    });
});
