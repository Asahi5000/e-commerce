<!-- Modal -->
<div id="carModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeModalBtn">&times;</span>
        <h2>Add New Car</h2>

        <form action="../resources/helpers/save-product.php" method="POST" enctype="multipart/form-data">
            <!-- Car Name -->
            <div class="form-group">
                <label for="name" class="form-label">Car Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <!-- Brand -->
            <div class="form-group">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" name="brand" id="brand" class="form-control">
            </div>

            <!-- Model Year -->
            <div class="form-group">
                <label for="model_year" class="form-label">Model Year</label>
                <input type="number" name="model_year" id="model_year" class="form-control" min="1900" max="<?= date('Y'); ?>">
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id']; ?>">
                            <?= htmlspecialchars($cat['category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Price -->
            <div class="form-group">
                <label for="price" class="form-label">Price (₱)</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" required>
            </div>

            <!-- Mileage -->
            <div class="form-group">
                <label for="mileage" class="form-label">Mileage (km)</label>
                <input type="number" name="mileage" id="mileage" class="form-control">
            </div>

            <!-- Transmission -->
            <div class="form-group">
                <label for="transmission" class="form-label">Transmission</label>
                <select name="transmission" id="transmission" class="form-select" required>
                    <option value="">-- Select Transmission --</option>
                    <option value="manual">Manual</option>
                    <option value="automatic">Automatic</option>
                </select>
            </div>

            <!-- Fuel Type -->
            <div class="form-group">
                <label for="fuel_type" class="form-label">Fuel Type</label>
                <select name="fuel_type" id="fuel_type" class="form-select">
                    <option value="">-- Select Fuel Type --</option>
                    <option value="petrol">Petrol</option>
                    <option value="diesel">Diesel</option>
                    <option value="electric">Electric</option>
                    <option value="hybrid">Hybrid</option>
                </select>
            </div>

            <!-- Stock -->
            <div class="form-group">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" id="stock" class="form-control" value="1" min="0">
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-control"></textarea>
            </div>

            <!-- Image -->
            <div class="form-group">
                <label for="image" class="form-label">Upload Image</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>

            <!-- Buttons -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Car</button>
                <button type="button" class="btn btn-secondary" id="closeModalBtn2">Cancel</button>
            </div>
        </form>
    </div>
</div>
