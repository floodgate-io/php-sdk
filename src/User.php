<?php

namespace FloodgateSDK;

use InvalidArgumentException;

class User {
  const USER_ATTRIBUTE_EMAIL  = 'email';
  const USER_ATTRIBUTE_NAME   = 'name';

  private $id;

  private $email;

  private $name;

  private $attributes;

  function __construct($id, Array $attributes = []) {

    if (empty($id)) {
      throw new InvalidArgumentException("User Id cannot be empty or null");
    }
    
    $this->id = $id;

    if (!empty($attributes)) {
      // Convert keys to lowercase
      $attributes = array_change_key_case($attributes, CASE_LOWER);

      if (!empty($attributes[self::USER_ATTRIBUTE_EMAIL])) {
        $this->email = $attributes[self::USER_ATTRIBUTE_EMAIL];
      }

      if (!empty($attributes[self::USER_ATTRIBUTE_NAME])) {
        $this->name = $attributes[self::USER_ATTRIBUTE_NAME];
      }

      $this->attributes = $attributes;
    }
  }

  public function getId() {
    return $this->id;
  }

  public function getEmail() {
    return $this->email;
  }

  public function getName() {
    return $this->name;
  }

  public function GetAttributeValue($attribute) {

    $attribute = strtolower($attribute);

    if ($attribute == self::USER_ATTRIBUTE_EMAIL) {
      return $this->email;
    }

    if ($attribute == self::USER_ATTRIBUTE_NAME) {
      return $this->name;
    }

    if (!empty($this->attributes[$attribute])) {
      return $this->attributes[$attribute];
    }

    return;
  }
}