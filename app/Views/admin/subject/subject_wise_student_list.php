<?php
    // var_dump($general, $obc_ncl, $sc, $st, $ews);exit;
?>

<div class="row m-t">
    <?php
        if(!isset($_SESSION['subject'])){
    ?>
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Select Subject</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form method="POST" class="form-horizontal" id="subject">
                    <div class="row" style="display: flex; align-items: flex-end;">
                        <div class="col-6 col-sm-3">
                            <label class="form-label">Subject:</label>
                            <!-- <input type="date" class="form-control" name="start_date"> -->
                            <select name="subject" id="subject" class="form-control">
                                <option value="">Select Subject</option>
                                <option value="Physics" <?php echo $subject == 'Physics' ? 'selected' : '';?>>Physics</option>
                                <option value="Chemistry" <?php echo $subject == 'Chemistry' ? 'selected' : '';?>>Chemistry</option>
                                <option value="Mathematics" <?php echo $subject == 'Mathematics' ? 'selected' : '';?>>Mathematics</option>
                                <option value="Zoology" <?php echo $subject == 'Zoology' ? 'selected' : '';?>>Zoology</option>
                                <option value="Botany" <?php echo $subject == 'Botany' ? 'selected' : '';?>>Botany</option>
                                <option value="History" <?php echo $subject == 'History' ? 'selected' : '';?>>History</option>
                                <option value="Geography" <?php echo $subject == 'Geography' ? 'selected' : '';?>>Geography</option>
                                <option value="English Language and Literature" <?php echo $subject == 'English Language and Literature' ? 'selected' : '';?>>English Language and Literature</option>
                                <option value="Hindi Language and Literature" <?php echo $subject == 'Hindi Language and Literature' ? 'selected' : '';?>>Hindi Language and Literature</option>
                                <option value="Urdu" <?php echo $subject == 'Urdu' ? 'selected' : '';?>>Urdu</option>
                            </select>
                        </div>
                        <div class="col-6 col-sm-2">
                            <button class="btn btn-primary m-n" id="show-list" type="submit">Show</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
        }
    ?>
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>General Student List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover general-counselling" style="font-size: 14px !important;">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Physical Disable</th>
                                <th>Preference</th>
                                <th>Percentile</th>
                                <th>B.Sc. Preference 1</th>
                                <th>B.Sc. Preference 2</th>
                                <th>B.Sc. Preference 3</th>
                                <th>B.Sc. Preference 4</th>
                                <th>B.A. Preference 1</th>
                                <th>B.A. Preference 2</th>
                                <th>B.A. Preference 3</th>
                                <th>B.A. Preference 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($general) && !empty($general) && count($general) > 0){
                                foreach ($general as $key => $value) {
                            ?>
                                <tr>
                                    <td><?=($key+1);?></td>
                                    <td><?=$value['name'];?></td>
                                    <td><?=$value['course'];?></td>
                                    <td><?=$value['category'];?></td>
                                    <td><?=$value['student_counselling_physical_disable'];?></td>
                                    <td><?=$value['student_counselling_subject']?></td>
                                    <td><?=$value['ncet_average_percentile']?></td>
                                    <td><?=$value['bsc_preference_1']?></td>
                                    <td><?=$value['bsc_preference_2']?></td>
                                    <td><?=$value['bsc_preference_3']?></td>
                                    <td><?=$value['bsc_preference_4']?></td>
                                    <td><?=$value['ba_preference_1']?></td>
                                    <td><?=$value['ba_preference_2']?></td>
                                    <td><?=$value['ba_preference_3']?></td>
                                    <td><?=$value['ba_preference_4']?></td>
                                </tr>
                            <?php
                                }}
                                else{
                                    echo "<td colspan='15' style='text-align:center;'>No Record For General Category</td>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>OBC-NCL Student List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover obc-ncl-counselling" style="font-size: 14px !important;">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Physical Disable</th>
                                <th>Preference</th>
                                <th>Percentile</th>
                                <th>B.Sc. Preference 1</th>
                                <th>B.Sc. Preference 2</th>
                                <th>B.Sc. Preference 3</th>
                                <th>B.Sc. Preference 4</th>
                                <th>B.A. Preference 1</th>
                                <th>B.A. Preference 2</th>
                                <th>B.A. Preference 3</th>
                                <th>B.A. Preference 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($general) && !empty($general) && count($general) > 0){
                                foreach ($general as $key => $value) {
                            ?>
                                <tr>
                                    <td><?=($key+1);?></td>
                                    <td><?=$value['name'];?></td>
                                    <td><?=$value['course'];?></td>
                                    <td><?=$value['category'];?></td>
                                    <td><?=$value['student_counselling_physical_disable'];?></td>
                                    <td><?=$value['student_counselling_subject']?></td>
                                    <td><?=$value['ncet_average_percentile']?></td>
                                    <td><?=$value['bsc_preference_1']?></td>
                                    <td><?=$value['bsc_preference_2']?></td>
                                    <td><?=$value['bsc_preference_3']?></td>
                                    <td><?=$value['bsc_preference_4']?></td>
                                    <td><?=$value['ba_preference_1']?></td>
                                    <td><?=$value['ba_preference_2']?></td>
                                    <td><?=$value['ba_preference_3']?></td>
                                    <td><?=$value['ba_preference_4']?></td>
                                </tr>
                            <?php
                                }}
                                else{
                                    echo "<td colspan='15' style='text-align:center;'>No Record For General Category</td>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>SC Student List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover sc-counselling" style="font-size: 14px !important;">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Physical Disable</th>
                                <th>Preference</th>
                                <th>Percentile</th>
                                <th>B.Sc. Preference 1</th>
                                <th>B.Sc. Preference 2</th>
                                <th>B.Sc. Preference 3</th>
                                <th>B.Sc. Preference 4</th>
                                <th>B.A. Preference 1</th>
                                <th>B.A. Preference 2</th>
                                <th>B.A. Preference 3</th>
                                <th>B.A. Preference 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($general) && !empty($general) && count($general) > 0){
                                foreach ($general as $key => $value) {
                            ?>
                                <tr>
                                    <td><?=($key+1);?></td>
                                    <td><?=$value['name'];?></td>
                                    <td><?=$value['course'];?></td>
                                    <td><?=$value['category'];?></td>
                                    <td><?=$value['student_counselling_physical_disable'];?></td>
                                    <td><?=$value['student_counselling_subject']?></td>
                                    <td><?=$value['ncet_average_percentile']?></td>
                                    <td><?=$value['bsc_preference_1']?></td>
                                    <td><?=$value['bsc_preference_2']?></td>
                                    <td><?=$value['bsc_preference_3']?></td>
                                    <td><?=$value['bsc_preference_4']?></td>
                                    <td><?=$value['ba_preference_1']?></td>
                                    <td><?=$value['ba_preference_2']?></td>
                                    <td><?=$value['ba_preference_3']?></td>
                                    <td><?=$value['ba_preference_4']?></td>
                                </tr>
                            <?php
                                }}
                                else{
                                    echo "<td colspan='15' style='text-align:center;'>No Record For General Category</td>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>ST Student List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover st-counselling" style="font-size: 14px !important;">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Physical Disable</th>
                                <th>Preference</th>
                                <th>Percentile</th>
                                <th>B.Sc. Preference 1</th>
                                <th>B.Sc. Preference 2</th>
                                <th>B.Sc. Preference 3</th>
                                <th>B.Sc. Preference 4</th>
                                <th>B.A. Preference 1</th>
                                <th>B.A. Preference 2</th>
                                <th>B.A. Preference 3</th>
                                <th>B.A. Preference 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($general) && !empty($general) && count($general) > 0){
                                foreach ($general as $key => $value) {
                            ?>
                                <tr>
                                    <td><?=($key+1);?></td>
                                    <td><?=$value['name'];?></td>
                                    <td><?=$value['course'];?></td>
                                    <td><?=$value['category'];?></td>
                                    <td><?=$value['student_counselling_physical_disable'];?></td>
                                    <td><?=$value['student_counselling_subject']?></td>
                                    <td><?=$value['ncet_average_percentile']?></td>
                                    <td><?=$value['bsc_preference_1']?></td>
                                    <td><?=$value['bsc_preference_2']?></td>
                                    <td><?=$value['bsc_preference_3']?></td>
                                    <td><?=$value['bsc_preference_4']?></td>
                                    <td><?=$value['ba_preference_1']?></td>
                                    <td><?=$value['ba_preference_2']?></td>
                                    <td><?=$value['ba_preference_3']?></td>
                                    <td><?=$value['ba_preference_4']?></td>
                                </tr>
                            <?php
                                }}
                                else{
                                    echo "<td colspan='15' style='text-align:center;'>No Record For General Category</td>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>EWS Student List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover ews-counselling" style="font-size: 14px !important;">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Physical Disable</th>
                                <th>Preference</th>
                                <th>Percentile</th>
                                <th>B.Sc. Preference 1</th>
                                <th>B.Sc. Preference 2</th>
                                <th>B.Sc. Preference 3</th>
                                <th>B.Sc. Preference 4</th>
                                <th>B.A. Preference 1</th>
                                <th>B.A. Preference 2</th>
                                <th>B.A. Preference 3</th>
                                <th>B.A. Preference 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($general) && !empty($general) && count($general) > 0){
                                foreach ($general as $key => $value) {
                            ?>
                                <tr>
                                    <td><?=($key+1);?></td>
                                    <td><?=$value['name'];?></td>
                                    <td><?=$value['course'];?></td>
                                    <td><?=$value['category'];?></td>
                                    <td><?=$value['student_counselling_physical_disable'];?></td>
                                    <td><?=$value['student_counselling_subject']?></td>
                                    <td><?=$value['ncet_average_percentile']?></td>
                                    <td><?=$value['bsc_preference_1']?></td>
                                    <td><?=$value['bsc_preference_2']?></td>
                                    <td><?=$value['bsc_preference_3']?></td>
                                    <td><?=$value['bsc_preference_4']?></td>
                                    <td><?=$value['ba_preference_1']?></td>
                                    <td><?=$value['ba_preference_2']?></td>
                                    <td><?=$value['ba_preference_3']?></td>
                                    <td><?=$value['ba_preference_4']?></td>
                                </tr>
                            <?php
                                }}
                                else{
                                    echo "<td colspan='15' style='text-align:center;'>No Record For General Category</td>";
                                }
                            ?>
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
        });
    </script>