<?php

declare(strict_types=1);
namespace Framework;

use App\Database;
use Exception;
use PDO;
use PDOException;

//always import PDO class using "use" whenever you use it. otherwise you will get errors due to namespace

// Define an abstract class Model which serves as the base class for other model classes.
abstract class Model
{
  protected $table; // Property to store the database table name.
  protected array $errors = []; // Property to store validation errors.

  // Constructor that injects an instance of the Database class.
  public function __construct(protected Database $database)
  {


    // echo "I am inside abstract Model's __construct method <br>";

  }

  //for those models which don't have validate funcitons 
  protected function validate(array $data): void
  {
  }



  //{--------------HELPERS--------------
  //{--------------------------------------

  // Method to retrieve the last inserted ID.
  public function getInsertID(): string
  {

    $conn = $this->database->getConnection();
    return $conn->lastInsertId();
  }


  // Method to dynamically retrieve the table name based on the class name.
  public function getTable(): string
  {
    // Check if the table name has already been set.
    if ($this->table !== null) {
      return $this->table; // If set, return the stored table name.
    }

    // If the table name is not set, extract the class name using "::class".
    // "::class" returns the fully qualified class name as a string.
    //if my model classname is "Product" then my table name will be "product"
    $parts = explode("\\", $this::class); // Split the class name by backslashes to separate namespaces.

    // Retrieve the last part of the namespace, which corresponds to the class name.
    $className = array_pop($parts);

    // Convert the class name to lowercase to match database naming conventions.
    return strtolower($className);
  }

  // Method to add errors to the errors array.
  protected function addError(string $field, string $message): void
  {
    $this->errors[$field] = $message;
  }

  // Method to retrieve validation errors.
  public function getErrors(): array
  {
    return $this->errors;
  }


  public function calculateFinalPrice(float $price, float $taxRate): float
  {
    $finalPrice = $price * ((100 + $taxRate) / 100);
    // Format the final price to 2 decimal places
    $formattedPrice = number_format($finalPrice, 2);
    // Convert the formatted price back to a float
    return (float) $formattedPrice;
  }

  //--------------------------------------------------}
  //--------------------------------------------------}



  //{--------------FIND ALL--------------


