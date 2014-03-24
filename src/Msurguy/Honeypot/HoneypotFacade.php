<?php namespace Msurguy\Honeypot;
 
use Illuminate\Support\Facades\Facade;
 
class HoneypotFacade extends Facade {
 
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'honeypot'; }
 
}