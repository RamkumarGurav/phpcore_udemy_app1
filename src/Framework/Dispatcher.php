<?php
declare(strict_types=1);

namespace Framework;

use ReflectionMethod;
use Framework\Exceptions\PageNotFoundException;
use Framework\Response;
use UnexpectedValueException;

class Dispatcher
{

  // Constructor accepting Router, Container, and middleware classes
  public function __construct(
    private Router $router,
    private Container $container,
    private array $middleware_classes
  ) {

    // echo "I am inside Dispatcer's construct method <br>";
    // Initialize the router property with the provided router object
    $this->router = $router;
  }



  // Method to handle incoming requests
  public function handle(Request $request): Response
  {


    // Extracting the requested path from the URI and removing any base path
    $path = $this->getPath($request->uri);

    // Match the requested path to a route and get parameters
    $params = $this->router->match($path, $request->method);

    // If no route matched, throw a PageNotFoundException
    if ($params == false) {
      throw new PageNotFoundException("No route matched for '$path' for method '{$request->method}' ");

    }

    // print_r($params);//output:Array ( [controller] => products [id] => 59 [action] => show )


    // Extract controller name and action name from parameters
    $controller = $this->getControllerNameInStudCaps($params);
    $action = $this->getActionMethodNameInCamelCase($params);

    // Get arguments for the action method
    $args = $this->getActionArguements($controller, $action, $params);
    // print_r($args);//output;Array ( [id] => 59 )

    //here if url is like this http://localhost/phpcore_udemy_app_1/products/1/show
    //here controller classname is Products and actionMethodname is show and also it has the id as 1
    // print_r($args);//output: Array ( [id] => 1 )


    // Instantiate the controller object from the container
    //in code creates the controller object and also creates all other the dependent objects
    $controller_object = $this->container->get($controller);


    // Set up the controller object with response and template viewer
    $controller_object->setResponse($this->container->get(Response::class));
    $controller_object->setViewer($this->container->get(TemplateViewerInterface::class));

    // Create a controller request handler with the controller object, action, and args
    $controller_request_handler = new ControllerRequestHandler($controller_object, $action, $args);

    // Determine if any middleware is associated with the route
    $middleware = $this->getMiddleware($params);

    // Create a middleware request handler with middleware stack and controller request handler
    $middleware_request_handler = new MiddlewareRequestHandler($middleware, $controller_request_handler);

    // Handle the request by triggering the middleware chain
    return $middleware_request_handler->handle($request);


  }



  // Method to retrieve arguments for the action method
  private function getActionArguements(string $controllerClassName, string $actionMethodName, array $params)
  {

    // echo "I am inside Dispatcher's getActionArguements method <br>";


    // Create a ReflectionMethod object for the specified controller class and action method
    //using ReflectionMethod class to get the arguement names of a method of Class by passing  the className and method name  to it
    $reflectionMethodObj = new ReflectionMethod($controllerClassName, $actionMethodName);


    // Initialize an empty array to store arguments
    $args = [];

    // Iterate through each parameter of the action method
    foreach ($reflectionMethodObj->getParameters() as $parameterObj) {

      // Get the name of the argument
      $nameOfArguement = $parameterObj->getName();
      // echo "nameOfArguement:$nameOfArguement";
      // echo "<br>";
      // Add the argument to the args array// for example in the url "products/1/show" ,the $params[$nameOfArguement] ie $params["id"] gives the "1"
      $args[$nameOfArguement] = $params[$nameOfArguement];

    }


    return $args;

  }



