<?php
if (isset($registrations)) {
  $registrations = $registrations;
}
?>
<div class="row">
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
              <th>Status</th>
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

    var registrations = <?php echo isset($registrations) && count($registrations) > 0 ? json_encode($registrations) : "[]" ?>;

    $('.dataTables-example').DataTable({
      responsive: true,
      data: registrations,
      columns: [
        { data: "id" },
        { data: "name" },
        { data: "father_name" },
        { data: "email" },
        { data: "phone" },
        { data: "status" },
        {
          data: null,
          render: function(data, type, row) {
            return "<a href='/admin/registrations/detail/" + row["id"] + "'>link</a>";
          }
        }
      ],
      rowCallback: function(row, data) {
        // console.log(data);

        if (data.status === 'Complete') {
          $(row).css('background-color', '#90EE90');
        }
        // } else if (data.status === 'Request') {
        //   $(row).css('background-color', '#FF8080');
         //} 
         else if (data.status === 'Save - Payment Pending') {
          $(row).css('background-color', '#FFFFE0');
        } else if (data.status === 'Save as Draft') {
          $(row).css('background-color', '#FF8080');
        }
      }
    });
  });
</script>