  public function findAll(string $table_name, int $limit = null, int $offset = 0): array
  {
    // Get database connection
    $conn = $this->database->getConnection();

    // Build the SQL query with pagination
    $sql = "SELECT * FROM $table_name";
    if ($limit !== null) {
      $sql .= " LIMIT $limit OFFSET $offset";
    }

    // Execute the query
    $stmt = $conn->query($sql);

    // Fetch and return the results as an associative array
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  //--------------------------------------------------}




  //{--------------FIND ONE--------------

  public function find(string $id, string $table_name): array|bool
  {

    // echo "I am inside abstract Model's find method <br>";

    $conn = $this->database->getConnection();

    $sql = "SELECT * FROM $table_name where id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }


  public function findByColumnName(string $table_name, string $columnName, string|int $columnValue): array|bool
  {

    // echo "I am inside abstract Model's find method <br>";

    $conn = $this->database->getConnection();

    $sql = "SELECT * FROM $table_name where $columnName=:c_value";
    $stmt = $conn->prepare($sql);

    $type = match (gettype($columnValue)) {
      "boolean" => PDO::PARAM_BOOL,
      "integer" => PDO::PARAM_INT,
      "NULL" => PDO::PARAM_NULL,
      default => PDO::PARAM_STR
    };

    $stmt->bindValue(":c_value", $columnValue, $type);

    if ($stmt->execute()) {
      return $stmt->fetch(PDO::FETCH_ASSOC);

    } else {
      return false;
    }

  }
  public function findAllByColumnName(string $table_name, string $columnName, string|int $columnValue): array|bool
  {

    // echo "I am inside abstract Model's find method <br>";

    $conn = $this->database->getConnection();

    $sql = "SELECT * FROM $table_name where $columnName=:c_value";
    $stmt = $conn->prepare($sql);

    $type = match (gettype($columnValue)) {
      "boolean" => PDO::PARAM_BOOL,
      "integer" => PDO::PARAM_INT,
      "NULL" => PDO::PARAM_NULL,
      default => PDO::PARAM_STR
    };

    $stmt->bindValue(":c_value", $columnValue, $type);

    if ($stmt->execute()) {
      return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {
      return false;
    }

  }

  // public function findAllBy(string $column, string $id, string $table_name): array|bool
  // {

  //   // echo "I am inside abstract Model's find method <br>";

  //   $conn = $this->database->getConnection();

  //   $sql = "SELECT * FROM $table_name where $column=:id";
  //   $stmt = $conn->prepare($sql);
  //   $stmt->bindValue(":id", $id, PDO::PARAM_INT);
  //   $stmt->execute();

  //   return $stmt->fetchAll(PDO::FETCH_ASSOC);
  // }






  //{--------------CREATE--------------


  public function createOne(string $table_name, array $data): bool|string
  {
    // $this->validate($data);

    // if (!empty($this->errors)) {
    //   return false;
    // }


    $columns = implode(",", array_keys($data));
    $placeholders = implode(",", array_fill(0, count($data), "?"));
    $sql = "INSERT INTO $table_name ($columns) VALUES($placeholders)";

    $conn = $this->database->getConnection();
    $stmt = $conn->prepare($sql);

    $i = 1;
    foreach ($data as $value) {
      $type = match (gettype($value)) {
        "boolean" => PDO::PARAM_BOOL,
        "integer" => PDO::PARAM_INT,
        "NULL" => PDO::PARAM_NULL,
        default => PDO::PARAM_STR
      };
      $stmt->bindValue($i, $value, $type);
      $i++;
    }

    if ($stmt->execute()) {
      return $conn->lastInsertId();
    } else {
      return false;
    }
  }









  // public function create(string $table_name, array $data): bool|string
  // {
  //   // $this->validate($data);

  //   // if (!empty($this->errors)) {
  //   //   return false;
  //   // }

  //   $product_data = [...$data];

  //   unset($product_data['display'], $product_data['category']);



  //   $conn = $this->database->getConnection();
  //   $conn->beginTransaction(); // Start transaction

  //   try {
  //     // Update product data
  //     $product_id = $this->createOne($table_name, $product_data);
  //     if ($product_id === false) {
  //       throw new Exception("Unable to create product ");
  //     }

  //     // Update product category
  //     $product_category_data = ["product_id" => $product_id, "category_name" => $data["category"]];
  //     $product_category_id = $this->createOne("product_category", $product_category_data);
  //     if ($product_category_id === false) {
  //       throw new Exception("Unable to create product_category_name with product id: $product_id");
  //     }


  //     // Insert new product_display entries
  //     foreach ($data["display"] as $display_name) {
  //       $data = ["product_id" => $product_id, "display_name" => $display_name];
  //       $product_display_id = $this->createOne("product_display", $data);

  //       if ($product_display_id === false) {
  //         throw new Exception("Unable to create product_display_name with product id: $product_id");
  //       }
  //     }


  //     // Commit transaction if all operations are successful
  //     $conn->commit();
  //     return $product_id;
  //   } catch (PDOException $e) {
  //     $conn->rollBack();
  //     echo $e->getMessage();
  //     echo "PDO Execptions <br>";
  //     return false;
  //   } catch (Exception $e) {
  //     $conn->rollBack();
  //     echo $e->getMessage();
  //     echo "Normal Exceptions <br>";
  //     return false;
  //   }
  // }
  //--------------------------------------------------}

  //{--------------UPDATE--------------


  public function updateByColumnName(string $table_name, string $columnName, string|int $columnValue, array $data): bool
  {
    // $this->validate($data);

    // if (!empty($this->errors)) {
    //   return false;
    // }


    // echo "inside  updateByColumnName for table $table_name $columnName $columnValue <br>";
    // print_r($data);
    // exit;
    $sql = "UPDATE $table_name ";

    $assignments = array_keys($data);
    array_walk($assignments, function (&$value) {
      $value = "$value = ?";
    });

    $sql .= "SET " . implode(", ", $assignments) . " WHERE $columnName = ?";



    $conn = $this->database->getConnection();

    $stmt = $conn->prepare($sql);

    $i = 1;
    foreach ($data as $value) {

      $type = match (gettype($value)) {

        "boolean" => PDO::PARAM_BOOL,
        "integer" => PDO::PARAM_INT,
        "NULL" => PDO::PARAM_NULL,
        default => PDO::PARAM_STR

      };

      $stmt->bindValue($i, $value, $type);
      $i++;

    }

    $type = match (gettype($columnValue)) {

      "boolean" => PDO::PARAM_BOOL,
      "integer" => PDO::PARAM_INT,
      "NULL" => PDO::PARAM_NULL,
      default => PDO::PARAM_STR

    };

    echo "type is $type <br>";
    $stmt->bindValue($i, $columnValue, $type);

    return $stmt->execute();
  }











  public function update(string $table_name, string $id, array $new_data): bool
  {

    // $product_data = [
    //   "name" => $new_data["name"],
    //   "description" => $new_data["description"], // Ensure correct mapping of description
    //   "image" => $new_data["image"],
    //   "tax" => $new_data["tax"],
    //   "price" => $new_data["price"],
    //   "final_price" => $new_data["final_price"]
    // ];


    $conn = $this->database->getConnection();
    $conn->beginTransaction(); // Start transaction

    try {
      // Update product data
      $isProductUpdated = $this->updateByColumnName($table_name, "id", (int) $id, $new_data);
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
  //--------------------------------------------------}



  //{--------------DELETE--------------

  public function delete(string $id, string $table_name): bool
  {
    $sql = "DELETE FROM $table_name WHERE id = :id";

    $conn = $this->database->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    return $stmt->execute();
  }




  public function deleteByColumnName(string $table_name, string $columnName, string|int $columnValue, ): bool
  {

    // echo "inside deleteByColumnName for table $table_name with productid: columnValue ";
    // echo " <br>";

    $sql = "DELETE FROM $table_name WHERE $columnName = :id";

    $conn = $this->database->getConnection();
    $stmt = $conn->prepare($sql);

    $type = match (gettype($columnValue)) {

      "boolean" => PDO::PARAM_BOOL,
      "integer" => PDO::PARAM_INT,
      "NULL" => PDO::PARAM_NULL,
      default => PDO::PARAM_STR

    };
    $stmt->bindValue(":id", $columnValue, $type);

    return $stmt->execute();
  }




  public function deleteAllByIds(string $table_name, array $ids)
  {


    $conn = $this->database->getConnection();
    $conn->beginTransaction(); // Start transaction

    try {
      foreach ($ids as $id) {

        $isDeleted = $this->deleteByColumnName($table_name, "id", $id);
        if ($isDeleted === false) {
          throw new Exception("Unable to delete resource with Id : $id");
        }

      }


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

  //--------------------------------------------------}

}

