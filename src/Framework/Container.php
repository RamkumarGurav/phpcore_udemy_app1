<?php
declare(strict_types=1);

namespace Framework;

use ReflectionClass;
use Closure;
use ReflectionNamedType;
use InvalidArgumentException;

class Container
{

  // Array to store registered services
  public array $registry = [];

  // Method to register a service in the container
  public function set(string $name, Closure $value): void
  {

    // echo "I am inside Container's set method <br>";

    // Store the service closure in the registry array
    $this->registry[$name] = $value;

  }


  // Method to retrieve a service from the container
  public function get(string $class_name): object
  {


    // Check if the service is already registered
    if (array_key_exists($class_name, $this->registry)) {

      // If so, call the closure to instantiate and return the service
      return $this->registry[$class_name]();

    }

    // If the service is not registered, try to instantiate it using reflection
    // Create a ReflectionClass object for the specified class
    $reflector = new ReflectionClass($class_name);

    // Get the constructor method of the class
    $contructor = $reflector->getConstructor(); //this returns null if the class has no constructor method

    // If the class has no constructor, instantiate and return it directly
    if ($contructor === null) {

      return new $class_name;
    }

    // If the class has a constructor, resolve its dependencies
    // Array to store resolved dependencies
    $dependencies = [];


    // Get the parameters of the constructor method
    $parameters = $contructor->getParameters();

    // Loop through the constructor parameters
    foreach ($parameters as $parameterObj) {
      // Get the type hint of the parameter
      $type = $parameterObj->getType();

      // If the parameter has no type hint, throw an exception
      if ($type === null) {
        throw new InvalidArgumentException("Constructor parameter '{$parameterObj->getName()}' in the $class_name class has no type declaration");
      }


      // If the parameter type is not a named type, throw an exception
      if (!($type instanceof ReflectionNamedType)) {
        throw new InvalidArgumentException("Constructor parameter '{$parameterObj->getName()}' in the $class_name is an invalid type: '$type' - only single named types supported");
      }

      // If the parameter type is a built-in type like string or int, throw an exception
      if ($type->isBuiltin()) {
        throw new InvalidArgumentException("Unable to Resolve constructor parameter '{$parameterObj->getName()}' of type '$type' in the $class_name class");
      }

      // Recursively resolve the dependency and add it to the dependencies array
      $dependencies[] = $this->get((string) $type);
    }

    // Instantiate the class with resolved dependencies and return it
    return new $class_name(...$dependencies);
  }
}



//{--------------Explaination--------------
//   The Container class is responsible for managing and resolving dependencies.
// The registry property is an array used to store registered services.
// The set() method registers a service in the container. It accepts a service name and a closure that returns an instance of the service.
// The get() method retrieves a service from the container. It accepts the name of the service to retrieve.
// If the requested service is already registered in the registry, it simply calls the closure associated with the service and returns the result.
// If the requested service is not registered, it attempts to instantiate it using reflection.
// It retrieves the constructor method of the class using reflection.
// If the class has no constructor, it instantiates and returns the class directly.
// If the class has a constructor, it resolves its dependencies by recursively calling get() for each parameter type hinted in the constructor.
// It then instantiates the class with resolved dependencies and returns it