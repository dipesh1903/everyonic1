<html>
<head>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#table5').DataTable( 
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
   <table id="table5" class="table table-hover animated fadeIn">
    <thead>
        <tr style="background: #000011;color:#fff">
                <th>Employee Name</th>
                <th>Employee Grade</th>
                <th>Designation</th>
                <th>Month</th>
                <th>Year</th>
                <th>Total Working Days</th>
                <th>Total Present</th>
                <th>Total Absent</th>
                <th>Absent Deduction</th>
                <th>Total Deduction</th>
                <th>Total Payment</th>
                <th>Email</th>
                <th>Remarks</th>
            </tr>
    </thead>
    <tbody>
        <?=$table_data?>
    </tbody>
   </table>
</body>
</html>


