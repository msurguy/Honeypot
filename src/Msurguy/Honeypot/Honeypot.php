<?php namespace Msurguy\Honeypot;

class Honeypot{

  public function getFormHTML($honey_name, $honey_time)
  {
    $honey_time_encrypted = \Crypt::encrypt(time());
    return \View::make("honeypot::fields", array_merge(get_defined_vars(), array('honey_time_encrypted' => $honey_time_encrypted)));
  }    

}
