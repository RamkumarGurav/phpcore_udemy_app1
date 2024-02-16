<?php
declare(strict_types=1);

namespace App\Middlewares;

use Framework\Request;
use Framework\RequestHandlerInterface;
use Framework\Response;
use Framework\MiddlewareInterface;

class ChangeRequestExample implements MiddlewareInterface
{

  public function process(Request $request, RequestHandlerInterface $next): Response
  {

    $request->post = array_map("trim", $request->post);

    $response = $next->handle($request);


    return $response;

  }
}