<?php

namespace FloodgateSDK\Loggers;

use Psr\Log\LoggerInterface;

class EchoLogger implements LoggerInterface {
  /**
   * System is unusable.
   *
   * @param string $message
   * @param array $context
   * @return void
   */
  public function emergency($message, array $context = array()) {
    $this->write("Emergency", $message);
  }

  /**
   * Action must be taken immediately.
   *
   * Example: Entire website down, database unavailable, etc. This should
   * trigger the SMS alerts and wake you up.
   *
   * @param string $message
   * @param array $context
   * @return void
   */
  public function alert($message, array $context = array()) {
    $this->write("Alert", $message);
  }

  /**
   * Critical conditions.
   *
   * Example: Application component unavailable, unexpected exception.
   *
   * @param string $message
   * @param array $context
   * @return void
   */
  public function critical($message, array $context = array()) {
    $this->write("Critical", $message);
  }

  /**
   * Runtime errors that do not require immediate action but should typically
   * be logged and monitored.
   *
   * @param string $message
   * @param array $context
   * @return void
   */
  public function error($message, array $context = array()) {
    $this->write("Error", $message);
  }

  /**
   * Exceptional occurrences that are not errors.
   *
   * Example: Use of deprecated APIs, poor use of an API, undesirable things
   * that are not necessarily wrong.
   *
   * @param string $message
   * @param array $context
   * @return void
   */
  public function warning($message, array $context = array()) {
    $this->write("Warning", $message);
  }

  /**
   * Normal but significant events.
   *
   * @param string $message
   * @param array $context
   * @return void
   */
  public function notice($message, array $context = array()) {
    $this->write("Notice", $message);
  }

  /**
   * Interesting events.
   *
   * Example: User logs in, SQL logs.
   *
   * @param string $message
   * @param array $context
   * @return void
   */
  public function info($message, array $context = array()) {
    $this->write("Info", $message);
  }

  /**
   * Detailed debug information.
   *
   * @param string $message
   * @param array $context
   * @return void
   */
  public function debug($message, array $context = array()) {
    $this->write("Debug", $message);
  }

  /**
   * Logs with an arbitrary level.
   *
   * @param mixed $level
   * @param string $message
   * @param array $context
   * @return void
   */
  public function log($level, $message, array $context = array()) {
    $this->write($level, $message);
  }

  private function write($level, $message) {
    $date = date('Y-m-d H:i:s');

    if (!\is_array($message)) {
      echo "{$date}\t{$level}\t\t{$message}<br>";
    }
    else {
      echo "{$date}\t{$level}<br>";
      print_r($message);
    }
  }
}