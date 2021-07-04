const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.react('resources/js/inventory_form.js', 'public/js')
    .react('resources/js/all_inventories.js', 'public/js')
    .react('resources/js/inventory_import_form.js', 'public/js')
    .react('resources/js/location_form.js', 'public/js')
    .react('resources/js/all_leads.js', 'public/js')
    .react('resources/js/lead_import_form.js', 'public/js')
    .react('resources/js/leads_form.js', 'public/js')
    .react('resources/js/transaction_form.js', 'public/js')
    .react('resources/js/customer_transaction_form.js', 'public/js')
    .react('resources/js/invoice_form.js', 'public/js')
    .react('resources/js/customers_form.js', 'public/js')
    .react('resources/js/user_form.js', 'public/js')
    .react('resources/js/user_role_form.js', 'public/js')
    .react('resources/js/add_interests_form.js', 'public/js')
    .react('resources/js/calendar_events.js', 'public/js')
    .react('resources/js/lead_convert_form.js', 'public/js')
    .react('resources/js/valuation.js', 'public/js')
    .react('resources/js/create_sms_campaign.js', 'public/js')
    .react('resources/js/create_email_campaign.js', 'public/js')
    .react('resources/js/admin/valuation_form.js', 'public/js/admin')
    .react('resources/js/car_recog.js', 'public/js/')
    .react('resources/js/distance_conversion.js', 'public/js/')
    .react('resources/js/money_conversion.js', 'public/js/')
    .react('resources/js/verify_phone.js', 'public/js/')
    .react('resources/js/mortgage.js', 'public/js/')
    .sass('resources/sass/app.scss', 'public/css')
    .js('resources/new_assets/js/app.js', 'public/new_assets/js')
    .autoload({
        'jquery': ['$', 'window.jQuery', 'jQuery']
    });
