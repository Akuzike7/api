<?php

namespace core;

use Exception;

use function PHPSTORM_META\type;
use Error;

class Token
{
    protected $key = 123456;

    private const ACCESS_TOKEN_LIFETIME = "60 sec";
    private const REFRESH_TOKEN_LIFETIME = "90 days";

    public static function generate_access_token($client)
    {
        $paylod = [
            'iat' => time(),
            'iss' => 'localhost',
            'exp' => time() + (60 * 2),  //
            'user' => $client,
            'type' => 'access'
        ];

        return $token = JWT::encode($paylod, 123456);
    }
    public static function generate_refresh_token($client)
    {
        $paylod = [
            'iat' => time(),
            'iss' => 'localhost',
            'exp' => self::REFRESH_TOKEN_LIFETIME,  //
            'user' => $client,
            'type' => 'refresh'
        ];

        return $token = JWT::encode($paylod, 123456);
    }

    public static function validate_access_token()
    {
        try
        {
            $token = self::bearerToken();
    
            $payload = JWT::decode($token, 123456, ['HS256']);
    
            return $payload;
        }
        catch(Exception $e)
        {
            return $res = [
                'message' => $e->getMessage(),
                'status' => '401'
            ];
        }
    }

    public static function validate_refresh_token()
    {
        try
        {
            $token = self::bearerToken();

            $payload = JWT::decode($token, 123456, ['HS256']);

            return $payload;
        }
        catch(Exception $e)
        {
            return $res = [
                'message' => $e->getMessage(),
                'status' => '401'
            ];
        }
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
            
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }

        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.0 401 Unauthorized');

        throw new Exception("Unauthorized access");
    }
}

    