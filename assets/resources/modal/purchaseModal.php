<div id="purchaseModal" class="purchase-modal" aria-hidden="true">
  <div class="purchase-content" role="dialog" aria-modal="true" aria-labelledby="purchaseTitle">
    <button class="modal-close" onclick="closePurchaseModal()" aria-label="Close">&times;</button>

    <h2 id="purchaseTitle">💳 Secure Payment</h2>
    <p class="modal-info">Enter your payment details to complete your purchase.</p>

    <div class="customer-info" id="customerInfo">
      <p><strong>Name:</strong> <span id="custName">—</span></p>
      <p><strong>Email:</strong> <span id="custEmail">—</span></p>
      <p><strong>Phone:</strong> <span id="custPhone">—</span></p>
      <p><strong>Address:</strong> <span id="custAddress">—</span></p>
    </div>

    <form id="purchaseForm" autocomplete="off">
      <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
      <input type="hidden" name="car_id" id="car_id">
      <input type="hidden" name="carPrice" id="carPrice">

      <div class="form-group">
        <label for="cardHolder">Card Holder</label>
        <input type="text" id="cardHolder" name="cardHolder" placeholder="John Wick" required>
      </div>

      <div class="form-group">
        <label for="cardNumber">Card Number</label>
        <input type="text" id="cardNumber" name="cardNumber" maxlength="20" placeholder="1010101010101010" required>
      </div>

      <div class="form-row">
        <div class="form-group" style="flex:1;">
          <label for="expiryDate">Expiry Date</label>
          <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY e.g. 12/28" required>
        </div>
        <div class="form-group" style="width:110px;">
          <label for="cvv">CVV</label>
          <input type="text" id="cvv" name="cvv" maxlength="4" placeholder="123" required>
        </div>
      </div>

      <button type="submit" class="btn-pay" id="payNowBtn">Pay Now</button>
    </form>

    <p id="paymentStatus" class="payment-status" role="status" aria-live="polite"></p>
  </div>
</div>
