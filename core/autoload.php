<?php

spl_autoload_register(function ($class) {
    $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);

    if(file_exists("$class.php")) {
        require_once("$class.php");
    }
});