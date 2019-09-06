<?php namespace Msurguy\Honeypot;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class HoneypotServiceProvider extends ServiceProvider
{

    /**
    * Indicates if loading of the provider is deferred.
    *
    * @var bool
    */
    protected $defer = false;

    /**
    * Register the service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app->singleton('honeypot', function ($app) {
            return new Honeypot;
        });
    }

    /**
    * Bootstrap the application events.
    *
    * @return void
    */
    public function boot()
    {
        if ($this->isLaravelVersion('4')) {
            $this->package('msurguy/honeypot');
        } elseif ($this->isLaravelVersion('5') || $this->isLaravelVersion('6')) {
            $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'honeypot');
        }

        $this->app->booted(function ($app) {

            // Get validator and translator
            $validator = $app['validator'];
            $translator = $app['translator'];

            // Add honeypot and honeytime custom validation rules
            $validator->extend('honeypot', 'honeypot@validateHoneypot', $translator->get('honeypot::validation.honeypot'));
            $validator->extend('honeytime', 'honeypot@validateHoneytime', $translator->get('honeypot::validation.honeytime'));
        });
    }

    /**
    * Get the services provided by the provider.
    *
    * @return array
    */
    public function provides()
    {
        return array('honeypot');
    }

    /**
     * Determine if laravel starts with any of the given version strings
     *
     * @param  string|array  $startsWith
     * @return boolean
     */
    protected function isLaravelVersion($startsWith)
    {
        return Str::startsWith(Application::VERSION, $startsWith);
    }
}
