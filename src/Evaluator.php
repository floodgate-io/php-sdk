<?php

namespace FloodgateSDK;

class Evaluator {
  const COMPARATOR_EQUAL_TO         = 1;
  const COMPARATOR_NOT_EQUAL_TO     = 2;
  const COMPARATOR_GREATER          = 3;
  const COMPARATOR_GREATER_EQUAL_TO = 4;
  const COMPARATOR_LESS             = 5;
  const COMPARATOR_LESS_EQUAL_TO    = 6;
  const COMPARATOR_CONTAINS         = 7;
  const COMPARATOR_NOT_CONTAIN      = 8;
  const COMPARATOR_ENDS_WITH        = 9;

  public static function Evaluate($key, $flags, $config, $defaultValue, User $user = null) {

    // Check to see there is flag data present
    if (empty($flags)) {
      $config->logger->debug("No flags data present, delivering defaultValue");
      return $defaultValue;
    }

    // Find flag based on key
    $arrayKey = array_search($key, array_column($flags, 'key'));
    if (!isset($arrayKey) || !is_numeric($arrayKey)) {
      $config->logger->debug("Flag not found, delivering defaultValue");
      return $defaultValue;
    }

    $flag = $flags[$arrayKey];
    
    // Check to see if there is a User object
    // Without an User object we cannot evaluate rollouts or targets
    if ($user == null) {
      $config->logger->debug("No user data found, unable to evaluate rollouts and targets");
      return $flag['value'];
    }

    // Check to see if targeting is enabled
    if (!$flag['is_targeting_enabled']) {
      if ($flag['is_rollout']) {
        $config->logger->debug("Targeting not enabled, evaluating rollouts");
        // Evaluate rollout
        return self::EvaluateRollouts($key, $user, $flag['rollouts'], $flag['value'], $config->logger);
      }

      $config->logger->debug("Targeting and rollouts not enabled, delivering flag value");
      return $flag['value'];
    }

    if (!empty($flag['targets'])) {
      $config->logger->debug("Targeting enabled, evaluatng targets");

      // return self::EvaluateTargets($key, $user, $flag['targets'], $flag['value'], $config->logger);
      return self::EvaluateTargets($key, $user, $flag, $flag['value'], $config->logger);
    }

    $config->logger->debug("No data found, returning default value");
    return $defaultValue;
  }

  private static function EvaluateRollouts($key, $user, $rollouts, $defaultValue, $logger) {
    $hashString = $key . $user->getId();
    $hash = substr(sha1($hashString), 0, 7);
    $hashValue = intval($hash, 16);
    $scale = $hashValue % 100;

    $logger->debug("Rollout scale = {$scale}");

    $rolloutLowerLimit = 0;
    $rolloutUpperLimit = 0;
    $rolloutValue = $defaultValue;

    foreach($rollouts as $rollout) {
      $rolloutUpperLimit += (int)$rollout['percentage'];

      if ($scale > $rolloutLowerLimit && $scale <= $rolloutUpperLimit) {
        $rolloutValue = $rollout['value'];
        break;
      }

      $rolloutLowerLimit += (int)$rollout['percentage'];
    }

    return $rolloutValue;
  }

  private static function EvaluateTargets($key, $user, $flag, $defaultValue, $logger) {
    $valid = false;

    $targets = $flag['targets'];

    foreach($targets as $index => $target) {
      $validRuleCount = 0;

      $rulesCount = count($target['rules']);

      foreach($target['rules'] as $rule) {
        $isRuleValid = 0;
        
        $userAttributeValue = $user->GetAttributeValue($rule['attribute']);

        if (empty($userAttributeValue)) {
          continue;
        }

        switch ($rule['comparator']) {
          case self::COMPARATOR_EQUAL_TO:
            $logger->debug("Evaluating target of type equal for $userAttributeValue");

            $isRuleValid = (int) in_array(strtolower($userAttributeValue), $rule['values']);
            break;
          case self::COMPARATOR_NOT_EQUAL_TO:
            $logger->debug("Evaluating target of type not equal");

            $isRuleValid = (int) !in_array(strtolower($userAttributeValue), $rule['values']);
            break;
          case self::COMPARATOR_GREATER:
            $logger->debug("Evaluating target of greater than");
            // There should only ever be a single value
            
            if (empty($rule['values'][0])) {
              $logger->debug("No rule value found");
              break;
            }

            $value = $rule['values'][0];

            $isRuleValid = (double)$userAttributeValue > (double)$value;
            break;
          case self::COMPARATOR_GREATER_EQUAL_TO:
            $logger->debug("Evaluating target of greater than or equal to");
            // There should only ever be a single value
            
            if (empty($rule['values'][0])) {
              $logger->debug("No rule value found");
              break;
            }

            $value = $rule['values'][0];

            $isRuleValid = (double)$userAttributeValue >= (double)$value;
            break;
          case self::COMPARATOR_LESS:
            $logger->debug("Evaluating target of less than");
            // There should only ever be a single value
            
            if (empty($rule['values'][0])) {
              $logger->debug("No rule value found");
              break;
            }

            $value = $rule['values'][0];

            $isRuleValid = (double)$userAttributeValue < (double)$value;
            break;
          case self::COMPARATOR_LESS_EQUAL_TO:
            $logger->debug("Evaluating target of less than or equal to");
            // There should only ever be a single value
            
            if (empty($rule['values'][0])) {
              $logger->debug("No rule value found");
              break;
            }

            $value = $rule['values'][0];

            $isRuleValid = (double)$userAttributeValue <= (double)$value;
            break;
          case self::COMPARATOR_CONTAINS:
            $logger->debug("Evaluating target of type contains");

            foreach($rule['values'] as $value) {
              if (strpos($userAttributeValue, $value) !== false) {
                $isRuleValid = 1;
                break;
              }
            }
            break;
          case self::COMPARATOR_NOT_CONTAIN:
            $logger->debug("Evaluating target of type does not contain");

            $isRuleValid = 1;
            foreach($rule['values'] as $value) {
              if (strpos($userAttributeValue, $value) !== false) {
                $isRuleValid = 0;
                break;
              }
            }
            break;
          case self::COMPARATOR_ENDS_WITH:
            $logger->debug("Evaluating target of type ends with");

            foreach($rule['values'] as $value) {
              if (self::endsWith(strtolower($userAttributeValue), $value)) {
                $isRuleValid = 1;
                break;
              }
            }
            break;
        }

        $validRuleCount = $validRuleCount + $isRuleValid;
      }

      if ($validRuleCount == $rulesCount) {
        if ($target['is_rollout']) {
          // Evaluate rollout
          return self::EvaluateRollouts($key, $user, $target['rollouts'], $defaultValue, $logger);
        }

        return $target['value'];
      }
    }

    if ($flag['is_rollout']) {
      // Evaluate rollout
      return self::EvaluateRollouts($key, $user, $flag['rollouts'], $defaultValue, $logger);
    }

    return $defaultValue;
  }

  private function endsWith($string, $endString) 
  { 
      $len = strlen($endString); 
      if ($len == 0) { 
          return true; 
      } 
      return (substr($string, -$len) === $endString); 
  }
}
