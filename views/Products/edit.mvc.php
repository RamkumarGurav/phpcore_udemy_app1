{% extend "layouts\default.php" %}


{% section title %} Edit Product {% endSection %}




{% section body %}


<div class="container mt-5">
  <h1 class="h2 mb-4">Edit Product</h1>
  <form action="/phpcore_udemy_app_1/products/{{ product['product_id'] }}/update" method="post"
    enctype="multipart/form-data">
    <div class="mb-3">
      <label for="name" class="form-label">Product Name</label>
      <input type="text" class="form-control" id="name" name="name" value="{{ product['name'] }}" required>
      {% if (isset($errors["name"])): %}
      <p class="text-danger">* {{ errors["name"] }}</p>
      {% endif; %}
    </div>
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea class="form-control" id="description" name="description"
        rows="5">{{ product['description'] }}</textarea>
    </div>
    <div class="mb-3">
      <label for="category" class="form-label">Category</label>
      <select class="form-select" id="category" name="category" required>
        <option value="">Select Category</option>
        <option value="electronics" <?= $product['category'] == 'electronics' ? 'selected' : '' ?>>Electronics</option>
        <option value="clothing" <?= $product['category'] == 'clothing' ? 'selected' : '' ?>>Clothing</option>
        <option value="home" <?= $product['category'] == 'home' ? 'selected' : '' ?>>Home</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Tax</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="tax" id="noTax" value="0" <?= $product['tax'] == '0' ? 'checked' : '' ?>>
        <label class="form-check-label" for="noTax">No Tax</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="tax" id="tax12" value="12" <?= $product['tax'] == '12' ? 'checked' : '' ?>>
        <label class="form-check-label" for="tax12">12% Tax</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="tax" id="tax10" value="10" <?= $product['tax'] == '10' ? 'checked' : '' ?>>
        <label class="form-check-label" for="tax10">10% Tax</label>
      </div>
    </div>
    <div class="mb-3">
      <label for="price" class="form-label">Price</label>
      <input type="number" class="form-control" id="price" name="price" value="<?= $product['price'] ?>" required>
    </div>
    <div class="mb-3">
      <label for="final_price" class="form-label">Final Price</label>
      <input type="number" class="form-control" id="final_price" name="final_price" readonly value="{{
        product['final_price'] }}">
    </div>
    <div class="mb-3">
      <label class="form-check-label">Display</label><br>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="new_Product" id="new_Product" name="display[]" <?=
          array_key_exists("new_Product", $product['display']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="new_Product">New Product</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="most_Viewed" id="most_Viewed" name="display[]" <?=
          array_key_exists("most_Viewed", $product['display']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="most_Viewed">Most Viewed</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="top_Seller" id="top_Seller" name="display[]" <?=
          array_key_exists("top_Seller", $product['display']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="top_Seller">Top Seller</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="most_Popular" id="most_Popular" name="display[]" <?=
          array_key_exists("most_Popular", $product['display']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="most_Popular">Most Popular</label>
      </div>

      {% if (isset($errors["display"])): %}

      <p class="text-danger text-start pt-2">*
        {{ errors["display"] }}
      </p>
      {% endif; %}
    </div>


    <div class="mb-3">
      <label for="image" class="form-label">Image</label>
      <input type="file" class="form-control" id="image" name="image">
      <div id="imageHelp" class="form-text">Choose an image for your product.</div>
      <div class="d-flex" id="imagePreviewContainer">
        <img src="{{ product['image'] }}" alt="{{ product['name'] }}" class="card-img-top "
          style="width:200px;height: auto;object-fit:cover;" id="imagePreviewContainerImage">
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
  <a href="/phpcore_udemy_app_1/products/<?= $product["product_id"] ?>/show"
    class="bg-gray-900 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded float-right">Cancel</a>
</div>



<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Function to calculate final price based on tax and price
    let priceInput = document.getElementById('price');

    function calculateFinal_price() {
      const price = parseFloat(priceInput.value == null ? 0 : priceInput.value);
      let taxRate = document.querySelector('input[name="tax"]:checked').value;
      taxRate = parseFloat(taxRate == null ? 0 : taxRate);

      const final_price = price * ((100 + taxRate) / 100);
      console.log("I ran with :" + `${price} ${taxRate} ${final_price}`);
      document.getElementById('final_price').value = final_price.toFixed(2);
    }

    // Add event listener to tax inputs
    var taxInputs = document.querySelectorAll('input[name="tax"]');
    taxInputs.forEach(function (input) {
      input.addEventListener('change', calculateFinal_price);
    });

    // Add event listener to price input
    priceInput.addEventListener("input", calculateFinal_price);



    // Add event listener for image input change
    const imageInput = document.getElementById('image');
    function imageEvent(event) {
      console.log("hi from image1");
      const file = event.target.files[0];
      if (file) {
        console.log("hi from image2");
        const reader = new FileReader();
        reader.onload = function (e) {
          const imagePreviewContainer = document.getElementById('imagePreviewContainer');
          const imgElement = document.getElementById('imagePreviewContainerImage');
          imgElement.src = e.target.result;

        };
        reader.readAsDataURL(file);
      }
    }
    imageInput.addEventListener('change', imageEvent);


    // Initial calculation on page load
    calculateFinal_price();
  });
</script>

{% endSection %}