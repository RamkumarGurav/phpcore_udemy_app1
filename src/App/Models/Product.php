<?php

declare(strict_types=1);
namespace App\Models;


use Framework\Model;
use PDO;
use PDOException;
use Exception;

//always import PDO class using "use" whenever you use it. other you will get errors due to namespace

class Product extends Model
{


  //You Can add table name manually by this line
  protected $table = "product";



  //{--------------VALIDATING INPUT DATA--------------
  protected function validate(array $data): void
  {
    if (empty($data["name"])) {

      $this->addError("name", "Name is Required");
    }


    // if (count($data['display']) === 0) {
    //   $this->addError("Display", "Select atleast one display");

    // }

  }
  //--------------------------------------------------}

  //{--------------total no. of  products--------------

  public function getTotal(): int
  {

    $sql = "SELECT COUNT(*) as total FROM product";
    $conn = $this->database->getConnection();
    $stmt = $conn->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    return (int) $row["total"];
  }



  public function findProductAllDetails(string $id): array|bool
  {
    $conn = $this->database->getConnection();
    $sql = "
    SELECT 
        product.id as product_id,product.name,product.description,product.tax,product.price,product.final_price ,product.image,
        product_category.id as product_category_id,
        product_category.category_name as category, 
        product_display.id as product_display_id,
        product_display.display_name
    FROM 
        product
    LEFT JOIN 
        product_category ON product.id = product_category.product_id
    LEFT JOIN 
        product_display ON product.id = product_display.product_id
    WHERE 
        product.id= :id
";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }




  public function createProductAndCategoryAndDisplay(string $table_name, array $data): bool|string
  {
    // $this->validate($data);

    // if (!empty($this->errors)) {
    //   return false;
    // }

    $product_data = [...$data];

    unset($product_data['display'], $product_data['category']);



    $conn = $this->database->getConnection();
    $conn->beginTransaction(); // Start transaction

    try {
      // Update product data
      $product_id = $this->createOne($table_name, $product_data);
      if ($product_id === false) {
        throw new Exception("Unable to create product ");
      }

      // Update product category
      $product_category_data = ["product_id" => $product_id, "category_name" => $data["category"]];
      $product_category_id = $this->createOne("product_category", $product_category_data);
      if ($product_category_id === false) {
        throw new Exception("Unable to create product_category_name with product id: $product_id");
      }


      // Insert new product_display entries
      foreach ($data["display"] as $display_name) {
        $data = ["product_id" => $product_id, "display_name" => $display_name];
        $product_display_id = $this->createOne("product_display", $data);

        if ($product_display_id === false) {
          throw new Exception("Unable to create product_display_name with product id: $product_id");
        }
      }


      // Commit transaction if all operations are successful
      $conn->commit();
      return $product_id;
    } catch (PDOException $e) {
      $conn->rollBack();
      echo $e->getMessage();
      echo "PDO Execptions <br>";
      return false;
    } catch (Exception $e) {
      $conn->rollBack();
      echo $e->getMessage();
      echo "Normal Exceptions <br>";
      return false;
    }
  }
  //--------------------------------------------------}




  public function updateProductAndCategoryAndDisplay(string $table_name, string $id, array $new_data): bool
  {

    $product_data = [
      "name" => $new_data["name"],
      "description" => $new_data["description"], // Ensure correct mapping of description
      "image" => $new_data["image"],
      "tax" => $new_data["tax"],
      "price" => $new_data["price"],
      "final_price" => $new_data["final_price"]
    ];


    $conn = $this->database->getConnection();
    $conn->beginTransaction(); // Start transaction

    try {
      // Update product data
      $isProductUpdated = $this->updateByColumnName($table_name, "id", (int) $id, $product_data);
      if ($isProductUpdated === false) {
        throw new Exception("Unable to update product with id: $id");
      }

      // Update product category
      $product_category_data = ["category_name" => $new_data["category"]];
      $isProductCategoryUpdated = $this->updateByColumnName("product_category", "product_id", (int) $id, $product_category_data);
      if ($isProductCategoryUpdated === false) {
        throw new Exception("Unable to update product_category_name with product id: $id");
      }

      // Delete existing product_display entries
      $isDeleted = $this->deleteByColumnName("product_display", "product_id", (int) $id);

      if ($isDeleted) {
        // Insert new product_display entries
        foreach ($new_data["display"] as $display_name) {
          $data = ["product_id" => $id, "display_name" => $display_name];
          $isCreated = $this->createOne("product_display", $data);

          if ($isCreated === false) {
            throw new Exception("Unable to create product_display_name with product id: $id");
          }
        }
      } else {
        throw new Exception("Unable to delete product_display_name with product id : $id");
      }

      // Commit transaction if all operations are successful
      $conn->commit();
      return true;
    } catch (PDOException $e) {
      $conn->rollBack();
      echo $e->getMessage();
      echo "PDO Execptions <br>";
      return false;
    } catch (Exception $e) {
      $conn->rollBack();
      echo $e->getMessage();
      echo "Normal Exceptions <br>";
      return false;
    }

  }





}

