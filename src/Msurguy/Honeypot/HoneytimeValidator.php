<?php namespace Msurguy\Honeypot;

use Illuminate\Encryption\Encrypter;

class HoneytimeValidator{

  public function validate($attribute, $value, $parameters, $validator)
  {
    $value = \Crypt::decrypt($value);

    // The current time should be greater than the time the form was built + the speed option
    return ( is_numeric($value) && time() > ($value + $parameters[0]) );
  }

}