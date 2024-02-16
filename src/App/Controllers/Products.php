<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use Framework\Exceptions\PageNotFoundException;
use Framework\Controller;
use App\Traits\HttpResponses;
use Exception;
use Framework\Response;

class Products extends Controller
{

  use HttpResponses;


  public function __construct(private Product $model)
  {
  }


  //{--------------API--------------
  public function apiFindAll()
  {
    /// Retrieve all products from the model

    try {
      $products = $this->model->findAll($this->model->getTable());
      echo $this->success($products);

    } catch (Exception $e) {
      echo $this->error(null, );
    }


  }

  public function apiFindOne($id)
  {
    /// Retrieve all products from the model

    try {
      $product = $this->model->find($id, $this->model->getTable());
      if ($product === false) {
        echo $this->error($product, "There is NO product with ID: $id", "", 404);
      } else {
        echo $this->success($product);

      }

    } catch (Exception $e) {
      echo $this->error(null, );
    }


  }

  //--------------------------------------------------}



  //{-----------HELPERS--------------------



  private function getProduct(string $id, string $table_name)
  {
    $product = $this->model->find($id, $table_name);

    //if find is false which means there is no id in the database so that's why when we execute the query with invalid id we get false as result
    if ($product === false) {
      throw new PageNotFoundException("Product not found");
    }

    return $product;
  }



  public function convertToBase64Uri(string $rawdata): string
  {
    $base64ImageData = base64_encode($rawdata);

    // Determine the MIME type of the image (e.g., JPEG, PNG)
    $imageMimeType = 'image/jpeg'; // Adjust this based on the actual MIME type of your image

    // Construct the data URI for the image
    return 'data:' . $imageMimeType . ';base64,' . $base64ImageData;

  }



  public function calculateFinalPrice(float $price, float $taxRate): float
  {
    $finalPrice = (float) $price * ((100 + $taxRate) / 100);
    // Format the final price to 2 decimal places
    $formattedPrice = round($finalPrice, 2);
    // Convert the formatted price back to a float
    return (float) $formattedPrice;
  }


  public function formattedDataForPage($id)
  {
    $result = $this->model->findProductAllDetails($id);

    $display_names = [];
    $product_display_ids = [];
    foreach ($result as &$product) {


      $display_names[$product["display_name"]] = $product["display_name"];
      $product_display_ids[] = $product["product_display_id"];

      unset($product["display_name"]);
      unset($product["product_display_id"]);
    }

    $result = $result[0];
    $result["display"] = $display_names;
    $result["product_display_ids"] = $product_display_ids;

    return $result;
  }

  //------------FRONTEND--------------------------------------}

  //{--------------SHOW ALL--------------


  public function index(): Response
  {
    $queryArray = [];
    $queryString = parse_url($this->request->uri, PHP_URL_QUERY);
    parse_str($queryString ?? "page=1&limit=2", $queryArray);

    // Get the current page from the query parameters or default to page 1
    $currentPage = isset($queryArray['page']) ? intval($queryArray['page']) : 1;

    // Set the pagination limit (number of products per page)
    $perPage = isset($queryArray['limit']) ? intval($queryArray['limit']) : 2;

    // Get total number of products
    $totalProducts = $this->model->getTotal();

    // Calculate total number of pages
    $totalPages = ceil($totalProducts / $perPage);

    // Calculate offset for pagination
    $offset = ($currentPage - 1) * $perPage;

    // Get products for the current page
    $products = $this->model->findAll($this->model->getTable(), $perPage, $offset);

    return $this->view("Products/index.mvc.php", [
      "products" => $products,
      "total" => $this->model->getTotal(),
      "query" => $queryArray,
      "totalPages" => $totalPages,
      "currentPage" => $currentPage,
      "limit" => $perPage,
    ]);
  }
  //--------------------------------------------------}




  //{--------------SHOW SINGLE--------------

  public function show($id): Response
  {

    $result = $this->model->findProductAllDetails($id);

    $display_names = [];
    foreach ($result as &$product) {


      $display_names[] = $product["display_name"];
      unset($product["display_name"]);
    }

    $result = $result[0];
    $result["display"] = $display_names;







    return $this->view("Products/show.mvc.php", ["product" => $result]);
  }
  //--------------------------------------------------}








