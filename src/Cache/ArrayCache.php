<?php

namespace FloodgateSDK\Cache;

final class ArrayCache implements IFloodgateCache {

  private $store = [];

  public function has($key) {
    return array_key_exists($key, $this->store);
  }

  public function get($key) {
    return array_key_exists($key, $this->store) ? $this->store[$key] : null;
  }

  public function set($key, $value) {
    $this->store[$key] = $value;
  }
}