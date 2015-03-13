<?php namespace Msurguy\Honeypot;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class HoneypotServiceProvider extends ServiceProvider {

    /**
    * Indicates if loading of the provider is deferred.
    *
    * @var bool
    */
    protected $defer = false;

    /**
     * Laravel major version number
     * 
     * @var integer
     */
    protected $laravelVersion;

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

        $this->app->resolving('form', function($form) {
            $this->createMacroForm($form);
        });        
    }

    /**
    * Bootstrap the application events.
    *
    * @return void
    */
    public function boot()
    {
        if ($this->isLaravelVersion(4))
        {
            $this->package('msurguy/honeypot');
        }
        elseif ($this->isLaravelVersion(5))
        {
            $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'honeypot');
        }

        // Get validator and translator
        $validator = $this->app['validator'];
        $translator = $this->app['translator'];

        // Extend Laravel's validator (rule, function, messages)
        $validator->extend(
            'honeypot',
            'Msurguy\Honeypot\HoneypotValidator@validate',
            $translator->get('honeypot::validation.honeypot')
        );

        // Extend Laravel's validator (rule, function, messages)
        $validator->extend(
            'honeytime',
            'Msurguy\Honeypot\HoneytimeValidator@validate',
            $translator->get('honeypot::validation.honeytime')
        );
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
    * 
    * @param  Illuminate\Html\FormBuilder
    * @return void
    */
    public function createMacroForm($form)
    {
        // Add a custom honeypot macro to Laravel's forms
        $form->macro('honeypot', function($honey_name, $honey_time) {
            $o = new Honeypot();
        
            return $o->getFormHTML($honey_name, $honey_time);
        });
    }

    /**
     * Determine if laravel is the given major version number
     * 
     * @param  integer  $major
     * @return boolean
     */
    protected function isLaravelVersion($major)
    {
        // Cache version number
        if (is_null($this->laravelVersion) && preg_match('#^(\d+)\.#', Application::VERSION, $majorVersion))
        {
            $this->laravelVersion = $majorVersion[1];
        }

        return $this->laravelVersion == $major;
    }
}
