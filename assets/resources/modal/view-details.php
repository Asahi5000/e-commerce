<div id="carModal" class="view-details-modal">
  <div class="view-details-content">
    <button class="modal-close" onclick="closeModal()">&times;</button>
    <img id="modalImage" src="" alt="Car Image" class="modal-image">

    <div class="modal-body">
      <h2 id="modalName" class="modal-title"></h2>
      <p id="modalBrandYear" class="modal-subtitle"></p>
      <p id="modalPrice" class="modal-price"></p>

      <!-- Car Details -->
      <p id="modalCategory" class="modal-detail"></p>
      <p id="modalMileage" class="modal-detail"></p>
      <p id="modalTransmission" class="modal-detail"></p>
      <p id="modalFuel" class="modal-detail"></p>
      <p id="modalStock" class="modal-detail"></p>

      <!-- Car Description -->
      <h3>Description</h3>
      <p id="modalDescription" class="modal-description mt-3"></p>

      <!-- ✅ Purchase Button must be inside the modal -->
      <button id="purchaseBtn" class="purchase-btn">Purchase Now</button>
    </div>
  </div>
</div>
