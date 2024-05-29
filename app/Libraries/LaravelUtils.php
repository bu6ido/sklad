<?php

namespace App\Libraries;

class LaravelUtils
{

  public static function arrVal($arr, $key)
  {
    if (isset($arr) && isset($key))
    {
      if (isset($arr[$key]))
      {
        return $arr[$key];
      }
    }
    return null;
  }

  public static function doubleArrVal($arr, $key1, $key2)
  {
    $temp = self::arrVal($arr, $key1);
    if (self::isArray($temp))
    {
      $result = self::arrVal($temp, $key2);
      return $result;
    }
    return null;
  }

  public static function isInt($v)
  {
    if (is_int($v))
      return true;
    if (is_string($v))
    {
      if (empty($v) && ($v !== '0'))
        return false;

      if ($v[0] == '-')
      {
        return ctype_digit(substr($v, 1));
      }
      return ctype_digit($v);
    }
    return false;
  }

  public static function isArray($arr)
  {
    if (is_array($arr) && !empty($arr))
    {
      return true;
    }
    return false;
  }

  // converts array of strings to string of sequences
  public static function arrToSeqStr($arr)
  {
    if (!self::isArray($arr))
    {
      return '';
    }
    $temp = array_map(function($v) { return "'".$v."'"; }, $arr);
    $result = implode(",", $temp);
    return $result;
  }

  public static function dateMinTime($dateStr)
  {
    $timestamp = strtotime($dateStr);
    $zeroedtimestamp = mktime(0, 0, 0, date('n', $timestamp), date('j', $timestamp), date('Y', $timestamp));
    return date('Y-m-d H:i:s', $zeroedtimestamp);
  }

  public static function dateMaxTime($dateStr)
  {
    $timestamp = strtotime($dateStr);
    $maxTimestamp = mktime(23, 59, 59, date('n', $timestamp), date('j', $timestamp), date('Y', $timestamp));
    return date('Y-m-d H:i:s', $maxTimestamp);
  }

  public static function firstDayOfWeek($date)
  {
    $day = date_format($date, 'w');
    $interval = date_interval_create_from_date_string('-'.$day.' days');
    $weekStart = clone $date;
    date_add($weekStart, $interval);
    return $weekStart;
  }

  public static function lastDayOfWeek($date)
  {
    $day = date_format($date, 'w');
    $interval = date_interval_create_from_date_string('+'.(6-$day).' days');
    $weekEnd = clone $date; 
    date_add($weekEnd, $interval);
    return $weekEnd;
  }  

  public static function sessionGet($viewId, $key)
  {
    if (empty($viewId) || empty($key))
    {
      return null;
    }
    return \Session::get($viewId.'_'.$key);
  }

  public static function sessionPut($viewId, $key, $value)
  {
    if (empty($viewId) || empty($key) | empty($value))
    {
      return;
    }
    \Session::put($viewId.'_'.$key, $value);
  }

  public static function sessionDel($viewId, $key)
  {
    if (empty($viewId) || empty($key))
    {
      return;
    }
    \Session::forget($viewId.'_'.$key);
  }

  public static function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
  }

  public static function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
  }
}

