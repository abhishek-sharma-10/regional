<?php
    // var_dump($records);
?>

<style>
    .dataTables_empty{
        text-align: center;
    }
</style>
<div class="row m-t">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Select Fees</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form method="POST" class="form-horizontal" id="fees">
                    <div class="row" style="display: flex; align-items: flex-end;">
                        <div class="col-sm-3">
                            <label class="form-label">Fees:</label>
                            <select name="fees" id="fees" class="form-control">
                                <option value="">Select</option>
                                <option value="with fees" <?php echo $fees == 'with fees' ? 'selected' : '';?>>With Fees</option>
                                <option value="without fees" <?php echo $fees == 'without fees' ? 'selected' : '';?>>Without Fees</option>
                                <option value="all" <?php echo $fees == 'all' ? 'selected' : '';?>>All</option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-primary m-n" id="show-list" type="submit">Show</button>
                        </div>
                        <?php if($_SESSION['user'][0]->role == 'admin'){?>
                        <div class="col-sm-3">
                            <a href="<?php echo base_url('admin/counselling/course-wise-pdf/'.$id);?>"><button class="btn btn-success m-n" type="button">Generate Course Wise PDF</button></a>
                        </div>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover student-list" style="font-size: 14px !important;">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>NCET Application</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Preference</th>
                                <th>Category</th>
                                <th>Physical Disable</th>
                                <th>Percentile</th>
                                <th>Academic Receipt No.</th>
                                <th>B.Sc. Preference 1</th>
                                <th>B.Sc. Preference 2</th>
                                <th>B.Sc. Preference 3</th>
                                <th>B.Sc. Preference 4</th>
                                <th>B.A. Preference 1</th>
                                <th>B.A. Preference 2</th>
                                <th>B.A. Preference 3</th>
                                <th>B.A. Preference 4</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // var_dump($records);
                                $count = 1;
                                if(count($records) > 0){
                                    foreach ($records as $value) {
                            ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo $value->ncet_application_no; ?></td>
                                        <td><?=$value->name;?></td>
                                        <td><?=$value->course;?></td>
                                        <td><?=$value->student_counselling_subject;?></td>
                                        <td><?=$value->student_counselling_category;?></td>
                                        <td><?=$value->student_counselling_physical_disable;?></td>
                                        <td><?=$value->ncet_average_percentile;?></td>
                                        <td><?=$value->academic_receipt_no;?></td>
                                        <td><?=$value->bsc_preference_1;?></td>
                                        <td><?=$value->bsc_preference_2;?></td>
                                        <td><?=$value->bsc_preference_3;?></td>
                                        <td><?=$value->bsc_preference_4;?></td>
                                        <td><?=$value->ba_preference_1;?></td>
                                        <td><?=$value->ba_preference_2;?></td>
                                        <td><?=$value->ba_preference_3;?></td>
                                        <td><?=$value->ba_preference_4;?></td>
                                        <td><?=$value->counselling_status;?></td>
                                        <td><?php if(isset($_SESSION['role']) && !empty($_SESSION['role']) && $_SESSION['role'] == 'account') {?><a href="<?php echo base_url('admin/counselling/student-detail/'.$value->id);?>"><button class='btn btn-success btn-sm'>Student Details</button><?php }?></td>
                                    </tr>
                            <?php
                                    }
                                }else{
                            ?>
                                <tr><td colspan="18" style="text-align:center;">No Data Available</td></tr>
                            <?php   
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
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