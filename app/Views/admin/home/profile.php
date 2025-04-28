<div class="row">
    <div class="col-sm-12">
        <br />
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Profile Details</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <?php
                if(isset($_SESSION['student'])){
                ?>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2">Name :</label>
                            <div class="col-md-4">
                                <?=$_SESSION['student'][0]->name; ?>
                            </div>
                            <label class="col-md-2">Father Name :</label>
                            <div class="col-md-4">
                                <?//=$_SESSION['student'][0]->father_name; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2">City :</label>
                            <div class="col-md-4">
                                <?//=$_SESSION['student'][0]->city; ?>
                            </div>
                            <label class="col-md-2">State :</label>
                            <div class="col-md-4">
                                <?//=$_SESSION['student'][0]->state; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2">Email :</label>
                            <div class="col-md-4">
                                <?//=$_SESSION['student'][0]->email; ?>
                            </div>
                            <label class="col-md-2">Contact # :</label>
                            <div class="col-md-4">
                                <?//=$_SESSION['student'][0]->contact_no; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="row">
                            <label class="col-md-2">Address :</label>
                            <div class="col-md-4">
                                <?//=$_SESSION['student'][0]->address; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 5%;">
                        <div class="row" style="float:right; margin: 0px;">
                            <button type="button" class="btn btn-primary" title="Edit Profile" onclick="window.location='<?php echo base_url();?>admin/home/edit-profile?btnTitle=Update';">Edit Profile</button>
                        </div>
                    </div>
                <?php
                }else{
                ?>
                    <p>No data</p>
                <?php
                }
                ?>
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