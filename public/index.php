<?php
// Enabling strict typing mode
declare(strict_types=1);




// echo __FILE__; //output: C:\xampp\htdocs\phpcore_udemy_app_1/public/index.php  =>full path of  current file
// echo __DIR__; //output: C:\xampp\htdocs\phpcore_udemy_app_1/public  =>full path of parent of current file
// echo dirname(__DIR__); //output: C:\xampp\htdocs\phpcore_udemy_app_1 =>full path of grandparent of current file


// Defining constant ROOT_PATH as full path of grandparent of current file
define("ROOT_PATH", dirname(__DIR__));//C:\xampp\htdocs\phpcore_udemy_app_1/



//{-------------AUTOLAODER FOR CLASSES---------------
// Registering an autoloader function using spl_autoload_register(): so that we can automatically load class files on-demand, improving code organization , and reducing the need for manual require or include statements for each class.

spl_autoload_register(function (string $class_name) {
  // Constructing the path to the class file, based on the namespace convention and finally requring that class
  // echo ROOT_PATH . "/src/" . str_replace("\\", "/", $class_name) . ".php <br>";
  require ROOT_PATH . "/src/" . str_replace("\\", "/", $class_name) . ".php";
});
//#> spl_autoload_register(): This function in PHP registers a function to be called whenever a class is not found. It allows you to define a custom autoloading strategy for your classes. When PHP encounters an undefined class, it will call the registered autoload functions in the order they were defined until a class definition is found or all registered autoload functions have been called.

//#>Anonymous Function: The argument passed to spl_autoload_register() is an anonymous function. Anonymous functions (also known as closures) are functions without a specified name. They can be defined inline and passed around like any other value. In this case, the anonymous function takes a single parameter $class_name, representing the name of the class that needs to be loaded.

//#>Constructing the Class File Path: Inside the anonymous function, the path to the class file is constructed based on the namespace convention. The ROOT_PATH constant represents the root directory of the application. The $class_name is assumed to follow PSR-4 namespace convention, where backslashes \ are replaced with directory separators / and .php is appended to the end.

//#>require Statement: The constructed class file path is used in a require statement to include the class file. This statement ensures that the class definition is loaded into the script when it's needed.

//#>How it works:

//#>When PHP encounters a class name that hasn't been defined yet, it triggers the autoloader mechanism.
//#>The registered autoloader function receives the name of the class that needs to be loaded.
//#>It constructs the path to the class file based on the namespace convention.
//#>Then, it includes the class file using require.
//#>If the class file is found and included successfully, PHP continues execution with the newly loaded class definition.
//--------------------------------------------------}***

//{-------------LOADING ENVS---------------
$dotenv = new Framework\Dotenv;
$dotenv->load(ROOT_PATH . "/.env");
//--------------------------------------------------}



//{-----------SETTING ERROR HANDLER AND EXCEPTION HANDLER GlOBALLY-------------
//set_error_handler() method automatically detects any error that occurs at language level like syntax error ,spelling mistakes in class or variable names and executes the callback(closure) function here "Framework\ErrorHandler::handleError"
//here we detect the error and throw it as an "ErrorException" (here basicaly we converting error into exception)
// Setting error handler to convert errors into ErrorException objects
set_error_handler("Framework\ErrorHandler::handleError");


//set_exception_handler() method automatically detects any Exceptions that occurs in our application //when any exception in our app occurrs set_exception_handler() method runs  and executes the callback(closure) function here "Framework\ErrorHandler::handleException" 
// Setting exception handler to handle uncaught exceptions
set_exception_handler("Framework\ErrorHandler::handleException");
//---------------------------------------------------------------------}







// Requiring routes configuration from routes.php file
$router = require ROOT_PATH . "/config/routes.php";

// Requiring service container configuration from services.php file
$container = require ROOT_PATH . "/config/services.php";

$middleware = require ROOT_PATH . "/config/middleware.php";

// Creating a Dispatcher object with the router and container
$dispatcher = new Framework\Dispatcher($router, $container, $middleware);

// Creating a Request object from the global variables
$request = Framework\Request::createFromGlobals();

// Handling the request with the Dispatcher object and getting the response
$reponse = $dispatcher->handle($request);

// Sending the response to the client
$reponse->send();




