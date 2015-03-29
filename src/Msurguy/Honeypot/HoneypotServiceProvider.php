<?php namespace Msurguy\Honeypot;

use Illuminate\Html\FormBuilder;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class HoneypotServiceProvider extends ServiceProvider {

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
        if ($this->isLaravelVersion('4'))
        {
            $this->package('msurguy/honeypot');
        }
        elseif ($this->isLaravelVersion('5'))
        {
            $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'honeypot');
        }

        $this->app->booted(function($app) {

            // Get validator and translator
            $validator = $app['validator'];
            $translator = $app['translator'];

            // Add honeypot and honeytime custom validation rules
            $validator->extend('honeypot', 'Msurguy\Honeypot\HoneypotValidator@validateHoneypot', $translator->get('honeypot::validation.honeypot'));
            $validator->extend('honeytime', 'Msurguy\Honeypot\HoneypotValidator@validateHoneytime', $translator->get('honeypot::validation.honeytime'));

            // Register the honeypot form macros
            $this->registerFormMacro($this->isLaravelVersion(['4.0', '4.1']) ? $app['form'] : null);
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
    * Register the honeypot form macro
    *
    * @param  Illuminate\Html\FormBuilder|null $form
    * @return void
    */
    public function registerFormMacro(FormBuilder $form = null)
    {
        $honeypotMacro = function($honey_name, $honey_time) {
            $honeypot = new Honeypot();
            return $honeypot->getFormHTML($honey_name, $honey_time);
        };

        // Add a custom honeypot macro to Laravel's form
        if ($form)
        {
            $form->macro('honeypot', $honeypotMacro);
        }
        else
        {
            FormBuilder::macro('honeypot', $honeypotMacro);
        }
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
