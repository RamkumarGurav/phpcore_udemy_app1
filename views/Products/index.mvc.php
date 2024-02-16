{% extend "layouts\default.php" %}


{% section title %} Products {% endSection %}

{% section body %}
<div class="container">
  <h1 class="h1 text-danger">Products</h1>
  <h1 class="h2 text-primary">Total: {{ total }}</h1>



  <form method="post" action="/phpcore_udemy_app_1/products/destroyall">
    <div class="text-end">
      <a href="/phpcore_udemy_app_1/products/new" class="btn btn-primary mb-3">+ New Product</a>
      <button type="submit" class="btn btn-danger mb-3 ms-auto">Delete
        Selected</button>
    </div>
    {% foreach ($products as $product): %}
    <div class="d-flex gap-2 align-items-center mb-3 shadow pe-4 border ">
      <img src="{{ product['image'] }}" class="img-fluid" style="width:120px;height:120px;object-fit:cover;"
        alt="{{ product['name'] }}">
      <h5 class="card-title me-auto">{{ product['name'] }}</h5>

      <a href="/phpcore_udemy_app_1/products/{{ product['id'] }}/show" class="btn btn-primary">View</a>
      <a href="/phpcore_udemy_app_1/products/{{ product['id'] }}/edit" class="btn btn-warning">Edit</a>
      <input type="checkbox" name="product_ids[]" value="{{ product['id'] }}" class="form-check-input custom-checkbox">

    </div>
    {% endforeach; %}

    <?php

    $maxVisiblePageBtns = 5;
    $activePage = $currentPage;
    $paginationRange = [];



    if ($totalPages <= $maxVisiblePageBtns) {
      // If total pages are less than or equal to maxVisiblePageBtns, show all pages
      $paginationRange = range(1, $totalPages);
    } else {
      $newRange = []; // Initialize new pagination range
      $middlePoint = ceil($maxVisiblePageBtns / 2); // Calculate the middle point
    
      // Adjust pagination range based on the activePage position within the maxVisiblePageBtns
    
      // Display the first maxVisiblePageBtns pages if activePage is near the start of the totalPages
      if ($activePage <= $middlePoint + 1) {
        $newRange = range(1, $maxVisiblePageBtns);
      }
      // Display the last maxVisiblePageBtns pages if activePage is near the end of the total pages
      elseif ($activePage >= $totalPages - $middlePoint) {
        $newRange = range($totalPages - $maxVisiblePageBtns + 1, $totalPages);

      }
      // Display a range centered around the activePage
      elseif (($activePage - 1) % ($maxVisiblePageBtns - 1) === 0) {
        $newRange = range($activePage, $activePage + $maxVisiblePageBtns - 1);
      } elseif (($activePage - 1) % ($maxVisiblePageBtns - 1) > 0) {
        $newRange = range($activePage - (($activePage - 1) % ($maxVisiblePageBtns - 1)), $activePage + $maxVisiblePageBtns - (($activePage - 1) % ($maxVisiblePageBtns - 1)) - 1);
      } else {
        $newRange = range($activePage, $activePage + $maxVisiblePageBtns - 1);
      }

      // Update the pagination range
      $paginationRange = $newRange;
    }
    ?>

    <!-- Pagination -->
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <li class="page-item ">
          <a class="page-link" href="/phpcore_udemy_app_1/products/index?page=1&limit=<?= $limit ?>" aria-label="first">
            first
          </a>
        </li>
        <li class="page-item <?= $activePage <= 1 ? "disabled " : "" ?>">
          <a class="page-link"
            href="/phpcore_udemy_app_1/products/index?page=<?= $activePage <= 1 ? 1 : $activePage - 1 ?>&limit=<?= $limit ?>"
            aria-label="Previous">
            Prev
          </a>
        </li>
        <?php foreach ($paginationRange as $item): ?>
          <li class=" <?= $activePage == $item ? 'page-item active ' : 'page-item' ?>"><a class="page-link "
              href="/phpcore_udemy_app_1/products/index?page=<?= $item ?>&limit=<?= $limit ?>">
              <?= $item ?>
            </a></li>
        <?php endforeach; ?>

        <li class="page-item <?= $activePage >= $totalPages ? "disabled " : "" ?>">
          <a class="page-link"
            href="/phpcore_udemy_app_1/products/index?page=<?= $activePage >= $totalPages ? $totalPages : $activePage + 1 ?>&limit=<?= $limit ?>"
            aria-label="Next">
            Next
          </a>
        </li>
        <li class="page-item ">
          <a class="page-link" href="/phpcore_udemy_app_1/products/index?page=<?= $totalPages ?>&limit=<?= $limit ?>"
            aria-label="last">
            last
          </a>
        </li>
      </ul>
    </nav>

  </form>
</div>



{% endSection %}