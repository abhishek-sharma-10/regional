<style>
    .dataTables_empty{
        text-align: center;
    }
</style>
<div class="row m-t">
    <div class="col-md-12">
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
    </div>
    <!-- <div class="col-md-7">
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

            var counselling = <?php echo json_encode(isset($counselling) ? $counselling : []); ?>;

            var table = $('.counselling').DataTable({
                responsive: true,
                data: counselling,
                columns: [
                    { data: "id" },
                    { data: "start_date" },
                    { data: "end_date" },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return "<a href='<?php echo base_url('/admin/counselling/student-list/');?>"+row.id+"'><button class='btn btn-success btn-sm'>Student List</button></a>";
                        }
                    }
                ]
            });
        });

        function sendEmail(){
            console.log('Send Mail');
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url('admin/counselling/send-email/') ?>"+c_id,
                data: JSON.stringify(counselling_student),
                dataType: 'json',
                contentType: 'application/json',
                cache: false,
                beforeSend: function() {
                    $("#preloadercustom").show();
                    $(".myspin").show();
                }, 
                success: function(result) {
                    console.log(result);
                    let response = result;

                    if(response.success){
                        toastr.success(response.message);
                    }else{
                        toastr.error(response.message);
                    }
                    
                    $("#preloadercustom").hide();
                    $(".myspin").hide();
                },error(e){
                    console.log('Error', e);
                }
            });
        }

        function showCounsellingStudentList(id){
            console.log('ENter');
            c_id = id;
            $.ajax({
                type: 'GET',
                url: "<?php echo base_url('admin/counselling/student-list') ?>",
                // data: data,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function() {
                    $("#preloadercustom").show();
                    $(".myspin").show();
                }, 
                success: function(result) {
                    console.log(result);
                    counselling_student = (result);

                    if(counselling_student.length > 0){
                        $("#send_mail").show();
                    }
                    $('.counselling-student').DataTable({
                        responsive: true,
                        data: counselling_student,
                        columns: [
                            { data: "id" },
                            { data: "registration_no" },
                            { data: "name" },
                            { data: "email" }
                        ]
                    });

                    $("#preloadercustom").hide();
                    $(".myspin").hide();
                }
            });
        }
    </script>