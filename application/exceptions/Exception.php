<?php

namespace application\exceptions;

class Exception
{
    public function __construct($message)
    {
        $this->getMessage($message);
    }

    public function getMessage($message)
    {
        echo $message;
    }

}