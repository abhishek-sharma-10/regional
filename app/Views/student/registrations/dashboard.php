<?php $id = $details->id;?>
  <style>
  .modal-backdrop.show {
    opacity: 1 !important;
  }
  .modal-backdrop {
    pointer-events: none;
  }
  .modal.modal-static .modal-dialog {
    transform: none !important;
  }
</style>

<!-- Modal -->
<div class="modal fade" id="policyModal" tabindex="-1" aria-labelledby="policyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <!-- Header -->
       <form method="Post">
        <div class="modal-header">
          <h4 class="modal-title" id="policyModalLabel">Guidelines and Policies</h4>
        </div>

        <!-- Body -->
        <div class="modal-body">
          <!-- PDF Embed -->
          <div class="mb-3" style="height: 600px;">
            <embed src="<?= base_url('public/assets/ITEP-Admission-Brochure-2025.pdf') ?>" type="application/pdf" width="100%" height="100%" />
          </div>
          
          <!-- Acknowledgment -->
          <div class="form-check mt-3">
            <input type="hidden" name="id" value="<?php echo $id?>"/>
            <input class="form-check-input" type="checkbox" id="ackCheckbox" name="ackCheckbox">
            <label class="form-check-label" for="ackCheckbox">
              I have read and agree to the guidelines and policies mentioned above.
            </label>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button type="submit" name="submit" class="btn primary-btn text-white" id="saveBtn" disabled>Save and Continue</button>
        </div>
      </form>
    </div>
  </div>
</div>