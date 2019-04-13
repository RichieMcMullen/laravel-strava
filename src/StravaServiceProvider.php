<?php

# CodeToad
# Richie McMullen
# 2019

namespace CodeToad\Strava;

use GuzzleHttp\Client;

use Illuminate\Support\ServiceProvider;

class StravaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
          __DIR__ . '/config/strava.php', 'strava'
        );

        $this->publishes([
          __DIR__ . '/config/strava.php' => config_path('ct_strava.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Strava', function ($app) {
            $client = new Client();

            return new Strava(
              config('ct_strava.client_id'),
              config('ct_strava.client_secret'),
              config('ct_strava.redirect_uri'),
              $client
            );

        });
    }
}
