<?php

namespace Arkade\Bronto;

use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Publish the config.
        $this->publishes([
            __DIR__ . '/Config/emarsys.php' => config_path('emarsys.php'),
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {

            list($endpoint, $clientId, $clientSecret) = [
                config('services.emarsys.endpoint'),
                config('services.emarsys.client_id'),
                config('services.emarsys.client_secret')
            ];

            if (! $endpoint || ! $clientId || ! $clientSecret) {
                throw new \Exception('You must configure a endpoint, client_id and client_secret and token to use the Emarsys client');
            }

            $client = new Client($endpoint, $clientId, $clientSecret);

            return $client;

        });
    }
}
