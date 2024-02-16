{% extend "layouts\default.php" %}


{% section title %} Delete Product {% endSection %}




{% section body %}
<!--  -->


<div class="container-fluid p-5   mt-5  py-4 ">
  <h1 class="h2 fw-bold mb-4">Delete Product</h1>

  <form action="/phpcore_udemy_app_1/products/<?= $product["id"] ?>/destroy" method="post" class="my-4">
    <h2 class="text-danger fw-semibold fs-5">Delete this product?</h2>
    <button type="submit" class="btn btn-danger">Yes</button>
  </form>

  <a href="/phpcore_udemy_app_1/products/<?= $product["id"] ?>/show" class="btn btn-secondary">Cancel</a>



</div>

{% endSection %}