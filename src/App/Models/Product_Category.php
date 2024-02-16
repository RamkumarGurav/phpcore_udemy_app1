<?php

declare(strict_types=1);
namespace App\Models;


use Framework\Model;
use PDO;

//always import PDO class using "use" whenever you use it. other you will get errors due to namespace

class Product_Category extends Model
{


  //You Can add table name manually by this line
  protected $table = "product_category";




  //--------------------------------------------------}

  //{--------------total no. of  product_Categorys--------------

  public function getTotal(): int
  {

    $sql = "SELECT COUNT(*) as total FROM product_category";
    $conn = $this->database->getConnection();
    $stmt = $conn->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    return (int) $row["total"];
  }

  //--------------------------------------------------}


}