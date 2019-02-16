<?php

class Pushpad {
  public static $auth_token;
  public static $project_id;

  public static function signature_for($data) {
    if (!isset(self::$auth_token)) throw new \Exception('You must set Pushpad\Pushpad::$auth_token');
    return hash_hmac('sha1', $data, self::$auth_token);
  }
}
