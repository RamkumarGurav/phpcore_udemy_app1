<?php

declare(strict_types=1);

namespace Framework;

use Framework\Request;
use Framework\Response;
use Framework\RequestHandlerInterface;

interface MiddlewareInterface
{

  public function process(Request $request, RequestHandlerInterface $next): Response;
}


//{--------------EXPLANATION--------------
// This is a PHP interface called MiddlewareInterface defined within the Framework namespace. It requires classes implementing it to have a process method, which takes a Request object and a RequestHandlerInterface object as parameters, and returns a Response object. This interface is typically used in middleware components within a framework to intercept and process HTTP requests before they reach the main request handler.->