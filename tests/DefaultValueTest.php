<?php

namespace FloodgateSDK;

use FloodgateSDK\User;
use PHPUnit\Framework\TestCase;

class DefaultValueTest extends TestCase {

  const CONFIG_FILE = "./tests/test-config.json";

  const SDK_KEY = "292b2f453a30c0f65c3414c73bb7e1ba2e42d1c02a2af1f7ada9f425187c";

  public function testValidateFlagSDKDefaultResponse_ShouldReturnGrey(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "grey";
    $default = "grey";

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('non-existent-flag', $default);

    $this->assertEquals($expected, $result);
  }

  public function testValidateFlagSDKDefaultResponse_ShouldReturnRed(): void {
    $sdkKey = self::SDK_KEY;

    $expected = "red";
    $default = "grey";

    $options = [
      "configFile" => self::CONFIG_FILE
    ];

    $floodgateClient = new FloodgateClient($sdkKey, $options);

    $result = $floodgateClient->GetValue('colours', $default);

    $this->assertEquals($expected, $result);
  }
}