<?php

declare(strict_types=1);

namespace Framework;

abstract class Controller
{

  protected Request $request;
  protected Response $response;
  protected TemplateViewerInterface $viewer;

  public function setRequest(Request $request): void
  {
    $this->request = $request;
  }

  public function setResponse(Response $response): void
  {
    $this->response = $response;
  }

  public function setViewer(TemplateViewerInterface $viewer): void
  {
    $this->viewer = $viewer;
  }


  protected function view(string $template, array $data = []): Response
  {
    $this->response->setResponseBody($this->viewer->render($template, $data));

    return $this->response;
  }


  protected function redirect(string $url): Response
  {
    $this->response->redirect($url);
    return $this->response;
  }


}


//{--------------EXPLANATION--------------
// This is an abstract Controller class within the Framework namespace. It provides basic functionality for handling requests and generating responses in a web application. Here's a brief explanation of its key components:

//   Properties:

//   $request: Stores the incoming request object.
//   $response: Stores the outgoing response object.
//   $viewer: Stores an instance of the template viewer interface for rendering views.

//   Methods:

//   setRequest(Request $request): Sets the incoming request object.
//   setResponse(Response $response): Sets the outgoing response object.
//   setViewer(TemplateViewerInterface $viewer): Sets the template viewer object.
//   view(string $template, array $data = []): Response: Renders a view template using the template viewer and sets it as the response body.
//   redirect(string $url): Response: Redirects the user to a specified URL by setting the appropriate headers in the response object.

// This class serves as a foundation for creating specific controllers that handle different routes and actions within the application.