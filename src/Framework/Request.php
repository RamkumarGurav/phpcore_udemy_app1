<?php
declare(strict_types=1);

namespace Framework;


class Request
{

  public function __construct(
    public string $uri,
    public string $method,
    public array $get,
    public array $post,
    public array $files,
    public array $cookie,
    public array $server,

  ) {
  }

  public static function createFromGlobals()
  {
    return new static(
      $_SERVER["REQUEST_URI"],
      $_SERVER["REQUEST_METHOD"],
      $_GET,
      $_POST,
      $_FILES,
      $_COOKIE,
      $_SERVER,
    );
  }
}


//{--------------EXPLANATION--------------
// The Request class represents an HTTP request and provides access to request-related data such as URI, method, parameters, and server information. It has properties to store this data and a static method createFromGlobals() to create a Request object using PHP's global variables.