<?php

namespace FloodgateSDK\Cache;

use InvalidArgumentException\InvalidArgumentException;

class FloodgateCache {

  const CACHE_TIME_KEY = "floodgate_cache_time";

  private $refreshInterval = 120;

  private $cacheTime = 0;

  private $cache;

  function __construct($cache, Array $options = []) {
    if (empty($cache)) {
      throw new InvalidArgumentException("Please provide a cache engine");
    }

    if (!empty($options['refreshInterval']) && is_numeric($options['refreshInterval'])) {
      $this->refreshInterval = (int) $options['refreshInterval'];
    }

    $this->cache = $cache;
  }

  public function get($key) {
    return $this->cache->get($key);
  }

  public function set($key, $value) {
    if (empty($key)) {
      throw new InvalidArgumentException\InvalidArgumentException("Cache key cannot be empty");
    }
    
    $this->cache->set($key, $value);

    $this->setCacheTime();
  }

  private function setCacheTime() {
    $this->cache->set(self::CACHE_TIME_KEY, time());
  }

  public function isValid() {
    $cacheTime = $this->cache->has(self::CACHE_TIME_KEY) ? $this->cache->get(self::CACHE_TIME_KEY) : 0;
    $valid = (time() > ((int)$cacheTime + (int)$this->refreshInterval)) ? false : true;
    return $valid;
  }
}