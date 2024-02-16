<?php

return [
  "message" => \App\Middlewares\ChangeResponseExample::class,
  "trim" => \App\Middlewares\ChangeRequestExample::class,
  "redirect" => \App\Middlewares\RedirectExample::class
];


//{--------------EXPLANATION--------------
// The code returns an array where each key represents the name of a middleware, and the corresponding value is the fully qualified class name of that middleware.
// For instance, if a route or application component needs to use a middleware called "message", it can retrieve its corresponding class name from this configuration array using the key "message". This allows the application to dynamically instantiate and utilize the middleware without hardcoding its class name.