<html>
<head>
    <script type="text/javascript">
$(document).ready(function() 
{
    $('#table2').DataTable( 
    	    {
            	dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    		} 
    	);
} );
</script>

<style>
@media print
{
    .linearea
    {
        height:330px;    
    }
}

</style>                    
  <br><br> <br>
</head>
<body>
    <div class="col-sm-2">
        <label style="color: orange">Total Attendance</label><?=$total_att?> 
    </div>
    <div class="col-sm-2">
        <label style="color: green">Total Present</label><?=$pre?> 
    </div>
    <div class="col-sm-2">
        <label style="color: red">Total Absent</label><?=$abs?> 
    </div>
    <table id="table2" class="table table-hover animated fadeIn display">
        <thead>
            <tr style="background: #000011;color:#fff">
                <th>Attendance Date</th>
                <th>Employee Grade</th>
                <th>Employee Name</th>
                <th>Duration</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?=$table_data?>
        </tbody>
    </table>
</body>
<script>
console.log({{module}});
// $.get('http://localhost/hrmaster/
</script>
   <div class="modal fade" id="image_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{hr_staff_details}} <small>Documents</small></h4>
      </div>
      <div class="modal-body" id="staff_data"></div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
</html>


                          


