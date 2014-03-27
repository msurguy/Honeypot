<?php namespace Msurguy\Honeypot;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Crypt;

class Honeypot{

    /**
     * Function to render the HTML of the hidden honeypot form
     */
    public function getFormHTML($honey_name, $honey_time)
    {
        // Encrypt the current time
        $honey_time_encrypted = Crypt::encrypt(time());

        return View::make("honeypot::fields", array_merge(get_defined_vars(), array('honey_time_encrypted' => $honey_time_encrypted)));
    }

}
