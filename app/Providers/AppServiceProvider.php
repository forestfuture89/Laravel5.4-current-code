<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Add the custom validation rule for checking the full name.
        Validator::extend('full_name', function ($attribute, $value) {

            // This will only accept at least two words
            return preg_match('/^[a-zA-Z.,]+(?: [a-zA-Z,.]+)+$/u', $value);

        });

        // Add the custom validation rule for checking the phone number.
        Validator::extend('phone_number', function($attribute, $value, $parameters) {

            // This will only accept digits with 10 length
            return preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/u', $value);

        });

        /*
         * Add the custom validation rule for checking the website URL.
         * Example: https://www.google.com, http://www.google.com, www.google.com.
         */
        Validator::extend('formatted_url', function($attribute, $value, $parameters) {

            // This will only accept the formatted website url like the above samples
            return preg_match('/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/u', $value);

        });

        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
