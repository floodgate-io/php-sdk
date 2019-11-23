<?php

namespace FloodgateSDK;

use FloodgateSDK\Configurations\ClientConfig;
use FloodgateSDK\Evaluator;
use FloodgateSDK\User;
use FloodgateSDK\Cache\FloodgateCache;
use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;

final class FloodgateClient {

  const CACHE_KEY = "floodgate_cache";

  private $config;

  private $responseEntity = null;

  function __construct ($sdkKey, array $options = []) {
    if (empty($sdkKey) || !isset($sdkKey)) {
      throw new InvalidArgumentException('SDK Key cannot be empty');
    }

    if (!empty($options['logger']) && $options['logger'] instanceof LoggerInterface) {
      $configOptions['logger'] = $options['logger'];
    }
    else {
      $configOptions['logger'] = new NullLogger();
    }

    if (!empty($options['cache'])) {
      $configOptions['cache'] = $options['cache'];
    }
    else {
      $configOptions['cache'] = new FloodgateCache(new \FloodgateSDK\Cache\ArrayCache());
    }

    if (!empty($options['timeout'])) {
      $configOptions['timeout'] = $options['timeout'];
    }
    else {
      $configOptions['timeout'] = 10; // Set the default http request timeout
    }

    $configOptions['configUrl'] = null;
    if (!empty($options['configUrl'])) {
      $configOptions['configUrl'] = $options['configUrl'];
    }

    $configOptions['configFile'] = null;
    if (!empty($options['configFile'])) {
      $configOptions['configFile'] = $options['configFile'];
    }

    // Create a new config object
    $this->config = new ClientConfig($sdkKey, $configOptions);

    $this->initaliseClient();
  }

  private function initaliseClient() {
    $this->refresh();
  }

  private function refresh() {
    try {
      // Check cache
      if ($this->config->cache->isValid()) {
        $this->config->logger->info("Loading data from cache");
  
        $this->responseEntity = $this->config->cache->get(self::CACHE_KEY);

        return;
      }

      // Check local file
      if (!empty($this->config->configFile)) {
        $this->config->logger->info("Attempting to load data from local file : {$this->config->configFile}");
        
        if (file_exists($this->config->configFile)) {
          $this->config->logger->info("Local file found");

          $json = file_get_contents($this->config->configFile);

          // $this->config->logger->info($json);

          $body = json_decode($json, true);
          
          $this->responseEntity =  new ResponseEntity(ResponseEntity::VALID, $body);

          $this->config->cache->set(self::CACHE_KEY, $this->responseEntity);
          return;
        }
      }

      // Check server
      $this->config->logger->info("Loading data from server");
      $httpResourceService = new HttpResourceService($this->config);

      if ($httpResourceService->isReady()) {
        $this->responseEntity = $httpResourceService->Fetch();
        $this->config->cache->set(self::CACHE_KEY, $this->responseEntity);
      }
    }
    catch (Exception $e) {
      $this->config->logger->error($e->message);
    }
  }

  public function GetValue($key, $defaultValue, User $user = null) {
    $this->refresh();

    $value = Evaluator::Evaluate($key, $this->responseEntity->flags, $this->config, $defaultValue, $user);
    $this->config->logger->info("Evaluation result: {$key} = {$value}");
    return $value;
  }
}