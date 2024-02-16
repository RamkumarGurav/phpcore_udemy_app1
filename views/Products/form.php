<div class="mb-4">
  <label for="name" class="block text-gray-700">Name</label>
  <input type="text" id="name" name="name" value="<?= $product['name'] ?? '' ?>"
    class="form-input mt-1 block w-full rounded-md border-gray-300 bg-gray-200 p-4 py-2 shadow-sm">
  <?php if (isset($errors["name"])): ?>

    <p class="text-red-500 text-start pt-2">*
      <?= $errors["name"] ?>
    </p>
  <?php endif; ?>
</div>
<div class="mb-4">
  <label for="description" class="block text-gray-700">Description</label>
  <textarea name="description" id="description" cols="30" rows="5" <
    class="bg-gray-200 p-4 py-2 form-textarea mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?= $product['description'] ?? '' ?></textarea>

</div>
<button type="submit" class="btn bg-black  ">Save</button>