<?php

namespace core;

use Exception;

class Token
{
    protected $key = 123456;

    private const ACCESS_TOKEN_LIFETIME = "5 minutes";
    private const REFRESH_TOKEN_LIFETIME = "90 days";

    public static function generate($client)
    {
        $paylod = [
            'iat' => time(),
            'iss' => 'localhost',
            'exp' => self::ACCESS_TOKEN_LIFETIME,  //
            'user' => $client,
        ];

        return $token = JWT::encode($paylod, 123456);
    }

    public static function validate()
    {

        $token = self::bearerToken();

        $payload = JWT::decode($token, 123456, ['HS256']);

        return $payload;
    }

    public static function validate_refresh_token()
    {

        $token = self::bearerToken();

        $payload = JWT::decode($token, 123456, ['HS256']);

        return $payload;
    }

    public static function authorizationHeader()
    {
        $headers = null;

        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER['Authorization']);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER['Authorization']);
        }
        else if (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();

            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));

            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }

        return $headers;
    }
    public static function bearerToken()
    {
        $headers = self::authorizationHeader();

        if (!empty($headers)) {
            if (preg_match('/Bearers\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }

        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');

        throw new Exception("Unauthorized access");
    }
}

    