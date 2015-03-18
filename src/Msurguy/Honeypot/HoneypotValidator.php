<?php namespace Msurguy\Honeypot;

use Crypt;

class HoneypotValidator {

    /**
    * Validate honeypot is empty
    * 
    * @param  string $attribute
    * @param  mixed $value
    * @param  array $parameters
    * @return boolean
    */
    public function validateHoneypot($attribute, $value, $parameters)
    {
        return $value == '';
    }

    /**
     * Validate honey time was within the time limit
     * 
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return boolean
     */
    public function validateHoneytime($attribute, $value, $parameters)
    {
        // Get the decrypted time
        $value = $this->decryptTime($value);

        // The current time should be greater than the time the form was built + the speed option
        return ( is_numeric($value) && time() > ($value + $parameters[0]) );
    }

    /**
     * Decrypt the given time
     * 
     * @param  mixed $time
     * @return string|null
     */
    public function decryptTime($time)
    {
        // Laravel will throw an uncaught exception if the value is empty
        // We will try and catch it to make it easier on users.
    	try {
            return Crypt::decrypt($time);
    	}
    	catch (\Illuminate\Encryption\DecryptException $exception)
        {
            return null;
        }
    }

}