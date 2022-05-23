<?php

namespace core;

class App {

    public Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function run() {
        echo json_encode($this->router->resolve());
    }
}