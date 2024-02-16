<?php
declare(strict_types=1);

namespace Framework;

interface RequestHandlerInterface
{
  public function handle(Request $request): Response;
}


//{--------------EXPLANATION--------------
// This PHP interface, RequestHandlerInterface, defines a method handle which takes a Request object as a parameter and returns a Response object. It serves as a contract for classes that handle incoming HTTP requests and generate appropriate responses. By implementing this interface, classes can define their own logic for processing requests and producing responses within a framework.