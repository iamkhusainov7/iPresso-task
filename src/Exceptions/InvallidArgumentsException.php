<?php

use App\Exceptions\Contracts\MultipleArgumentExceptionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class InvallidArgumentsException extends HttpException implements
    HttpExceptionInterface,
    MultipleArgumentExceptionInterface
{
    protected array $messages;

    public function __construct(
        int $statusCode = 400,
        array $messages = [],
        array $headers = []
    ) {
        $this->messages = $messages;
        $this->headers = $headers;

        parent::__construct(
            $statusCode,
            'Invallid arguments were provided!',
            null,
            $headers
        );
    }

    public function getMessages()
    {
        return $this->messages;
    }
}
