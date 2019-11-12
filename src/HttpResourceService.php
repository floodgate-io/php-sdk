<?php

namespace FloodgateSDK;

use FloodgateSDK\Configurations\ClientConfig;
use GuzzleHttp\Client;

final class HttpResourceService {
  private $client;

  private $config;

  function __construct($config, Array $options = []) {

    $this->config = $config;
    
    try {
      $this->client = new Client([
        'base_uri' => ClientConfig::CDN_BASEURL,
        'timeout'  => $config->timeout,
      ]);
    }
    catch (Exception $e) {
      throw $e;
    }
  }

  public function Fetch() {
    $options = [
      'headers' => [
        'X-FloodGate-SDK-Agent' => 'php-v' . ClientConfig::SDK_VERSION
      ]
    ];

    $url = $this->urlBuilder();
    
    $response = $this->client->request('GET', $url, $options);

    // Check ETag - if not changed return from cache

    $statusCode = $response->getStatusCode();
    if ($statusCode == 200) {
      $body = json_decode($response->getBody(), true);
      return new ResponseEntity(ResponseEntity::VALID, $body);
    }

    return new ResponseEntity(ResponseEntity::INVALID);

    // Print all response headers
    // foreach ($response->getHeaders() as $name => $values) {
    //   echo $name . ': ' . implode(', ', $values) . "\r\n";
    // }

    
  }

  private function urlBuilder() {
    $apiVersion = ClientConfig::API_VERSION;
    return "environment-files/{$this->config->SdkKey}/{$apiVersion}/flags-config.json";
  }
}