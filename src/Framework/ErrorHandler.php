<?php
declare(strict_types=1);

namespace Framework;

use ErrorException;
use Throwable;
use Framework\Exceptions\PageNotFoundException;

class ErrorHandler
{
  // Method to handle PHP errors
  public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool
  {
    // echo "I am inside ErrorHandler's handleError method <br>";
    // Convert PHP errors to ErrorException objects and throw them
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);

  }

  // Method to handle exceptions
  public static function handleException(Throwable $exception): void
  {
    // echo "I am inside ErrorHandler's handleException method <br>";

    // Handle specific types of exceptions differently
    if ($exception instanceof PageNotFoundException) {
      // Set HTTP response code to 404 for PageNotFoundException
      http_response_code(404);
      // Set the template to display for 404 errors
      $template = "404.php";
    } else {
      // Set HTTP response code to 500 for other exceptions
      http_response_code(500);
      // Set the template to display for 500 errors
      $template = "500.php";
    }

    // If SHOW_ERRORS environment variable is true, display errors directly
    if ($_ENV["SHOW_ERRORS"]) {
      // Turn on displaying errors
      ini_set("display_errors", "1");
    } else {
      // Turn off displaying errors
      ini_set("display_errors", "0");
      // Log errors to PHP error log
      ini_set("log_errors", "1");
      // Require the error template file to display to the user
      require dirname(__DIR__, 2) . "/views/$template";
    }

    // Re-throw the exception to ensure it's handled by the global exception handler
    // In the handleException() method of  ErrorHandler class, after handling the exception based on its type and performing necessary actions (such as setting HTTP response code, displaying error template, etc.), you might re-throw the exception to ensure it's eventually caught by the global exception handler that is in frontcontroller
    // echo "throw exception <br>";
    throw $exception;
  }

}