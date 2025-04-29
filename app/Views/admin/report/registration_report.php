<?php
if (isset($registrations)) {
    $registrations = $registrations;
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Filter</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <?php
            $states = [
                "Andhra Pradesh",
                "Arunachal Pradesh",
                "Assam",
                "Bihar",
                "Chhattisgarh",
                "Gujarat",
                "Haryana",
                "Himachal Pradesh",
                "Jammu and Kashmir",
                "Goa",
                "Jharkhand",
                "Karnataka",
                "Kerala",
                "Madhya Pradesh",
                "Maharashtra",
                "Manipur",
                "Meghalaya",
                "Mizoram",
                "Nagaland",
                "Odisha",
                "Punjab",
                "Rajasthan",
                "Sikkim",
                "Tamil Nadu",
                "Telangana",
                "Tripura",
                "Uttarakhand",
                "Uttar Pradesh",
                "West Bengal",
                "Andaman and Nicobar Islands",
                "Chandigarh",
                "Dadra and Nagar Haveli",
                "Daman and Diu",
                "Delhi",
                "Lakshadweep",
                "Puducherry"
            ];
            ?>
            <div class="ibox-content">
                <form action="<?php echo base_url('/admin/report/state-wise-report'); ?>" method="POST">
                    <div class="container text-center">
                        <div class="row">
                            <div class="col-6 col-sm-3">
                                <input type="hidden" name="filterType" value="state" />
                                <select class="form-control form-select-sm" name="filterValue">
                                    <option value="" <?php echo isset($filterValue) && $filterValue == '' ? 'selected' : ''; ?>>--Select State--</option>
                                    <?php foreach ($states as $state): ?>
                                        <option value="<?= $state;?>" <?php echo (isset($filterValue) && $filterValue == $state) ? 'selected' : ''; ?>>
                                            <?php echo $state; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6 col-sm-2">
                                <button class="btn btn-primary" id="filterButton" type="submit">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row2">
        <div class="col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Registration List</h5>
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
                                <th>Father Name</th>
                                <th>Email</th>
                                <th>Phone No.</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $("#preloadercustom").hide();
                $(".myspin").hide();

                var registrations = <?php echo json_encode(isset($registrations) ? $registrations : []); ?>;

                var table = $('.dataTables-example').DataTable({
                    layout: {
                        topStart: {
                            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
                        }
                    },
                    responsive: true,
                    data: registrations,
                    columns: [{
                            data: "id"
                        },
                        {
                            data: "name"
                        },
                        {
                            data: "father_name"
                        },
                        {
                            data: "email"
                        },
                        {
                            data: "phone"
                        },
                        {
                            render: function(data, type, row) {
                                return "<a href='/admin/registrations/detail/" + row["id"] + "'>link</a>";
                            }
                        }
                    ]
                });

                // function getOption() {
                //     let state = $('.form-select').val();
                //     if (state !== '') {
                //         $.ajax({
                //             type: "GET",
                //             url: `<?php //echo base_url('/admin/registrations/fetch-registrations'); ?>?filterType=state&filterValue=${state}`,
                //             dataType: "json",
                //             contentType: "application/json",
                //             cache: false,
                //         }).done(function(data) {
                //             if (data.status == '200') {
                //                 if (Array.isArray(data.result) && data.result.length > 0) {
                //                     table.clear();
                //                     table.rows.add(data.result).draw();
                //                 } else {
                //                     table.clear().draw();
                //                     alert('No registrations found for selected state.');
                //                 }
                //             } else {
                //                 alert('Failed to fetch data from server.');
                //             }
                //         }).fail(function(xhr, status, error) {
                //             console.log("AJAX Error:", xhr.responseText);
                //             alert('Something went wrong while fetching data.');
                //         });
                //     } else {
                //         table.clear();
                //         table.rows.add(registrations).draw();
                //     }
                // }

                // $(".form-select").on("change", function() {
                //     getOption();
                // });

            });
        </script>