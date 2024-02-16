<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use Framework\Exceptions\PageNotFoundException;
use Framework\Controller;
use App\Traits\HttpResponses;
use Exception;
use Framework\Response;

class Product_Displays extends Controller
{

  use HttpResponses;


  public function __construct(private Product $model)
  {
  }




}