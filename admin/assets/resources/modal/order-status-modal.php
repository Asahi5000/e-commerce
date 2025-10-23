  <!-- Modal -->
  <div class="modal" id="statusModal">
    <div class="modal-content">
      <h3>Update Order Status</h3>
      <input type="hidden" id="modalOrderId">
      <select id="modalStatus">
        <option value="pending">Pending</option>
        <option value="confirmed">Confirmed</option>
        <option value="shipped">Shipped</option>
        <option value="delivered">Delivered</option>
        <option value="cancelled">Cancelled</option>
      </select>
      <div class="modal-buttons">
        <button class="save-btn" onclick="saveStatus()">Save</button>
        <button class="cancel-btn" onclick="closeModal()">Cancel</button>
      </div>
    </div>
  </div>