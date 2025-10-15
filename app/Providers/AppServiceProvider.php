<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\HttpClient\HttpClient; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Mail::extend('brevo', function () {
            $factory = new BrevoTransportFactory(null, HttpClient::create()); // <-- Le pasamos el cliente HTTP

            return $factory->create(
                new Dsn(
                    'brevo+api',
                    'default',
                    config('mail.mailers.brevo.key')
                )
            );
        });
    }
}