  //{--------------CREATE--------------

  //frontend
  public function new(): Response
  {
    $display = [];
    return $this->view("Products/new.mvc.php", ["display" => $display]);
  }



  //backend
  public function create(): Response
  {

    $imageFile = $this->request->files["image"]["tmp_name"];
    $imageData = null;
    if (!empty($imageFile)) {
      $imageData = file_get_contents($imageFile);
      $imageData = $this->convertToBase64Uri($imageData);
    }




    if (array_key_exists("display", $this->request->post)) {
      $display = $this->request->post["display"];
    } else {
      $display = [];
    }



    $data = [
      "name" => $this->request->post["name"],
      "description" => empty($this->request->post["description"]) ? null : $this->request->post["description"],
      "tax" => $this->request->post["tax"],
      "price" => $this->request->post["price"],
      "final_price" => $this->calculateFinalPrice((float) $this->request->post["price"], (float) $this->request->post["tax"]),
      "category" => $this->request->post["category"],
      "display" => $display,
      "image" => $imageData

    ];


    $product_id = $this->model->createProductAndCategoryAndDisplay($this->model->getTable(), $data);
    if ($product_id != false) {

      return $this->redirect("/phpcore_udemy_app_1/products/$product_id/show");
    } else {
      return $this->view("Products/new.mvc.php", ["errors" => $this->model->getErrors()]);
    }


  }
  //--------------------------------------------------}

  //{--------------UPDATE--------------



  public function edit(string $id): Response
  {

    $product_details = $this->formattedDataForPage($id);



    // print_r($product_details);
    // exit;


    return $this->view("Products/edit.mvc.php", ["product" => $product_details]);
  }


  public function update(string $id): Response
  {


    $product = $this->getProduct($id, "product");


    $imageFile = $this->request->files["image"]["tmp_name"];
    $imageData = null;
    if (!empty($imageFile)) {
      $imageData = file_get_contents($imageFile);
      $imageData = $this->convertToBase64Uri($imageData);
    } else {
      $imageData = $product["image"];
    }




    if (array_key_exists("display", $this->request->post)) {
      $display = $this->request->post["display"];
    } else {
      $display = [];
    }



    $new_data = [
      "name" => $this->request->post["name"],
      "description" => empty($this->request->post["description"]) ? null : $this->request->post["description"],
      "tax" => $this->request->post["tax"],
      "price" => $this->request->post["price"],
      "final_price" => $this->calculateFinalPrice((float) $this->request->post["price"], (float) $this->request->post["tax"]),
      "category" => $this->request->post["category"],
      "display" => $display,
      "image" => $imageData,
      "product_id" => $id
    ];





    if ($this->model->updateProductAndCategoryAndDisplay($this->model->getTable(), $id, $new_data)) {

      return $this->redirect("/phpcore_udemy_app_1/products/$id/show");
    } else {


      echo "something went wrong final";

      return $this->view("Products/edit.mvc.php", ["product" => $new_data, "errors" => $this->model->getErrors()]);
    }


  }

  //--------------------------------------------------}



  //{--------------DELETE--------------

  public function delete(string $id): Response
  {

    $product = $this->getProduct($id, $this->model->getTable());

    return $this->view("Products/delete.mvc.php", ["product" => $product]);



  }


  public function destroy(string $id): Response
  {

    $product = $this->getProduct($id, $this->model->getTable());

    $this->model->delete($id, $this->model->getTable());
    return $this->redirect("/phpcore_udemy_app_1/products/index");


  }

  public function destroyall(): Response
  {
    if (isset($_POST['product_ids'])) {
      $product_ids = $this->request->post["product_ids"];

      // var_dump($this->request->post["product_ids"]);
      // exit;

      foreach ($product_ids as $id) {
        $this->model->delete($id, $this->model->getTable());

      }

      // exit($product_ids);

      return $this->redirect("/phpcore_udemy_app_1/products/index");
    } else {
      return $this->redirect("/phpcore_udemy_app_1/products/index");
    }

  }



  //--------------------------------------------------}



  public function responseCodeExample(): Response
  {
    $this->response->setSatusCode(451);
    $this->response->setResponseBody("Unavailable for legal reasons.");

    return $this->response;

  }





}