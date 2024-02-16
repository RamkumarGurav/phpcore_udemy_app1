<?php

declare(strict_types=1);
namespace App\Models;


use Framework\Model;
use PDO;

//always import PDO class using "use" whenever you use it. other you will get errors due to namespace

class Product_Display extends Model
{


  //You Can add table name manually by this line
  protected $table = "product_display";





  public function getTotal(): int
  {

    $sql = "SELECT COUNT(*) as total FROM product_display";
    $conn = $this->database->getConnection();
    $stmt = $conn->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    return (int) $row["total"];
  }

  //--------------------------------------------------}


}