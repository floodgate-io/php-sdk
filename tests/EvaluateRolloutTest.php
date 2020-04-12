<?php

namespace FloodgateSDK;

use FloodgateSDK\User;
use PHPUnit\Framework\TestCase;

class EvaluateRolloutTest extends TestCase {

  const CONFIG_FILE = "./tests/test-config.json";

  const SDK_KEY = "292b2f453a30c0f65c3414c73bb7e1ba2e42d1c02a2af1f7ada9f425187c";

  // Ok
  public function testValidateFlagResponse_Rollout1_ShouldReturnRed(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "red";
    $default = "grey";

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $user = new User("a9ac317e-6510-4903-9a42-e43d775b816d");

    $result = $floodgateClient->GetValue('rollout-colours', $default, $user);

    $this->assertEquals($expected, $result);
  }

  // Ok
  public function testValidateFlagResponse_Rollout1_ShouldReturnGreen(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "green";
    $default = "grey";

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $user = new User("a8ca3305-d4fc-4865-ae75-72edb8949244");

    $result = $floodgateClient->GetValue('rollout-colours', $default, $user);

    $this->assertEquals($expected, $result);
  }

  // Ok
  public function testValidateFlagResponse_Rollout1_ShouldReturnYellow(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "yellow";
    $default = "grey";

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $user = new User("ec144edb-7102-4091-ae1a-0d7b5f74707a");

    $result = $floodgateClient->GetValue('rollout-colours', $default, $user);

    $this->assertEquals($expected, $result);
  }

  // Ok
  public function testValidateFlagResponse_Rollout1_ShouldReturnOrange(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "orange";
    $default = "grey";

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $user = new User("1fa1cc72-6463-47d8-87df-a05a4e832dba");

    $result = $floodgateClient->GetValue('rollout-colours', $default, $user);

    $this->assertEquals($expected, $result);
  }

  // Ok
  public function testValidateFlagResponse_Rollout1_ShouldReturnBlue(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "blue";
    $default = "grey";

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $user = new User("7af3cd58-dc5b-4d53-982b-4892fd9b7df2");

    $result = $floodgateClient->GetValue('rollout-colours', $default, $user);

    $this->assertEquals($expected, $result);
  }
}
