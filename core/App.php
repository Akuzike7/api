<?php

namespace core;

use Exception;

class App
{

    public Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function run()
    {
        try {
            echo json_encode($this->router->resolve());
        } catch (Exception $e) {

            http_response_code($e->getCode());
            
            echo json_encode(
                $res = [
                    'message' => $e->getMessage(),
                    'status' => $e->getCode()
                ]
            );
        }
    }
}
