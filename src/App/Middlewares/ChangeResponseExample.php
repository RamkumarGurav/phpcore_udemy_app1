<?php
declare(strict_types=1);

namespace App\Middlewares;

use Framework\Request;
use Framework\RequestHandlerInterface;
use Framework\Response;
use Framework\MiddlewareInterface;

class ChangeResponseExample implements MiddlewareInterface
{

  public function process(Request $request, RequestHandlerInterface $next): Response
  {
    $response = $next->handle($request);
    $response->setResponseBody($response->getResponseBody() . "Hellow from middleware1");


    return $response;

  }
}