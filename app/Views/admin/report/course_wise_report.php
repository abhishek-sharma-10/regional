<?php
//var_dump($registrations);
if (isset($registrations)) {
    $registrations = $registrations;
}
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
                <h5>Filter</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form action="<?php echo base_url('/admin/report/course-wise-report'); ?>" method="POST">
                    <div class="row">
                        <div class="col-6 col-sm-3">
                            <input type="hidden" name="filterType" value="course"/>
                            <select class="form-control form-select-sm" name="filterValue">
                                    <option value="">--Select--</option>
                                    <option value="ITEP - B.Sc. B.Ed." <?php echo isset($filterValue) && $filterValue == 'ITEP - B.Sc. B.Ed.' ? 'selected' : ''; ?>>ITEP - B.Sc. B.Ed.</option>
                                    <option value="ITEP - B.A. B.Ed." <?php echo isset($filterValue) && $filterValue == 'ITEP - B.A. B.Ed.' ? 'selected' : ''; ?>>ITEP - B.A. B.Ed.</option>
                                    <option value="ITEP - B.Sc. B.Ed. & B.A. B.Ed." <?php echo isset($filterValue) && $filterValue == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.' ? 'selected' : ''; ?>>ITEP - B.Sc. B.Ed. & B.A. B.Ed.</option>
                            </select>
                        </div>
                        <div class="col-6 col-sm-2">
                            <button class="btn btn-primary" id="filterButton" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row2">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Subject Wise Filter List</h5>
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
                            <th>Course</th>
                            <th>Email</th>
                            <th>Phone No.</th>
                            <th>Action</th>
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
var table;

$(document).ready(function() {
    $("#preloadercustom").hide();
    $(".myspin").hide();

    // Initialize DataTable
    var registrations = <?php echo json_encode(isset($registrations) ? $registrations : []); ?>;
    table = $('.dataTables-example').DataTable({
        layout: {
            topStart: {
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            }
        },
        responsive: true,
        data: registrations,
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "father_name" },
            { data: "course" },
            { data: "email" },
            { data: "phone" },
            {
                data: null,
                render: function(data, type, row) {
                    return "<a href='/admin/registrations/detail/" + row.id + "'>link</a>";
                }
            }
        ]
    });

    // // Function to fetch data based on course selection
    // function getOption() {
    //     let course = $('.form-select').val();

    //     $.ajax({
    //         type: "GET",
    //         url: `<?php //echo base_url('/admin/registrations/fetch-registrations'); ?>?filterType=course&filterValue=${course}`,
    //         dataType: "json",
    //         contentType: "application/json",
    //         cache: false,
    //     }).done(function(data) {
    //         console.log(data.result);
    //         console.log(data);
    //         if (data.status == '200') {
    //             if (Array.isArray(data.result) && data.result.length > 0) {
    //                 table.clear();
    //                 table.rows.add(data.result).draw();
    //             } else {
    //                 table.clear().draw();
    //                 alert('No registrations found for selected course.');
    //             }
    //         } else {
    //             alert('Failed to fetch data from server.');
    //         }
    //     }).fail(function(xhr, status, error) {
    //         console.log("AJAX Error:", xhr.responseText);
    //         alert('Something went wrong while fetching data.');
    //     });
    // }

    // // Attach event listener to select dropdown
    // $(".form-select").on("change", function() {
    //     getOption();
    // });

});
</script>
