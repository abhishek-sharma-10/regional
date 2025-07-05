<?php
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
                <form action="<?php echo base_url('/admin/report/category-wise-report'); ?>" method="POST">
                    <div class="row">
                        <div class="col-6 col-sm-3">
                            <input type="hidden" name="filterType" value="category"/>
                            <select class="form-control form-select-sm" name="filterValue">
                                <option value="">--Select--</option>
                                <option value="GEN" <?php echo isset($filterValue) && $filterValue == 'GEN' ? 'selected' : ''; ?>>GEN</option>
                                <option value="SC" <?php echo isset($filterValue) && $filterValue == 'SC' ? 'selected' : ''; ?>>SC</option>
                                <option value="ST" <?php echo isset($filterValue) && $filterValue == 'ST' ? 'selected' : ''; ?>>ST</option>
                                <option value="OBC (Creamy Layer)" <?php echo isset($filterValue) && $filterValue == 'OBC (Creamy Layer)' ? 'selected' : ''; ?>>OBC (Creamy Layer)</option>
                                <option value="OBC (Non-Creamy Layer)" <?php echo isset($filterValue) && $filterValue == 'OBC (Non-Creamy Layer)' ? 'selected' : ''; ?>>OBC (Non-Creamy Layer)</option>
                                <option value="EWS" <?php echo isset($filterValue) && $filterValue == 'EWS' ? 'selected' : ''; ?>>EWS</option>
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

<div class="row">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Category Wise Report List</h5>
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
                            <th>Category</th>
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

$(document).ready(function() {
    $("#preloadercustom").hide();
    $(".myspin").hide();

    // Initialize DataTable
    var registrations = <?php echo json_encode(isset($registrations) ? $registrations : []); ?>;
    var table = $('.dataTables-example').DataTable({
        responsive: true,
        layout: {
            topStart: {
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            }
        },
        data: registrations,
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "father_name" },
            { data: "email" },
            { data: "category" },
            { data: "phone" },
            {
                data: null,
                render: function(data, type, row) {
                    console.log(row.id);
                    return "<a href='/admin/registrations/detail/" + row.id + "'>link</a>";
                }
            }
        ]
    });

});
</script>
