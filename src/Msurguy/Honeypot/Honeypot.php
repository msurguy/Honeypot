<?php namespace Msurguy\Honeypot;

use Crypt;

class Honeypot {

    protected $disabled = false;

    /**
     * Enable the Honeypot validation
     */
    public function enable()
    {
        $this->disabled = false;
    }

    /**
     * Disable the Honeypot validation
     */
    public function disable()
    {
        $this->disabled = true;
    }

    /**
     * Generate a new honeypot and return the form HTML
     * @param  string $honey_name
     * @param  string $honey_time
     * @return string
     */
    public function generate($honey_name, $honey_time)
    {
        // Encrypt the current time
        $honey_time_encrypted = $this->getEncryptedTime();

        $html = '<div class="' . $honey_name . '_wrap" style="display:none;"><input name="' . $honey_name . '" type="text" value="" id="' . $honey_name . '"/><input name="' . $honey_time . '" type="text" value="' . $honey_time_encrypted . '"/></div>';

        return $html;
    }

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
        if ($this->disabled) {
            return true;
        }

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
        if ($this->disabled) {
            return true;
        }
        
        // Get the decrypted time
        $value = $this->decryptTime($value);

        // The current time should be greater than the time the form was built + the speed option
        return ( is_numeric($value) && time() > ($value + $parameters[0]) );
    }

    /**
     * Get encrypted time
     * @return string
     */
    public function getEncryptedTime()
    {
        return Crypt::encrypt(time());
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
    	catch (\Exception $exception)
        {
            return null;
        }
    }

}
