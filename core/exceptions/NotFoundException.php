<?php

namespace core\exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $message = 'Not found';

    protected $code = 404;
}