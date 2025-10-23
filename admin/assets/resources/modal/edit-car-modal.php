<!-- EDIT CAR MODAL -->
<div id="editCarModal" class="modal">
  <div class="modal-content">
    <button class="close-btn" id="closeEditModalBtn">&times;</button>
    <h2>Edit Car</h2>

    <form id="editForm" action="../resources/helpers/update-product.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="car_id" id="edit_car_id">

      <div class="form-group">
        <label for="edit_name" class="form-label">Car Name</label>
        <input type="text" name="name" id="edit_name" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="edit_brand" class="form-label">Brand</label>
        <input type="text" name="brand" id="edit_brand" class="form-control">
      </div>

      <div class="form-group">
        <label for="edit_model_year" class="form-label">Model Year</label>
        <input type="number" name="model_year" id="edit_model_year" class="form-control" min="1900" max="<?= date('Y'); ?>">
      </div>

      <div class="form-group">
        <label for="edit_category_id" class="form-label">Category</label>
        <select name="category_id" id="edit_category_id" class="form-select">
          <option value="">-- Select Category --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['category_id']; ?>"><?= htmlspecialchars($cat['category_name']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="edit_price" class="form-label">Price (₱)</label>
        <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="edit_mileage" class="form-label">Mileage (km)</label>
        <input type="number" name="mileage" id="edit_mileage" class="form-control">
      </div>

      <div class="form-group">
        <label for="edit_transmission" class="form-label">Transmission</label>
        <select name="transmission" id="edit_transmission" class="form-select" required>
          <option value="">-- Select Transmission --</option>
          <option value="manual">Manual</option>
          <option value="automatic">Automatic</option>
        </select>
      </div>

      <div class="form-group">
        <label for="edit_fuel_type" class="form-label">Fuel Type</label>
        <select name="fuel_type" id="edit_fuel_type" class="form-select">
          <option value="">-- Select Fuel Type --</option>
          <option value="petrol">Petrol</option>
          <option value="diesel">Diesel</option>
          <option value="electric">Electric</option>
          <option value="hybrid">Hybrid</option>
        </select>
      </div>

      <div class="form-group">
        <label for="edit_stock" class="form-label">Stock</label>
        <input type="number" name="stock" id="edit_stock" class="form-control" min="0">
      </div>

<div class="form-group">
  <label for="edit_description" class="form-label">Description</label>
  <textarea name="description" id="edit_description" rows="4" class="form-control"></textarea>
</div>

      <div class="form-group">
        <label for="edit_image" class="form-label">Upload New Image</label>
        <input type="file" name="image" id="edit_image" class="form-control">
        <small><br>Leave blank if you don't want to change the image.</small>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update Car</button>
        <button type="button" class="btn btn-secondary" id="closeEditModalBtn2">Cancel</button>
      </div>
    </form>
  </div>
</div>


