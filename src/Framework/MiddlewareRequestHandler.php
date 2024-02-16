<?php
declare(strict_types=1);

namespace Framework;

class MiddlewareRequestHandler implements RequestHandlerInterface
{


  public function __construct(
    private array $middlewares,
    private ControllerRequestHandler $controller_request_handler
  ) {

  }

  public function handle(Request $request): Response
  {

    $middleware = array_shift($this->middlewares);

    if ($middleware === null) {
      return $this->controller_request_handler->handle($request);

    }



    return $middleware->process($request, $this);


  }




}


//{--------------EXPLANATION--------------
//   This PHP class, MiddlewareRequestHandler, implements the RequestHandlerInterface within the Framework namespace. It takes an array of middleware objects and a ControllerRequestHandler object as constructor parameters.

// The handle method processes the incoming request by executing the middleware chain. It removes the first middleware from the array using array_shift and checks if it exists. If there are no more middleware left, it delegates the handling of the request to the ControllerRequestHandler object.

// If there is still middleware left, it calls the process method of the current middleware object, passing the request and itself ($this) as parameters. This allows the middleware to perform its processing and possibly pass the request to the next middleware in the chain.

// Overall, this class manages the execution of middleware in a chain before delegating the request handling to a controller.