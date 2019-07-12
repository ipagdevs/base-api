<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Observers\TransactionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Transaction::observe(TransactionObserver::class);
        \Validator::extend('cpf', '\App\Rules\CpfCnpjValidator@passes');
        \Validator::extend('phone', '\App\Rules\PhoneValidator@passes');
        \Validator::extend('zip_code', '\App\Rules\ZipCodeValidator@passes');
        \Validator::extend('creditcard', '\App\Rules\CreditCardValidator@passes');
    }
}
