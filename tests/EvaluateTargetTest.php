<?php

namespace FloodgateSDK;

use FloodgateSDK\User;
use PHPUnit\Framework\TestCase;

class FloodgateClientTest extends TestCase {

  const CONFIG_FILE = "./tests/test-config.json";

  const SDK_KEY = "292b2f453a30c0f65c3414c73bb7e1ba2e42d1c02a2af1f7ada9f425187c";

  public function testValidateFlagResponse_TargetEqualTo_ShouldReturnYellow(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "yellow";
    $default = "grey";

    $customAttributes = [
      "email" => "spiderman@marvel.com",
      "name" => "Peter Parker",
      "country" => "US"
    ];
    
    $user = new User("a-unique-id", $customAttributes);

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('colours', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_TargetNotEqualTo_ShouldReturnOrange(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "orange";
    $default = "grey";

    $customAttributes = [
      "name" => "Bruce Banner"
    ];
    
    $user = new User("a-unique-id", $customAttributes);

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('colours', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_TargetGreaterThan_ShouldReturnBlue(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "blue";
    $default = "grey";

    $customAttributes = [
      "score1" => 45,
    ];
    
    $user = new User("a-unique-id", $customAttributes);

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('colours', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_TargetGreaterThanEqualTo_ShouldReturnGreen(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "green";
    $default = "grey";
    
    $customAttributes = [
      "score2" => 135,
    ];
    
    $user = new User("a-unique-id", $customAttributes);

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('colours', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_TargetLessThan_ShouldReturnYellow(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "yellow";
    $default = "grey";
    
    $customAttributes = [
      "score3" => 35,
    ];
    
    $user = new User("a-unique-id", $customAttributes);

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('colours', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_TargetLessThanEqualTo_ShouldReturnBlue(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "blue";
    $default = "grey";
    
    $customAttributes = [
      "score4" => 120,
    ];
    
    $user = new User("a-unique-id", $customAttributes);

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('colours', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_TargetContains_ShouldReturnGreen(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "green";
    $default = "grey";
    
    $customAttributes = [
      "email" => "random@gmail.com",
    ];
    
    $user = new User("a-unique-id", $customAttributes);

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('colours', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_TargetNotContains_ShouldReturnYellow(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "yellow";
    $default = "grey";
    
    $customAttributes = [
      "email" => "random@yahoo.co.uk",
    ];
    
    $user = new User("a-unique-id", $customAttributes);

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('colours', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_TargetEndsWith_ShouldReturnYellow(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "yellow";
    $default = "grey";
    
    $customAttributes = [
      "country" => "United Kingdom",
    ];
    
    $user = new User("a-unique-id", $customAttributes);

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('colours', $default, $user);

    $this->assertEquals($expected, $result);
  }
}
