<?php namespace Msurguy\Honeypot;

//use Illuminate\Validation\Validator;
//use Illuminate\Encryption\Encrypter;

class HoneypotValidator {

  public function validate($attribute, $value, $parameters, $validator)
  {
    return $value == '';
  }

}