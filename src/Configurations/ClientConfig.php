<?php

namespace FloodgateSDK\Configurations;

class ClientConfig {
  const API_VERSION = "v1";

  const CDN_BASEURL = "https://cdn.floodgate.io";

  const SDK_VERSION = "1";

  public $SdkKey;

  public $timeout;

  public $logger;

  public $cache;

  /** @var string A custom URL to fetch config data from. */
  public $configUrl = null;

  /** @var string A local file location to fetch config data from. */
  public $configFile = null;

  function __construct ($sdkKey, Array $options) {
    $this->SdkKey = $sdkKey;
    $this->timeout = $options['timeout'];
    $this->logger = $options['logger'];
    $this->cache = $options['cache'];
    $this->configUrl = $options['configUrl'];
    $this->configFile = $options['configFile'];
  }
}