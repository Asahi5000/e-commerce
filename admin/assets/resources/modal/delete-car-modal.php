<!-- Delete Car Modal -->
<div id="deleteCarModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeDeleteModal">&times;</span>
        <h2>Delete Car</h2>
        <p>Are you sure you want to delete <strong id="deleteCarName"></strong>?</p>

        <form id="deleteCarForm" method="POST" action="../resources/helpers/delete-product.php">
            <input type="hidden" name="car_id" id="deleteCarId">
            <div class="form-actions">
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Cancel</button>
            </div>
        </form>
    </div>
</div>