  /*
        private function getActionArguments(string $controllerClassName, string $actionMethodName, array $params) {
          // Create a ReflectionMethod object for the specified controller class and action method
          $reflectionMethodObj = new ReflectionMethod($controllerClassName, $actionMethodName);
      
          $args = [];
      
          // Iterate through each parameter of the action method
          foreach ($reflectionMethodObj->getParameters() as $parameter) {
              // Get the name of the argument
              $nameOfArgument = $parameter->getName();
      
              // Check if the argument exists in the provided params array
              if (isset($params[$nameOfArgument])) {
                  // If the argument exists, add it to the args array
                  $args[$nameOfArgument] = $params[$nameOfArgument];
              } else {
                  // If the argument is not provided, check if it has a default value
                  if ($parameter->isDefaultValueAvailable()) {
                      // If a default value is available, use it
                      $args[$nameOfArgument] = $parameter->getDefaultValue();
                  } else {
                      // If no default value is available, throw an exception or handle the case as needed
                      throw new Exception("Missing argument '$nameOfArgument' for method '$actionMethodName'");
                  }
              }
          }
      
          return $args;
      }
      */



  // Method to convert controller name to studly caps format
  private function getControllerNameInStudCaps(array $params): string
  {

    // echo "I am inside Dispatcer's getControllerNameInStudCaps method <br>";



    // Convert controller name to studly caps format
    $controllerNameInLowerCase = strtolower($params["controller"]);
    $controllerNameInStudCaps = str_replace("-", "", ucwords($controllerNameInLowerCase, "-"));

    // Append namespace if specified in params
    $namespace = "App\Controllers";
    if (array_key_exists("namespace", $params)) {
      $namespace .= "\\" . $params["namespace"];
    }
    return $namespace . "\\" . $controllerNameInStudCaps;
  }

  // Method to convert action method name to camel case
  private function getActionMethodNameInCamelCase(array $params): string
  {

    // echo "I am inside Dispatcer's getActionMethodNameInCamelCase method <br>";

    // Convert action method name to camel case
    $methodNameInLowerCase = strtolower($params["action"]);
    $methodNameInCamelCase = lcfirst(str_replace("-", "", ucwords($methodNameInLowerCase, "-")));
    return $methodNameInCamelCase;
  }




  private function getPath(string $uri): string
  {
    // Extracting the requested path from the $_SERVER["REQUEST_URI"] variable
    $url_path = parse_url($uri, PHP_URL_PATH);

    // Validating the URL path
    if ($url_path === false) {
      throw new UnexpectedValueException("Malformed URL: '$uri'");
    }

    //removing "/phpcore_udemey_app_1" from original url_path
    return "/" . substr($url_path, strlen("/phpcore_udemey_app_1/") - 1);

  }






  public function getMiddleware(array $params): array
  {
    // Check if the "middleware" key exists in the $params array
    if (!array_key_exists("middleware", $params)) {
      // If not, return an empty array
      return [];
    }

    // Split the middleware string into an array using the pipe (|) delimiter
    $middlewares = explode("|", $params["middleware"]);

    // For each middleware in the $middlewares array, perform the following operations
    array_walk($middlewares, function (&$value) {
      // Check if the middleware exists in the middleware_classes array
      if (!array_key_exists($value, $this->middleware_classes)) {
        // If the middleware does not exist, throw an UnexpectedValueException
        throw new UnexpectedValueException("Middleware '$value' not found in config settings");
      }
      // Get the middleware class from the container based on the configured class name
      // and replace the middleware name in the $middlewares array with the actual middleware object
      $value = $this->container->get($this->middleware_classes[$value]);
    });

    // Return the array of middleware objects
    return $middlewares;
  }



}


// Explanation:

// The Dispatcher class is responsible for handling incoming requests and dispatching them to the appropriate controller action.
// It takes the Router, Container, and an array of middleware classes as dependencies in its constructor.
// The handle() method is the entry point for request handling. It matches the requested path to a route, extracts the controller and action names, and then executes the corresponding action method on the controller.
// The getActionArguments() method retrieves arguments for the action method by inspecting its parameters using reflection.
// Helper methods like getControllerNameInStudCaps() and getActionMethodNameInCamelCase() convert controller and action names into the appropriate formats.
// Middleware is retrieved and applied to the request based on route configuration.
// The getPath() method extracts the path from the URI, removing any base path if present.
// If no route matches the requested path, a PageNotFoundException is thrown.
// The getMiddleware() method retrieves middleware classes specified in the route configuration.