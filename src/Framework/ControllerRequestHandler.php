<?php
declare(strict_types=1);

namespace Framework;


class ControllerRequestHandler implements RequestHandlerInterface
{


  public function __construct(
    private Controller $controller,
    private string $action,
    private array $args
  ) {

  }


  public function handle(Request $request): Response
  {

    $this->controller->setRequest($request);

    return ($this->controller)->{$this->action}(...$this->args);

  }


}


//{--------------EXPLANATION--------------

// This PHP class, ControllerRequestHandler, implements the RequestHandlerInterface. It takes a Controller object, an action name, and an array of arguments as constructor parameters. When its handle method is called with a Request object, it sets the request on the controller and invokes the specified action with the provided arguments. The resulting response is returned. Essentially, it acts as a mediator between HTTP requests and controller actions within a framework.