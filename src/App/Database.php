<?php
declare(strict_types=1);

namespace App;

use PDO;

class Database
{
  // Declare properties to store database connection details
  private ?PDO $pdo = null; // Initialize PDO object as null

  // Constructor to initialize database connection details
  public function __construct(
    private string $host,    // Database host
    private string $dbname,  // Database name
    private string $port,    // Database port
    private string $user,    // Database user
    private string $password // Database password
  ) {
    // echo "I am inside  Database's __construct method <br>";
  }

  // Method to get database connection
  public function getConnection(): PDO
  {
    // Check if PDO object is not already created
    if ($this->pdo === null) {
      // Construct Data Source Name (DSN) string for PDO connection
      $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8;port={$this->port}";

      // Create PDO object and assign it to $pdo property
      // Set PDO options for error handling
      $this->pdo = new PDO(
        $dsn,                   // DSN string
        $this->user,            // Database user
        $this->password,        // Database password
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] // PDO options: error mode
      );
    }

    // Return the PDO object for database connection
    return $this->pdo;
  }
}
