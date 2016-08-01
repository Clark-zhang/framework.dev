<?php

namespace Calendar\Controller;

use Symfony\Component\HttpFoundation\Response;

class ErrorController
{
    public function exceptionAction($exception)
    {
        $msg = 'Something went wrong! ('.$exception->getMessage().')';

        return new Response($msg, $exception->getStatusCode());
    }
}