<style>
    .dataTables_empty{
        text-align: center;
    }
</style>
<div class="row m-t">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Add Counselling</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form method="POST" class="form-horizontal" id="counselling_form">
                    <div class="row" style="display: flex; align-items: flex-end;">
                        <div class="col-6 col-sm-3">
                            <label class="form-label">Start Date:</label>
                            <input type="date" class="form-control" name="start_date">
                        </div>
                        <div class="col-6 col-sm-3">
                            <label class="form-label">End Date:</label>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                        <div class="col-6 col-sm-2">
                            <button class="btn btn-primary m-n" id="add" type="submit">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- <div class="col-md-5">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Counselling List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <table class="table table-striped table-bordered table-hover counselling" style="font-size: 14px !important;">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div> -->
    <!-- <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Counselling wise student list</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-danger btn-sm" id="send_mail" type="button" onclick="sendEmail()" style="display: none;">Send Email</button>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover counselling-student" style="font-size: 14px !important;">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Registration No</th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div> -->
    <script>
        var counselling_student = [];
        var c_id = 0;

        $(document).ready(function() {
            $("#preloadercustom").hide();
            $(".myspin").hide();
            $("#send_mail").hide();

            // var counselling = <?php echo json_encode(isset($counselling) ? $counselling : []); ?>;

            // var table = $('.counselling').DataTable({
            //     responsive: true,
            //     data: counselling,
            //     columns: [
            //         { data: "id" },
            //         { data: "start_date" },
            //         { data: "end_date" },
            //         {
            //             data: null,
            //             render: function(data, type, row) {
            //                 return "<button class='btn btn-success btn-sm' onclick='showCounsellingStudentList("+ row.id +")'>Student Details</button>";
            //             }
            //         }
            //     ]
            // });

            $("body").on("submit", "#counselling_form", function(e) {
                e.preventDefault();
                var data = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url('admin/counselling') ?>",
                    data: data,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function() {
                        $("#add").prop('disabled', true);
                        $("#preloadercustom").show();
                        $(".myspin").show();
                    }, 
                    success: function(result) {
                        $("#add").prop('disabled', false);
                        if($.isEmptyObject(result.error_message)) {
                            // $(".result").html(result.success_message);
                            toastr.success(result.success_message);
                            // window.location.reload();
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

        // function sendEmail(){
        //     console.log('Send Mail');
        //     $.ajax({
        //         type: 'POST',
        //         url: "<?php echo base_url('admin/counselling/send-email/') ?>"+c_id,
        //         data: JSON.stringify(counselling_student),
        //         dataType: 'json',
        //         contentType: 'application/json',
        //         cache: false,
        //         beforeSend: function() {
        //             $("#preloadercustom").show();
        //             $(".myspin").show();
        //         }, 
        //         success: function(result) {
        //             console.log(result);
        //             let response = result;

        //             if(response.success){
        //                 toastr.success(response.message);
        //             }else{
        //                 toastr.error(response.message);
        //             }
                    
        //             $("#preloadercustom").hide();
        //             $(".myspin").hide();
        //         },error(e){
        //             console.log('Error', e);
        //         }
        //     });
        // }

        // function showCounsellingStudentList(id){
        //     console.log('ENter');
        //     c_id = id;
        //     $.ajax({
        //         type: 'GET',
        //         url: "<?php echo base_url('admin/counselling/student-list') ?>",
        //         // data: data,
        //         dataType: 'json',
        //         contentType: false,
        //         cache: false,
        //         processData:false,
        //         beforeSend: function() {
        //             $("#preloadercustom").show();
        //             $(".myspin").show();
        //         }, 
        //         success: function(result) {
        //             console.log(result);
        //             counselling_student = (result);

        //             if(counselling_student.length > 0){
        //                 $("#send_mail").show();
        //             }
        //             $('.counselling-student').DataTable({
        //                 responsive: true,
        //                 data: counselling_student,
        //                 columns: [
        //                     { data: "id" },
        //                     { data: "registration_no" },
        //                     { data: "name" },
        //                     { data: "email" }
        //                 ]
        //             });

        //             $("#preloadercustom").hide();
        //             $(".myspin").hide();
        //         }
        //     });
        // }
    </script>