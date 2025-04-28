<div class="row border-bottom">
    <div class="col-md-12">
        <br />
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Update Profile</h5>
                <div class="ibox-tools">
                    <span><label style="color:#cc5965;font-size:20px">I &nbsp;</label><label > - Required</label></span>
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>

            <div class="ibox-content">
                <form name="registration_form" id="registration_form" action="<?php echo base_url();?>admin/home/edit-profile" enctype="multipart/form-data" method="post" class="form-horizontal">

                    <input type="hidden" name="btnTitle" value="<?php echo $btnTitle;?>" id="btnTitle"/>    

                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="control-label">Email</label>
                            <input type="text" name="email" value="<?php //echo $_SESSION['student'][0]->email?>" id="email" class="form-control requiredfield" data-rule-required="true" data-rule-email="true"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="control-label">State</label> 
                            <select name="state" id="state" class="form-control requiredfield" data-rule-required="true">
                                <!-- states option -->
                                <?php
                                    // if(count($allStates)){
                                    //     foreach($allStates as $state){
                                ?>
                                            <option value="<?php //echo $state->states; ?>" <?php //echo $_SESSION['student'][0]->state == $state->states ?'selected':'' ?> ><?php //echo $state->states ?></option>
                                <?php
                                    //     }
                                    // }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">City</label>
                            <div class="input-group">
                                <select name="city" id="city" class="form-control requiredfield" data-rule-required="true">
                                    <!-- all cities option -->
                                    <?php
                                        // if(count($citiesOfStates)){
                                        //     foreach($citiesOfStates as $district){
                                    ?>
                                                <!-- <option value="<?php //echo $district->districts ?>" <?php //echo $_SESSION['student'][0]->city == $district->districts?'selected':'' ?> ><?php //echo $district->districts ?></option> -->
                                    <?php
                                        //     }
                                        // }
                                    ?>
                                </select>
                                <input type="text" name="customcity" id="customcity" style="display:none;" class="form-control requiredfield" data-rule-required="true" placeholder="write city name here.."/>
                                <span class="input-group-addon" id="basic-addon2"><input type="checkbox" name="othercity" id="othercity" class="ckhbox" onclick="otherCityShow(this);"/> Other</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="control-label">Contact #</label>
                            <input type="text" name="contact_no" value="<?php //echo $_SESSION['student'][0]->contact_no?>" id="contact_no" class="form-control requiredfield" data-rule-required="true" data-rule-phoneno="true" maxlength="15"/>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Address</label>
                            <textarea name="address" cols="40" class="form-control requiredfield" data-rule-required="true" style="resize:none"><?php //echo $_SESSION['student'][0]->address;?></textarea>
                        </div>
                    </div>

                    <div class="form-group" align="center">
                        <button type="submit" class="btn btn-primary" >Update</button>
                        <a href="<?php echo base_url();?>home/profile"><button class="btn btn-danger" type="button" >Cancel</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#preloadercustom").hide();
        $(".myspin").hide();
        
        $("#state").change(function() {
            //console.log('--------------');
            var stateSelected = $('#state').val();
            //console.log('stateSelected'+stateSelected);
            $.ajax({
                 type: "GET",
                 url: "<?php echo base_url();?>admin/home/cities-of-state", 
                 data: {'selectedState' : stateSelected},
                 dataType: "json",  
                 cache:false,
                 success: 
                 function(data){
                    //console.log(data);
                    $('#city').html(data);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('#loader').hide();
                }
            });
        });
    });
</script>