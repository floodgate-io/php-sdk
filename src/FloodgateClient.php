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

    // Create a new config object
    $this->config = new ClientConfig($sdkKey, $configOptions);

    $this->initaliseClient();
  }

  private function initaliseClient() {
    $this->refresh();
  }

  private function refresh() {
    if ($this->config->cache->isValid()) {
      $this->config->logger->info("Loading data from cache");

      $this->responseEntity = $this->config->cache->get(self::CACHE_KEY);
    }
    else {
      $this->config->logger->info("Loading data from server");

      $httpResourceService = new HttpResourceservice($this->config);
      $this->responseEntity = $httpResourceService->Fetch();
      $this->config->cache->set(self::CACHE_KEY, $this->responseEntity);
    }
  }

  public function GetValue($key, $defaultValue, User $user = null) {
    $this->refresh();

    $value = Evaluator::Evaluate($key, $this->responseEntity->flags, $this->config, $defaultValue, $user);
    $this->config->logger->info("Evaluation result: {$key} = {$value}");
    return $value;
  }
}