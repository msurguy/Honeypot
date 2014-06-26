<?php namespace Msurguy\Honeypot;

use Illuminate\Support\Facades\Crypt;

class HoneytimeValidator {

    /**
     * Extending Laravel Validator (http://laravel.com/docs/validation#custom-validation-rules)
     */
    public function validate($attribute, $value, $parameters, $validator)
    {
        // Laravel will throw an uncaught exception if the value is empty
        // We will try and catch it to make it easier on users.
        try
        {
          $value = Crypt::decrypt($value);   
        }
        catch (\Illuminate\Encryption\DecryptException $exception){
            return false;
        }

        // The current time should be greater than the time the form was built + the speed option
        return ( is_numeric($value) && time() > ($value + $parameters[0]) );
    }

}
