<?php

namespace App\Exceptions\Contracts;

interface MultipleArgumentExceptionInterface extends \Throwable
{
    public function getMessages();
}