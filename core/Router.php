<?php

namespace core;

class Router {
    public $routes = [];

    public Request $request;

    public function __construct()
    {
        $this->request = new Request;
    }

    public function get($resource, $callback) 
    {
        $this->routes['get'][$resource] = $callback;
    }

    public function post($resource, $callback) 
    {
        $this->routes['post'][$resource] = $callback;
    }

    public function resolve()
    {
        $method = $this->request->method();
        $resource = $this->request->resource();

        $callback = $this->routes[$method][$resource] ?? false;

        if($callback === false) {
            return '404';
        }

        if(is_string($callback)) {
            return $callback;
        }

        if(is_array($callback)) {
            $controller = new $callback[0];

            $callback[0] = $controller;
        }

        return call_user_func($callback, $this->request);
    }
}