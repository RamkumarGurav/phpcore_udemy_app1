<?php
declare(strict_types=1);

namespace App\Middlewares;

use Framework\Request;
use Framework\RequestHandlerInterface;
use Framework\Response;
use Framework\MiddlewareInterface;

class RedirectExample implements MiddlewareInterface
{

  public function __construct(private Response $response)
  {
  }

  public function process(Request $request, RequestHandlerInterface $next): Response
  {

    $this->response->redirect("/phpcore_udemy_app_1/");

    return $this->response;

  }
}


//{----------------------------
// This  defines a middleware class named RedirectExample within the App\Middlewares namespace. It implements the MiddlewareInterface and redirects requests to "/phpcore_udemy_app_1/" using the injected Response object. The constructor facilitates dependency injection of the Response object.