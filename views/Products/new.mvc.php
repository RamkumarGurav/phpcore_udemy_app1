{% extend "layouts\default.php" %}


{% section title %} New Product
{% endSection %}




{% section body %}

<?= $description = $category = $tax = $price = $final_price = ""; ?>



<div class="container mt-5">
  <h1 class="h2 mb-4">Create Product</h1>
  <form action="/phpcore_udemy_app_1/products/create" method="post" id="productForm" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="name" class="form-label">Product Name</label>
      <input type="text" class="form-control" id="name" name="name" value="{{ name }}" required>
    </div>
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea class="form-control" id="description" name="description" rows="3">{{ description }}</textarea>
    </div>
    <div class="mb-3">
      <label for="category" class="form-label">Category</label>
      <select class="form-select" id="category" name="category" required>
        <option value="">Select Category</option>
        <option value="electronics" <?= $category == 'electronics' ? 'selected' : '' ?>>Electronics</option>
        <option value="clothing" <?= $category == 'clothing' ? 'selected' : '' ?>>Clothing</option>
        <option value="home" <?= $category == 'home' ? 'selected' : '' ?>>Home</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Tax</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="tax" id="noTax" value="0" <?= $tax == '0' ? 'checked' : '' ?>>
        <label class="form-check-label" for="noTax">No Tax</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="tax" id="tax12" value="12" <?= $tax == '12' ? 'checked' : '' ?>>
        <label class="form-check-label" for="tax12">12% Tax</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="tax" id="tax10" value="10" <?= $tax == '10' ? 'checked' : '' ?>>
        <label class="form-check-label" for="tax10">10% Tax</label>
      </div>
    </div>
    <div class="mb-3">
      <label for="price" class="form-label">Price</label>
      <input type="number" class="form-control" id="price" name="price" value="{% $price %} " required>
    </div>
    <div class="mb-3">
      <label for="final_price" class="form-label">Final Price</label>
      <input type="number" class="form-control" id="final_price" name="final_price" readonly>
    </div>
    <div class="mb-3">
      <label class="form-check-label">Display</label><br>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="new_Product" id="new_Product" name="display[]">
        <label class="form-check-label" for="new_Product">New Product</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="most_Viewed" id="most_Viewed" name="display[]">
        <label class="form-check-label" for="most_Viewed">Most Viewed</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="top_Seller" id="top_Seller" name="display[]">
        <label class="form-check-label" for="top_Seller">Top Seller</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="most_Popular" id="most_Popular" name="display[]">
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
      <input type="file" class="form-control" id="image" name="image" required>
      <div id="imageHelp" class="form-text">Choose an image for your product.</div>
      <!-- Image preview container -->
      <div id="imagePreviewContainer" class="mb-3"></div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
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
          const imgElement = document.createElement('img');
          imgElement.src = e.target.result;
          imgElement.classList.add('img-fluid', 'mt-2');
          imgElement.style.width = '200px';
          imgElement.style.height = 'auto';
          imgElement.style.objectFit = 'cover';
          // imgElement.style = { height: "150px", objectFit: "cover" };
          imagePreviewContainer.innerHTML = ''; // Clear previous image previews
          imagePreviewContainer.appendChild(imgElement);
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