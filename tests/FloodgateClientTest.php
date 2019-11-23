<?php

namespace FloodgateSDK;

use FloodgateSDK\User;
use PHPUnit\Framework\TestCase;

class FloodgateClientTest extends TestCase {

  public function testCreateClientInstanceWithNullSdkKey(): void {
    $this->expectException(\InvalidArgumentException::class);

    $floodgateClient = new FloodgateClient(null);
  }

  public function testCreateClientInstanceWithEmptySdkKey(): void {
    $this->expectException(\InvalidArgumentException::class);

    $floodgateClient = new FloodgateClient("");
  }

  public function testCreateClientInstance(): void {
    $sdkKey = "sdkkey";

    $options = [
      "configFile" => "./tests/test-flags-rollout-target.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $this->assertInstanceOf(FloodgateClient::class, $floodgateClient);
  }

  public function testValidateFlagSDKDefaultResponse_ShouldReturnGrey(): void {
    $sdkKey = "sdkkey";

    $expected = "grey";
    $default = "grey";

    $options = [
      "configFile" => "./tests/test-flags-rollout-target.json",
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('non-existent-flag', $default);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagSDKDefaultResponse_ShouldReturnPink(): void {
    $sdkKey = "sdkkey";

    $expected = "pink";
    $default = "grey";

    $options = [
      // "configUrl" => "http://localhost:8765/",
      "configFile" => "./tests/test-flags.json",
      //"logger" => new \FloodgateSDK\Loggers\ConsoleLogger()
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_Target1_ShouldReturnYellow(): void {
    $sdkKey = "sdkkey";

    $expected = "yellow";
    $default = "grey";

    $customAttributes = [
      "email" => "spiderman@marvel.com",
      "name" => "Peter Parker",
      "country" => "US"
    ];
    
    $user = new User("a-unique-id", $customAttributes);

    $options = [
      "configFile" => "./tests/test-flags-rollout-target.json",
      // "logger" => new \FloodgateSDK\Loggers\ConsoleLogger()
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);

    unset($floodgateClient);

    $options = [
      "configFile" => "./tests/test-flags-target.json",
      // "logger" => new \FloodgateSDK\Loggers\ConsoleLogger()
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_Target2_ShouldReturnOrange(): void {
    $sdkKey = "sdkkey";

    $expected = "orange";
    $default = "grey";

    $customAttributes = [
      "email" => "spiderman@marvel.com",
      "name" => "Peter Parker",
      "country" => "UK"
    ];
    
    $user = new User("a-unique-id", $customAttributes);

    $options = [
      "configFile" => "./tests/test-flags-rollout-target.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);

    unset($floodgateClient);

    $options = [
      "configFile" => "./tests/test-flags-target.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_Target3_ShouldReturnBrown(): void {
    $sdkKey = "sdkkey";

    $expected = "brown";
    $default = "grey";

    $customAttributes = [
      "email" => "spiderman@marvel.com",
    ];
    
    $user = new User("a-unique-id", $customAttributes);

    $options = [
      "configFile" => "./tests/test-flags-rollout-target.json",
      // "logger" => new \FloodgateSDK\Loggers\ConsoleLogger()
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);

    unset($floodgateClient);

    $options = [
      "configFile" => "./tests/test-flags-target.json",
      // "logger" => new \FloodgateSDK\Loggers\ConsoleLogger()
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_Rollout1_ShouldReturnBlue(): void {
    $sdkKey = "sdkkey";

    $expected = "blue";
    $default = "grey";
    
    $user = new User("d2405fc0-c9cd-49e7-a07e-bf244d6d360b");

    $options = [
      "configFile" => "./tests/test-flags-rollout-target.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);

    unset($floodgateClient);

    $options = [
      "configFile" => "./tests/test-flags-rollout.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_Rollout2_ShouldReturnPurple(): void {
    $sdkKey = "sdkkey";

    $expected = "purple";
    $default = "grey";
    
    $user = new User("4d5817c5-4450-4cd4-b035-e24c2b72d50a");

    $options = [
      "configFile" => "./tests/test-flags-rollout-target.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);

    unset($floodgateClient);

    $options = [
      "configFile" => "./tests/test-flags-rollout.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_Rollout3_ShouldReturnBrown(): void {
    $sdkKey = "sdkkey";

    $expected = "brown";
    $default = "grey";
    
    $user = new User("1dd97efd-62eb-418c-b2fe-11dd0ca188e0");

    $options = [
      "configFile" => "./tests/test-flags-rollout-target.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);

    unset($floodgateClient);

    $options = [
      "configFile" => "./tests/test-flags-rollout.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_Rollout4_ShouldReturnPink(): void {
    $sdkKey = "sdkkey";

    $expected = "pink";
    $default = "grey";
    
    $user = new User("cb3c2d9c-9908-4f80-b7a4-c3cf2aa4d134");

    $options = [
      "configFile" => "./tests/test-flags-rollout-target.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);

    unset($floodgateClient);

    $options = [
      "configFile" => "./tests/test-flags-rollout.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_Rollout5_ShouldReturnGreen(): void {
    $sdkKey = "sdkkey";

    $expected = "green";
    $default = "grey";
    
    $user = new User("27089992-de46-4fe3-a45e-bc2755d5a3f0");

    $options = [
      "configFile" => "./tests/test-flags-rollout-target.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);

    unset($floodgateClient);

    $options = [
      "configFile" => "./tests/test-flags-rollout.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_Rollout6_ShouldReturnRed(): void {
    $sdkKey = "sdkkey";

    $expected = "red";
    $default = "grey";
    
    $user = new User("1527d16f-f287-49ba-bc61-bf86d39a2ab2");

    $options = [
      "configFile" => "./tests/test-flags-rollout-target.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);

    unset($floodgateClient);

    $options = [
      "configFile" => "./tests/test-flags-rollout.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_Rollout7_ShouldReturnYellow(): void {
    $sdkKey = "sdkkey";

    $expected = "yellow";
    $default = "grey";
    
    $user = new User("86b56de0-8383-483e-842b-80528e3923dc");

    $options = [
      "configFile" => "./tests/test-flags-rollout-target.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);

    unset($floodgateClient);

    $options = [
      "configFile" => "./tests/test-flags-rollout.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagResponse_Rollout8_ShouldReturnOrange(): void {
    $sdkKey = "sdkkey";

    $expected = "orange";
    $default = "grey";
    
    $user = new User("68450bec-70c1-4b49-91ba-5c756b2b3191");

    $options = [
      "configFile" => "./tests/test-flags-rollout-target.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);

    unset($floodgateClient);

    $options = [
      "configFile" => "./tests/test-flags-rollout.json"
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('background-colour', $default, $user);

    $this->assertEquals($expected, $result);
  }
}