<?php
// echo "I am inside  routes.php <br>";

// require 'src/router.php';
//here we didn't add extra backslah bcz current file which is "index.php" doesn't have any namespace.
$router = new Framework\Router();


////////////// THIS IS ROUTE STACK 
//ALWAYS SPACE SPECIC ROUTES ON TOP AND GENERIC ROUTES AT BOTTOM

//SPECIFIC ROUTES
$router->add("/admin/{controller}/{action}", ["namespace" => "Admin"]);

// $router->add("/{controller}/{id:\d+}/show", ["action" => "show", "middleware" => "message|trim"]);
$router->add("/{controller}/{id:\d+}/show", ["action" => "show"]);
$router->add("/{controller}/{id:\d+}/edit", ["action" => "edit"]);
$router->add("/{controller}/{id:\d+}/update", ["action" => "update"]);
$router->add("/{controller}/{id:\d+}/delete", ["action" => "delete"]);
$router->add("/{controller}/{id:\d+}/destroy", ["action" => "destroy", "method" => "post"]);

$router->add("/{controller}/destroyall", ["action" => "destroyall", "method" => "post"]);

$router->add("/product/{slug:[\w-]+}", ["controller" => "home", "action" => "index"]);
$router->add("/", ["controller" => "home", "action" => "index"]);
$router->add("/home", ["controller" => "home", "action" => "index"]);
$router->add("/products", ["controller" => "products", "action" => "index"]);

//GENERIC ROUTES
// $router->add("/api/{controller}/{id:\d+}/{action}/");
$router->add("/api/{controller}/{id:\d+}", ["action" => "apiFindOne"]);
$router->add("/api/{controller}", ["action" => "apiFindAll"]);

$router->add("/api/{controller}/{action}");
$router->add("/{controller}/{action}");
//////////////


return $router;



//{--------------EXPLANATION--------------Router Instantiation:
// The code starts by creating an instance of a custom router class. This router class is responsible for handling incoming requests and routing them to the appropriate controller and action.

// Route Definitions:
// After creating the router object, the code defines various routes using the add method of the router object. Each route definition consists of two main parts:

// URL Pattern: This specifies the structure of the URL that the route should match. It may contain placeholders for dynamic parts of the URL.
// Route Options: This is an array that provides additional information about the route, such as the controller, action, namespace, middleware, and HTTP method associated with the route.
// Return the Router Object:
// Once all routes are defined, the router object is returned from the file. This makes the router object accessible to other parts of the application, allowing it to be used for handling incoming requests and dispatching them to the appropriate controllers and actions.

// Namespace and Controller:
// Some routes are specific to the admin section of the application. These routes are configured to use a specific namespace (Admin) for the controllers associated with them.

// Dynamic URL Segments:
// The route definitions include placeholders for dynamic parts of the URL, such as {controller} and {id:\d+}. These placeholders allow the router to match URLs with varying segments and extract relevant information from them.

// Generic Routes:
// In addition to specific routes, the code also defines some generic routes that can match a wide range of URLs. These routes typically have placeholders for controller and action segments and can be used for handling various types of requests.

// Overall, the code sets up a routing mechanism that interprets incoming URLs, matches them against predefined patterns, and dispatches them to the appropriate controllers and actions based on the defined routes.