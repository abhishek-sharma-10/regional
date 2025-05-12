        <footer>
            <div class="footer">
                <!-- <div class="pull-right"></div> -->
                <div class="text-center">
                    <strong>Copyright</strong> Regional Institute of Education&copy; <?php echo date('Y'); ?> <strong>• Developed By</strong> iBirds Software Services Pvt. Ltd.
                </div>
            </div>
        </footer>
    <!-- </div>
</div> -->
    <script src="<?php echo base_url(); ?>public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/js/main.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/js/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>public/assets/js/plugins/toastr/toastr.min.js"></script>
    
       <script>
            const policyModal = new bootstrap.Modal(document.getElementById('policyModal'), {
                backdrop: 'static',
                keyboard: false
            });

            window.addEventListener('load', () => {
                policyModal.show();
            });

            // Checkbox & Button Logic
            const ackCheckbox = document.getElementById('ackCheckbox');
            const saveBtn = document.getElementById('saveBtn');

            ackCheckbox.addEventListener('change', function() {
                saveBtn.disabled = !this.checked;
            });

            saveBtn.addEventListener('click', function() {
                if (ackCheckbox.checked) {
                    policyModal.hide();
                }
            });

            // Disable right click
            document.addEventListener('contextmenu', function(e){
                e.preventDefault();
            });

            // Disable F12 key
            document.addEventListener('keydown', function(e){
                if(e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) || (e.ctrlKey && e.key === 'U')) {
                    e.preventDefault();
                }
            });
        </script>
    </body>
</html>