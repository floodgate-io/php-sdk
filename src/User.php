<?php

namespace FloodgateSDK;

class User {
  private $id;

  private $attributes;

  function __construct($id, Array $attributes = []) {

    if (empty($id)) {
      throw new Exception("User Id cannot be empty or null");
    }

    $this->id = $id;

    $this->attributes = $attributes;
  }

  public function getId() {
    return $this->id;
  }

  public function GetAttributeValue($attribute) {
    if (!empty($this->attributes[$attribute])) {
      return $this->attributes[$attribute];
    }

    return;
  }
}