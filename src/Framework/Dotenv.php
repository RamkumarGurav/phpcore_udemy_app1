<?php

declare(strict_types=1);
namespace Framework;

class Dotenv
{

  // This method loads environment variables from a given file
  public function load(string $path)
  {

    // Read the file into an array of lines
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    // print_r($lines); //Array ( [0] => DB_HOST=localhost [1] => DB_NAME=phpcore_mars_db1 [2] => DB_PORT=3307 [3] => DB_USER=root [4] => DB_PASSWORD= [5] => SHOW_ERRORS=1 )

    // Iterate over each line in the file
    foreach ($lines as $line) {
      // Split the line into two parts: the variable name and its value
      list($nameOfTheEnv, $valueOfEnv) = explode("=", $line, 2);

      // Set the environment variable in the $_ENV superglobal array
      // The environment variable name is the first part of the line, and its value is the second part
      $_ENV[$nameOfTheEnv] = $valueOfEnv;
    }
  }
}