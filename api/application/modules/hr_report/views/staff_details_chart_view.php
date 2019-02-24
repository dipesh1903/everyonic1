<html>
<head>
 <script type="text/javascript">
$(document).ready(function() 
{
    $('#table6').DataTable( 
    	    {
            	dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    		} 
    	);
} );
</script>
<script>
$(function () {
  $("[data-toggle='tooltip']").tooltip()
})
</script>
<style>
@media print{
    .linearea{
        height:330px;    
    }
}
</style>                    
<br><br> <br> 
</head>
<body>
    <div class="col-sm-2">
        <label style="color: orange">Total Rows</label><?=$total_rows?> 
    </div>
    <table id="table6" class="table table-hover animated fadeIn">
        <thead>
            <tr style="background: #000011;color:#fff">
                <th>Employee Name</th>
                <th>Employee Grade</th>
                <th>Designation</th>
                <th>Basic Salary</th>
                <th>Father's Name</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>City</th>
                <th>State</th>
                <th>Contact No</th>
                <th>Email Address</th>
            </tr>
        </thead>
        <tbody>
          <?=$table_data?>  
        </tbody>
    </table>
</body>
</html>
