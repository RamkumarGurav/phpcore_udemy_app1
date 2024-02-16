



<div class="mb-3">
  <label for="name" class="form-label">Name</label>
  <input type="text" id="name" name="name" value='{{ product["name"] }}' class="form-control">
  {% if (isset($errors["name"])): %}
  <p class="text-danger">* {{ errors["name"] }}</p>
  {% endif; %}
</div>
<div class="mb-3">
  <label for="description" class="form-label">Description</label>
  <textarea name="description" id="description" cols="30" rows="5"
    class="form-control">{{ product['description'] }}</textarea>
</div>
<button type="submit" class="btn btn-primary">Save</button>