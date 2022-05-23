<?php

namespace core;

class Request

{
    public function __construct()
    {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
    }
    public function resource()
    {
        $resource = '/';

        if (isset($_GET['url'])) {
            $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));

            $resource = '/' . $url[0];
        }

        return $resource;
    }

    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function url()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }

    public function params()
    {
        $body = [];

        $params = $this->url() ? array_values($this->url()) : [];

        foreach($params as $key => $value) {
            if($key != 0) {
                $body[] = $value;
            }
        }

        if(empty($body)) {
            return null;
        }

        return $body;
    }

    public function raw()
    {

        $attributes = json_decode(file_get_contents("php://input"));
        
        foreach ($attributes as $key => $value) {
            $this->{ $key} = $value;
        }
 
        return $this;
    }
}