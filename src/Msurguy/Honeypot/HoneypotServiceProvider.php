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
        if ($this->isLaravelMinimumVersion(5)) {
            $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'honeypot');
        } else {
            $this->package('msurguy/honeypot');
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
     * Determine if Laravel version is at least the given version.
     *
     * @param  string|array  $minimumVersion
     * @return boolean
     */
    protected function isLaravelMinimumVersion($minimumVersion)
    {
        return (float)Application::VERSION >= $minimumVersion;
    }
}
