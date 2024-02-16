<?= $this->extend("layouts\default.php") ?>

<?= $this->section("title") ?>Products
<?= $this->endSection() ?>


<?= $this->section("body") ?>
<div class="container">
  <h1 class="h1 text-danger">Products</h1>
  <h1 class="h2 text-primary">Total:
    <?= $total ?? "" ?>
  </h1>

  <div class="text-end">
    <a href="/phpcore_udemy_app_1/products/new" class="btn btn-primary mb-3">+ New Product</a>

  </div>

  <div class="">

    <?php foreach ($products as $product): ?>
      <div class="d-flex gap-2 align-items-center mb-3 shadow pe-4">
        <img src="<?= $product['image'] ?? "" ?>" class="img-fluid" style="width:100px;height:auto;object-fit:cover;"
          alt="<?= $product['name'] ?? "" ?>">
        <h5 class="card-title me-auto">
          <?= $product['name'] ?? "" ?>
        </h5>

        <a href="/phpcore_udemy_app_1/products/<?= $product['id'] ?? "" ?>/show" class="btn btn-primary">View</a>
        <a href="/phpcore_udemy_app_1/products/<?= $product['id'] ?? "" ?>/edit" class="btn btn-warning">Edit</a>
        <input type="checkbox" name="product_ids[]" value="<?= $product['id'] ?? "" ?>"
          class="form-check-input custom-checkbox">

      </div>
    <?php endforeach; ?>
    <div class="text-end">
      <button onclick="deleteSelectedProducts()" class="btn btn-danger mb-3 ms-auto">Delete Selected</button>

    </div>

  </div>
</div>

<script>
  function deleteSelectedProducts() {
    var productIds = [];
    var checkboxes = document.querySelectorAll('input[name="product_ids[]"]:checked');
    checkboxes.forEach(function (checkbox) {
      productIds.push(checkbox.value);
    });

    if (productIds.length > 0) {

      console.log(JSON.stringify({ product_ids: productIds }));
      // Send AJAX request to delete selected products
      fetch('http://localhost/phpcore_udemy_app_1/products/deleteAll', {
        method: 'POST',
        body: JSON.stringify({ product_ids: productIds }),
        headers: {
          'Content-Type': 'application/json'
        }
      }).then(response => {
        if (response.ok) {
          // Refresh the page or update the UI
        }
      });
    } else {
      alert('Please select at least one product to delete.');
    }
  }
</script>

<?= $this->endSection() ?>

