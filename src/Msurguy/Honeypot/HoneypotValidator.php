<?php namespace Msurguy\Honeypot;

class HoneypotValidator {

    /**
     * Extending Laravel Validator (http://laravel.com/docs/validation#custom-validation-rules)
     */
    public function validate($attribute, $value, $parameters, $validator)
    {
        return $value == '';
    }

}