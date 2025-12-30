<?php

namespace App\Exceptions;

use Exception;

class BadRequestException extends Exception
{
    protected $message = 'Bad request';
    protected $code = 400;

    public function __construct($message = null)
    {
        if ($message) {
            $this->message = $message;
        }
        parent::__construct($this->message, $this->code);
    }
}
