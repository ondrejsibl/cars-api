<?php
namespace App\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MissingAttributeException extends HttpException
{
    /**
     * @param string|null $message The internal exception message
     */
    public function __construct(?string $message = '')
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, $message);
    }
}
