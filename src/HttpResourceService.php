<?php

namespace FloodgateSDK;

use FloodgateSDK\Configurations\ClientConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class HttpResourceService {
  private $client;

  private $config;

  private $isReady = false;

  function __construct($config, Array $options = []) {

    $this->config = $config;
    
    try {
      $this->client = new Client([
        'timeout'  => $config->timeout,
      ]);

      $this->isReady = true;
    }
    catch (GuzzleException $e) {
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
        'X-FloodGate-SDK-Agent' => 'PHP',
        'X-FloodGate-SDK-Version' => ClientConfig::SDK_VERSION
      ]
    ];

    $url = $this->buildUrl();
    
    try
    {
      $response = $this->client->request('GET', $url, $options);

      $statusCode = $response->getStatusCode();

      if ($statusCode == 200) {
        $body = json_decode($response->getBody(), true);

        return new ResponseEntity(ResponseEntity::VALID, $body);
      }
    }
    catch (GuzzleException $e) {
      $this->isReady = false;
    }
    catch (Exception $e) {
      $this->isReady = false;
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