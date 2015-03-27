<?php namespace Msurguy\Honeypot;

use Illuminate\Html\FormBuilder;
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

        // Add honeypot and honeytime custom validation rules
        $validator->extend('honeypot', 'Msurguy\Honeypot\HoneypotValidator@validateHoneypot', $translator->get('honeypot::validation.honeypot'));
        $validator->extend('honeytime', 'Msurguy\Honeypot\HoneypotValidator@validateHoneytime', $translator->get('honeypot::validation.honeytime'));

        // Register the honeypot form macros
        $this->registerFormMacro();
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
    * Register the honeypot form macro
    * 
    * @return void
    */
    public function registerFormMacro()
    {
        // Add a custom honeypot macro to Laravel's forms
        FormBuilder::macro('honeypot', function($honey_name, $honey_time) {
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
