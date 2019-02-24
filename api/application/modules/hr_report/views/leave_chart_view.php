<html>
<head>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#table4').DataTable( 
    	    {
            	dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    		} 
    	);
} );
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
    <table id="table4" class="table table-hover animated fadeIn display">
        <thead>
           <tr style="background: #000011;color:#fff">
                <th>Employee Name</th>
                <th>Employee Grade</th>
                <th>Leave Type</th>
                <th>Leave From</th>
                <th>Leave To</th>
                <th>No Of Days</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            <?=$table_data?>
        </tbody>
    </table>
</body>
</html>
