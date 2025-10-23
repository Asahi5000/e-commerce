<div id="deleteModal" class="modal">
  <div class="modal-content">
    <h3>Delete Message?</h3>
    <p>Are you sure you want to delete this message? This action cannot be undone.</p>
    <form method="post">
      <input type="hidden" name="message_id" id="deleteMessageId">
      <button type="submit" name="confirmDelete">Delete</button>
      <button type="button" onclick="closeDeleteModal()">Cancel</button>
    </form>
  </div>
</div>
