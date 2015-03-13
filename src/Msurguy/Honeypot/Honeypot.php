<?php namespace Msurguy\Honeypot;

use View;
use Crypt;

class Honeypot {

    /**
     * Function to render the HTML of the hidden honeypot form
     */
    public function getFormHTML($honey_name, $honey_time)
    {
        // Encrypt the current time
        $honey_time_encrypted = Crypt::encrypt(time());

        return View::make("honeypot::fields", array(
            'honey_name'           => $honey_name,
            'honey_time'           => $honey_time,
            'honey_time_encrypted' => $honey_time_encrypted
        ));
    }

}