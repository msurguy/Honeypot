<?php namespace Msurguy\Honeypot;

use Illuminate\Support\ServiceProvider;

class HoneypotServiceProvider extends ServiceProvider {

    /**
    * Indicates if loading of the provider is deferred.
    *
    * @var bool
    */
    protected $defer = false;

    /**
    * Bootstrap the application events.
    *
    * @return void
    */
    public function boot()
    {
        $this->package('msurguy/honeypot');

        $this->createMacroForm();

        // Extend Laravel's validator (rule, function, messages)
        $this->app['validator']->extend(
            'honeypot', 
            'Msurguy\Honeypot\HoneypotValidator@validate', 
            $this->app['translator']->get('honeypot::validation.honeypot'
        ));

        // Extend Laravel's validator (rule, function, messages)
        $this->app['validator']->extend(
            'honeytime', 
            'Msurguy\Honeypot\HoneytimeValidator@validate', 
            $this->app['translator']->get('honeypot::validation.honeytime')
        );
    }

    /**
    * Register the service provider.
    *
    * @return void
    */
    public function register()
    {
        $this->app['honeypot'] = $this->app->share(function($app)
        {
            return new Honeypot;
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
    * Creates a custom Form macro
    * @return void
    */
    public function createMacroForm()
    {
        // Add a custom honeypot macro to Laravel's forms
        app('form')->macro('honeypot', function($honey_name, $honey_time)
        {
            $o = new Honeypot();
            
            return $o->getFormHTML($honey_name, $honey_time);
        });
    }
}
