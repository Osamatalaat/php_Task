<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JWTConfig {
    
    private static $secret_key = "dsfswer342fdsg43wgvdvx@#@e3r45353";
    private static $algo = "HS256";

    
    public static function encode($payload) {
        return JWT::encode($payload, self::$secret_key, self::$algo);
    }

    
    public static function decode($jwt) {
        try {
          
            $decoded = JWT::decode($jwt, new Key(self::$secret_key, self::$algo));
            return $decoded; // stdClass object
        } catch (\Exception $e) {
           
            return null;
        }
    }
}
