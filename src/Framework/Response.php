<?php


declare(strict_types=1);

namespace Framework;

class Response
{


  private string $responseBody = "";
  private array $headers = [];
  private int $statusCode = 0;



  public function setResponseBody(string $responseBody): void
  {
    $this->responseBody = $responseBody;
  }

  public function getResponseBody(): string
  {
    return $this->responseBody;
  }

  public function addHeader(string $header): void
  {
    $this->headers[] = $header;
  }



  public function setSatusCode(int $code): void
  {
    $this->statusCode = $code;
  }

  public function redirect(string $url): void
  {
    $this->addHeader("Location: $url");
  }




  public function send(): void
  {

    if ($this->statusCode) {
      http_response_code($this->statusCode);
    }


    foreach ($this->headers as $header) {
      header($header);
    }

    echo $this->responseBody;
  }

}


//{--------------EXPLANATION--------------
//   The class allows setting the response body, adding headers, setting the status code, and performing redirection.
// When the send() method is called, it first sets the HTTP status code using http_response_code() if a status code is set.
// Then, it loops through the headers added using addHeader() and sets each header using the header() function.
// Finally, it echoes the response body.