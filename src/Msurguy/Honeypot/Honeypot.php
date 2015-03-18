<?php namespace Msurguy\Honeypot;

use Crypt;

class Honeypot {

    /**
     * Get the honey pot form HTML
     * @param  string $honey_name
     * @param  string $honey_time
     * @return string
     */
    public function getFormHTML($honey_name, $honey_time)
    {
        // Encrypt the current time
        $honey_time_encrypted = $this->getEncryptedTime();

        $html = '<div id="' . $honey_name . '_wrap" style="display:none;">\r\n' .
                    '<input name="' . $honey_name . '" type="text" value="" id="' . $honey_name . '"/>\r\n' .
                    '<input name="' . $honey_time . '" type="text" value="' . $honey_time_encrypted . '"/>\r\n' .
                '</div>';

        return $html;
    }

    /**
     * Get encrypted time
     * @return string
     */
    public function getEncryptedTime()
    {
        return Crypt::encrypt(time());
    }

}