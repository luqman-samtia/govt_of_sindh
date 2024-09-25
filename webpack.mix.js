const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/js').
//     postCss('resources/css/app.css', 'public/css', [
//         require('postcss-import'),
//         require('tailwindcss'),
//         require('autoprefixer'),
//     ]);

mix.copyDirectory(["node_modules/intl-tel-input/build/img"], "public/img");
mix.copyDirectory(
    ["resources/assets/images", "resources/images"],
    "public/assets/images"
);

mix.copyDirectory("resources/assets/css/landing", "public/assets/css/landing");

//invoice-pdf css
mix.sass(
    "resources/assets/css/invoice-pdf.scss",
    "public/assets/css/invoice-pdf.css"
);

//phone-number dark css
mix.sass(
    "resources/assets/css/phone-number-dark.scss",
    "public/assets/css/phone-number-dark.css"
);

// front third party css
mix.styles(
    ["node_modules/toastr/build/toastr.min.css"],
    "public/assets/css/front-third-party.css"
).version();

mix.copy(
    ["node_modules/bootstrap/dist/css/bootstrap.min.css"],
    "public/assets/css/bootstrap.min.css"
).version();

//front third party js

//front custom js
mix.scripts(
    [
        "node_modules/toastr/build/toastr.min.js",
        "resources/assets/js/contact_enquiry/contact_enquiry.js",
        "resources/assets/js/home-page/home-page.js",
    ],
    "public/assets/js/front-pages.js"
).version();

// light theme css
mix.styles("resources/theme/css/style.css", "public/assets/css/style.css");
mix.styles("resources/theme/css/plugins.css", "public/assets/css/plugins.css");

// dark theme css
mix.styles(
    "resources/theme/css/style.dark.css",
    "public/assets/css/style.dark.css"
);
mix.styles(
    "resources/theme/css/plugins.dark.css",
    "public/assets/css/plugins.dark.css"
);

mix.sass(
    "resources/assets/css/main.scss",
    "public/assets/css/page.css"
).version();

// backend third party css
mix.styles(
    [
        "resources/theme/css/third-party.css",
        "node_modules/intl-tel-input/build/css/intlTelInput.css",
        "node_modules/@simonwep/pickr/dist/themes/nano.min.css",
        "node_modules/daterangepicker/daterangepicker.css",
    ],
    "public/assets/css/third-party.css"
);

// backend invoice Template dark mode css
mix.sass(
    "resources/assets/css/invoice-template-dark.scss",
    "public/assets/css/invoice-template-dark-mode.css"
).version();

// backend third party js
mix.scripts(
    [
        "resources/theme/js/vendor.js",
        "resources/theme/js/plugins.js",
        "node_modules/intl-tel-input/build/js/intlTelInput.js",
        "node_modules/intl-tel-input/build/js/utils.js",
        "node_modules/chart.js/dist/chart.min.js",
        "node_modules/@simonwep/pickr/dist/pickr.min.js",
        "node_modules/daterangepicker/daterangepicker.js",
    ],
    "public/assets/js/third-party.js"
).version();

// backend custom js
mix.js(
    [
        "resources/assets/js/flatpickr_localization.js",
        "resources/assets/js/turbo.js",
        "resources/assets/js/custom/helpers.js",
        "resources/assets/js/custom/custom.js",
        "resources/assets/js/dashboard/dashboard.js",
        "resources/assets/js/users/users.js",
        "resources/assets/js/users/create-edit.js",
        "resources/assets/js/category/category.js",
        "resources/assets/js/custom/phone-number-country-code.js",
        "resources/assets/js/client/client.js",
        "resources/assets/js/client/create-edit.js",
        "resources/assets/js/product/product.js",
        "resources/assets/js/product/create-edit.js",
        "resources/assets/js/invoice/invoice.js",
        "resources/assets/js/invoice/create-edit.js",
        "resources/assets/js/quote/quote.js",
        "resources/assets/js/quote/create-edit.js",
        "resources/assets/js/settings/setting.js",
        "resources/assets/js/tax/tax.js",
        "resources/assets/js/currency/currency.js",
        "resources/assets/js/users/user-profile.js",
        "resources/assets/js/sidebar_menu_search/sidebar_menu_search.js",
        "resources/assets/js/invoice/invoice_payment_history.js",
        "resources/assets/js/client_panel/invoice/invoice.js",
        "resources/assets/js/transaction/transaction.js",
        "resources/assets/js/client_panel/transaction/transaction.js",
        "resources/assets/js/settings/invoice-template.js",
        "resources/assets/js/client/invoice.js",
        "resources/assets/js/invoice/invoice_send.js",
        "resources/assets/js/payment/payment.js",
        "resources/assets/js/payment_qr_code/payment-qr-code.js",
        "resources/assets/js/account/account.js",
        "resources/assets/js/subscription_plans/subscription_plan.js",
        "resources/assets/js/subscription_plans/create-edit.js",
        "resources/assets/js/subscription_plans/plan_features.js",
        "resources/assets/js/subscriptions/admin-free-subscription.js",
        "resources/assets/js/subscriptions/free-subscription.js",
        "resources/assets/js/subscriptions/payment-message.js",
        "resources/assets/js/subscriptions/subscription.js",
        "resources/assets/js/subscriptions/subscriptions-transactions.js",
        "resources/assets/js/faqs/faqs.js",
        "resources/assets/js/contact_enquiry/contact_enquiry.js",
        "resources/assets/js/super_admin_testimonial/testimonial.js",
        "resources/assets/js/languageChange/languageChange.js",
        "resources/assets/js/super_admin_enquiry/super_admin_enquiry.js",
        "resources/assets/js/super_admin_settings/setting.js",
        "resources/assets/js/super_admin_settings/new-user-setting.js",
        "resources/assets/js/super_admin_dashboard/dashboard.js",
        "resources/assets/js/subscribe/subscribe.js",
        "resources/assets/js/jquery.toast.min.js",
        "resources/assets/js/super_admin_currency/currency.js",
        "resources/assets/js/super_admin/super-admin.js",
        "resources/assets/js/client_panel/quotes/quote.js",
        "resources/assets/js/client_panel/quotes/create-edit.js",
    ],
    "public/assets/js/pages.js"
).version();

mix.js(
    "resources/assets/js/auth/auth.js",
    "public/assets/js/auth/auth.js"
).version();

// front-third-party js
mix.scripts(
    ["resources/theme/js/vendor.js", "resources/theme/js/plugins.js"],
    "public/assets/js/auth-third-party.js"
).version();

mix.copyDirectory("node_modules/intl-tel-input/build/img", "public/assets/img");

mix.copyDirectory("resources/theme/webfonts", "public/assets/webfonts");
mix.copyDirectory("resources/theme/css/fonts", "public/assets/css/fonts");
