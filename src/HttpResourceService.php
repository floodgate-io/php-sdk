<?php

namespace FloodgateSDK;

use FloodgateSDK\Configurations\ClientConfig;
use GuzzleHttp\Client;

final class HttpResourceService {
  private $client;

  private $config;

  private $isReady = false;

  function __construct($config, Array $options = []) {

    $this->config = $config;
    
    try {
      $this->client = new Client([
        // 'base_uri' => ClientConfig::CDN_BASEURL,
        'timeout'  => $config->timeout,
      ]);

      $this->isReady = true;
    }
    catch (Exception $e) {
      // log $e->getMessage();
      $this->isReady = false;
    }
  }

  public function isReady() {
    return $this->isReady;
  }

  public function Fetch() {
    $options = [
      'headers' => [
        'X-FloodGate-SDK-Agent' => 'php-v' . ClientConfig::SDK_VERSION
      ]
    ];

    $url = $this->buildUrl();
    
    $response = $this->client->request('GET', $url, $options);

    // Check ETag - if not changed return from cache

    $statusCode = $response->getStatusCode();
    if ($statusCode == 200) {
      $body = json_decode($response->getBody(), true);
      return new ResponseEntity(ResponseEntity::VALID, $body);
    }

    return new ResponseEntity(ResponseEntity::INVALID);
  }

  private function buildUrl() {
    $apiVersion = ClientConfig::API_VERSION;
    
    $baseUrl = ClientConfig::CDN_BASEURL;
    
    if (!empty($this->config->configUrl)) {
      $baseUrl = $this->config->configUrl;
    }

    $url = "{$baseUrl}/environment-files/{$this->config->SdkKey}/{$apiVersion}/flags-config.json";

    $this->config->logger->info($url);

    return $url;
  }
}