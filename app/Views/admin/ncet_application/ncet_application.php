<?php
if (isset($result)) {
    $result = $result;
}
?>
<style>
    .dataTables_empty {
        text-align: center;
    }
</style>
<div class="row m-t">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Upload NCET Application Data</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form id="form-upload" method="post" autocomplete="off">
                    <div class="row">
                        <div class="col-sm-3">
                            <!-- <div class="sub-result"></div> -->
                            <div class="form-group">
                                <label class="control-label">Choose File <small class="text-danger">*</small></label>
                                <input type="file" class="form-control form-control-sm m-b-xxs" id="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                                <small class="text-danger">Upload excel or csv file only.</small>
                            </div>
                            <div class="form-group">
                                <div class="text-center">
                                    <div class="user-loader" style="display: none; ">
                                        <i class="fa fa-spinner fa-spin"></i> <small>Please wait ...</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id="btnUpload" style="margin-top: 24px;">Upload</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>NCET Application List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <table class="table table-striped table-bordered table-hover dataTables-example" style="font-size: 14px !important;">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>NCET Application Number</th>
                            <th>Mobile</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#preloadercustom").hide();
        $(".myspin").hide();

        // Initialize DataTable
        var result = <?php echo json_encode(isset($result) ? $result : []); ?>;
        var table = $('.dataTables-example').DataTable({
            responsive: true,
            layout: {
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                }
            },
            data: result,
            columns: [{ data: "id" },
                { data: "name" },
                { data: "ncet_application_no" },
                { data: "mobile_no" },
            ]
        });

        $("body").on("submit", "#form-upload", function(e) {
            e.preventDefault();
            var data = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url('admin/ncet-applications') ?>",
                data: data,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function() {
                    $("#btnUpload").prop('disabled', true);
                    $("#preloadercustom").show();
                    $(".myspin").show();
                }, 
                success: function(result) {
                    $("#btnUpload").prop('disabled', false);
                    if($.isEmptyObject(result.error_message)) {
                        // $(".result").html(result.success_message);
                        toastr.success(result.success_message);
                        window.location.reload();
                    } else {
                        // $(".sub-result").html(result.error_message);
                        toastr.warning(result.error_message);
                    }
                    $("#form-upload")[0].reset();
                    $("#preloadercustom").hide();
                    $(".myspin").hide();
                }
            });
        });
    });
</script>