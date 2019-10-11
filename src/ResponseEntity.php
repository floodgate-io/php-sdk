<?php

namespace FloodgateSDK;

class ResponseEntity {
  
  const VALID   = 1;
  const INVALID = 2;
  
  public $status;
  public $flags;

  function __construct($status, $flags = null) {
    $this->status = $status;
    $this->flags = $flags;
  }
}