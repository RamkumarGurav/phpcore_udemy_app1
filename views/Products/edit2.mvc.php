{% extend "layouts\default.php" %}


{% section title %} Edit Product {% endSection %}




{% section body %}
<div class="container mx-auto px-4 mt-8">
  <h1 class="text-2xl font-bold mb-4">Edit Product</h1>

  <form action="/phpcore_udemy_app_1/products/<?= $product["id"] ?>/update" method="post" class="max-w-md mx-auto">
    <!-- here php is actually executing from MVCTemplateViewer class so
    //thats why "../views/Products/form.php" -->
    {% include "Products/form.mvc.php" %}
  </form>

  <a href="/phpcore_udemy_app_1/products/<?= $product["id"] ?>/show"
    class="bg-gray-900 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded float-right">Cancel</a>
</div>
{% endSection %}