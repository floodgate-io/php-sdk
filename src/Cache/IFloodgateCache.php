<?php

namespace FloodgateSDK\Cache;

interface IFloodgateCache {

  public function get($key);

  public function set($key, $value);

  public function has($key);
}