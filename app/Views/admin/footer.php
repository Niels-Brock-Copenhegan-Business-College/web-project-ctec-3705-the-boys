</main>

<!-- Secret Code Verification Modal -->
<div class="modal fade" id="secretCodeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">⚠️ Confirm Destructive Action</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="mb-3">This action cannot be undone. Enter your <strong>secret code</strong> to proceed:</p>
        <div id="deleteItemInfo" class="alert alert-warning mb-3" style="display:none;">
          Deleting: <strong id="deleteItemTitle"></strong>
        </div>
        <form id="secretCodeForm">
          <div class="mb-3">
            <label for="secretCodeInput" class="form-label">Secret Code (PIN)</label>
            <input type="password" 
                   class="form-control form-control-lg" 
                   id="secretCodeInput" 
                   placeholder="Enter your secret code"
                   autocomplete="off"
                   required>
          </div>
          <div id="secretCodeError" class="alert alert-danger" style="display:none;"></div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
	window.APP_BASE_URL = '<?= rtrim(base_url(), "/") ?>';
</script>
<script src="<?= base_url('/js/main.js') ?>"></script>
</body>
</html>
