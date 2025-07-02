<style>
    .dataTables_empty{
        text-align: center;
    }
</style>
<div class="row m-t">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Counselling Wise Student List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <table class="table table-striped table-bordered table-hover student-list" style="font-size: 14px !important;">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Academic Receipt No.</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // var_dump($records);
                            $count = 1;
                            if(count($records) > 1){
                                foreach ($records as $value) {
                        ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?=$value->name;?></td>
                                    <td><?=$value->course;?></td>
                                    <td><?=$value->academic_receipt_no;?></td>
                                    <td><?=$value->counselling_status;?></td>
                                    <td><?php if(isset($_SESSION['role']) && !empty($_SESSION['role']) && $_SESSION['role'] == 'account') {?><a href="<?php echo base_url('admin/counselling/student-detail/'.$value->id);?>"><button class='btn btn-success btn-sm'>Student Details</button><?php }?></td>
                                </tr>
                        <?php
                        }
                            }else{
                        ?>
                            <tr><td colspan="5" style="text-align:center;">No Data Available</td></tr>
                        <?php   
                            }
                        ?>
                    </tbody>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        var counselling_student = [];
        var c_id = 0;

        $(document).ready(function() {
            $("#preloadercustom").hide();
            $(".myspin").hide();
            $("#send_mail").hide();

            $('.student-list').DataTable({});

            // var records = <?php echo json_encode(isset($records) ? $records : []); ?>;

            // var table = $('.student-list').DataTable({
            //     responsive: true,
            //     data: records,
            //     columns: [
            //         { 
            //             data: null, 
            //             render: function(data, type, row, meta){ 
            //                 return (meta.row + meta.settings._iDisplayStart + 1); 
            //             } 
            //         },
            //         { data: "name" },
            //         { data: "course" },
            //         { data: "academic_receipt_no" },
            //         {
            //             data: null,
            //             render: function(data, type, row) {
            //                 return "<a href='<?php echo base_url('admin/counselling/student-detail/');?>"+row.id+"'><button class='btn btn-success btn-sm'>Student Details</button>";
            //             }
            //         }
            //     ]
            // });
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
    </script>