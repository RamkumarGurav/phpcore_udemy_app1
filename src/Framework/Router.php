<?php
declare(strict_types=1);

namespace Framework;

class Router
{

  private array $routes = [];
  //like this
  // [ 
  //   [
  //     [path] => /admin/{controller}/{action} 
  //     [params] =>[[namespace] => Admin]  
  //   ],
  //   [
  //     [path] => /{controller}/{id:\d+}/show 
  //     [params] => [[action] => show]
  //   ],
  // ]


  public function add(string $path, array $params = []): void
  {

    // echo "I am inside Router's add method for path $path <br>";

    $this->routes[] = ["path" => $path, "params" => $params];

  }


  public function match(string $incoming_path, string $incoming_method): array|bool
  {



    // echo "I am inside Router's match method <br>";

    //if givepath is /product/sn-ué then "é" is a spanish letter , so what happens is url automatically encodes it as 
// "/product/sn-u%C3%A9" to decode it we can use the urldecode method and then we the original urlpath as "/product/sn-ué"

    // var_dump($incoming_path);//

    $incoming_path = urldecode($incoming_path); //when urlpath has other english words like spanish it encodes  it ,using urldecode method we can decode it, get it as it is. 

    //   echo "urldecode $incoming_path<br>";
    // var_dump($incoming_path);

    // Trim initial and trailing slashes from the given path
    $incoming_path = trim($incoming_path, "/");

    // echo "given path $incoming_path ";
    // echo '<br>';
    // Iterate through each registered route
    foreach ($this->routes as $route) {
      //getting pattern for each registered routes
      $pattern = $this->getPatternFromRoutePath($route["path"]);


      // Check if the given path matches the pattern of the current route
      if (preg_match($pattern, $incoming_path, $matches)) {
        // / $matches[0] contains the entire matched string
        // $matches['controller'] contains the captured "controller" name like products
        // $matches['action'] contains the captured "action" method name like show or index
        // $matches['id'] contains the captured id value like 1 or 12

        // Extract the captured parameters from the matches array

        // Filter the matches array to keep only string keys
        $matches = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY); //filters the elements of the $matches array, keeping only those with string keys. Let's break down the function and its parameters

        // Merge the captured parameters with the route's predefined parameters
        $params = array_merge($matches, $route['params']);

        // Check if the route has a specified method and if it matches the incoming request method
        if (array_key_exists("method", $params)) {
          // Convert both method names to lowercase for case-insensitive comparison
          // strtolower() is used to ensure case insensitivity in method comparison
          if (strtolower($incoming_method) !== strtolower($params["method"])) {
            // If the methods don't match, skip to the next route
            // If the method specified for the route does not match the incoming request method,
            // skip processing this route and continue to the next one
            continue;
          }
        }

        // Return the matched parameters
        // print_r($params);
        // echo "<br>";
        return $params;
      }

    }

    return false;


  }


  private function getPatternFromRoutePath(string $route_path): string
  {


    // echo "I am inside Router's getPatternFromRoutePath method for path $route_path<br>";


    //trimming initial and last slashes
    $route_path = trim($route_path, '/');
    //converting it an array , if stored routepath is /{controller}/{action} then array will be ["{controller}","{action}"]
    // Split the route path into segments based on slashes
    $segments = explode("/", $route_path);

    // print_r($segments);
    // echo " <br>";

    // Process each segment of the route path
    $segments = array_map(function (string $item) {

      // Check if the segment matches the pattern for a parameter placeholder like '{controller}' eg: '{controller}' or {action}
      if (preg_match("#^\{([a-z][a-z0-9]*)\}$#", $item, $matches)) {
        // If matched, return a named capturing group pattern for the parameter
        // The pattern captures any characters except '/'
        return "(?<" . $matches[1] . ">[^/]*)";
      }

      // Check if the segment matches the pattern for a parameter placeholder with regex like '{parameter:regex}' eg: '{id:\d+}'
      if (preg_match("#^\{([a-z][a-z0-9]*):(.+)\}$#", $item, $matches)) {
        // If matched, return a named capturing group pattern with specified regex
        return "(?<" . $matches[1] . ">" . $matches[2] . ")";

      }

      // echo "z $item <br>";
      // If segment does not match any pattern, return the segment as is
      return $item;
    }, $segments);

    // print_r($segments);
    // echo '1a segments array of stored route <br>';
    // echo "#^".implode("/", $segments)."$#";
    // echo '1b pattern for stored route  <br>';

    // "i" for making pattern case-insensitive and "u" for matching all unicode characters also like spanish charectors
    return "#^" . implode("/", $segments) . "$#iu";

  }


  public function getAllRoutes()
  {
    return $this->routes;
  }



}




// Explanation:

// The Router class is responsible for managing routes and matching incoming requests to registered routes.
// Routes are added using the add() method, which stores the path and associated parameters in the $routes array.
// The match() method is used to match incoming requests to registered routes. It iterates through each registered route, extracts parameters, and compares them with the incoming request.
// Route paths can contain placeholders for dynamic parameters, such as {controller}, {action}, or custom parameters like {id:\d+}.
// The getPatternFromRoutePath() method generates a regular expression pattern from the route path, allowing for flexible matching of dynamic segments and parameters.
// The router supports specifying HTTP methods for routes. If a method is specified in the route parameters, it checks if it matches the method of the incoming request.
// If a route matches the incoming request, it returns the matched parameters. Otherwise, it returns false.
// The getAllRoutes() method is provided to retrieve all registered routes for debugging or informational purposes.