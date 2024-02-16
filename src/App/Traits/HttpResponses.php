<?php
namespace App\Traits;


trait HttpResponses
{

  protected function success($data, $message = "Request Successful", int $code = 200)
  {


    // Set the appropriate HTTP header for the status code
    http_response_code($code);
    header('Content-Type: application/json');

    $jsonResponse = json_encode(["status" => "TRUE", "statusCode" => $code, "message" => $message, "data" => $data]);


    // Output the JSON response
    return $jsonResponse;
  }


  protected function error($data, $message = "Request Failed", $error = "", int $code = 500)
  {


    // Set the appropriate HTTP header for the status code
    http_response_code($code);
    header('Content-Type: application/json');

    $jsonResponse = json_encode(["status" => "FALSE", "statusCode" => $code, "message" => $message, "error" => $error, "data" => $data]);


    // Output the JSON response
    return $jsonResponse;
  }


}