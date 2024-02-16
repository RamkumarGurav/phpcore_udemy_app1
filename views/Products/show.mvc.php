{% extend "layouts\default.php" %}


{% section title %} Product {% endSection %}



{% section body %}

<div class="container mt-5">
  <div class="card mb-3 mx-auto shadow" style="width:500px;">
    <div class="d-flex justify-content-center  align-items-center my-3">
      <img src="{{ product['image'] }}" alt="{{ product['name'] }}" class="card-img-top "
        style="width:auto;height: 230px;object-fit:cover;">
    </div>


    <div class="card-body">
      <h5 class="card-title h1 mb-0"> {{ product['name'] }}</h5>
      <p class="card-text lead fw-light">{{ product['description'] }}</p>
      <ul class="list-group list-group-flush">

        <li class="list-group-item"><strong>Category:</strong> {{ product['category'] }}</li>
        <li class="list-group-item"><strong>DisplayName:</strong>
          <?= implode(', ', $product["display"]) ?>
        </li>
        <li class="list-group-item"><strong>Tax:</strong> {{ product['tax'] }}%</li>
        <li class="list-group-item"><strong>Price:</strong> ₹{{ product['price'] }}</li>
        <li class="list-group-item"><strong>Final Price:</strong> ₹{{ product['final_price'] }}</li>
      </ul>
      <div class="d-grid gap-2 mt-4">
        <a href="/phpcore_udemy_app_1/products/{{ product['product_id'] }}/edit" class="btn btn-primary">Edit</a>
        <a href="/phpcore_udemy_app_1/products/{{ product['product_id'] }}/delete" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>

</div>





{% endSection %}