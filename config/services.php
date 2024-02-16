<?php

// echo "I am inside  servieces.php <br>";

$container = new Framework\Container;


//we are setting the database obje in the registry because database class constructor has some primitive arguements and 
//so which gives error bcz container try to create object using data type of the parameter like data type of host is string //if we already inject the database object to container we can stop this 
// App\Database::class -> this gives the classname of the "App\Database"
$container->set(App\Database::class, function () {
  // echo "I am inside  servieces.php and container's set method<br>";

  return new App\Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_PORT"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
});



$container->set(
  Framework\TemplateViewerInterface::class,
  function () {

    return new Framework\MVCTemplateViewer;
  }
);
return $container;




//{--------------EXPLANATION--------------
//   Container Instantiation:
// The code begins by creating an instance of a container object. In modern PHP development, containers are often used for managing dependencies and services within an application. The container acts as a registry where objects can be stored and retrieved by other parts of the application.

// Database Service Registration:
// The container's set method is then used to register a service responsible for creating and managing database connections. The service is registered with the class name App\Database::class. This means that whenever another part of the application requests an object of type App\Database, the container will invoke the provided callback function to create and return an instance of the database object.

// Callback Function for Database Service:
// The callback function provided to the set method is responsible for creating and configuring the database object. It retrieves database connection configuration parameters from environment variables ($_ENV) and uses them to instantiate a new App\Database object. This ensures that the database object is created with the correct configuration every time it is requested from the container.

// Template Viewer Service Registration:
// Similarly, another service is registered for managing templates. It is registered with the interface name Framework\TemplateViewerInterface::class, which indicates that the container will return an object that implements this interface whenever it is requested. The callback function simply returns a new instance of Framework\MVCTemplateViewer, which presumably implements the TemplateViewerInterface.

// Return the Container Object:
// Finally, the container object is returned from the file. This makes the container and the services it manages accessible to other parts of the application, allowing them to retrieve and use the registered services as needed.

// Overall, this code sets up a dependency injection container and registers two services: one for managing database connections and another for handling templates. These services can then be easily accessed and utilized throughout the application.
//--------------------------------------------------}




