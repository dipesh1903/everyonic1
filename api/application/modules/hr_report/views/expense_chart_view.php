<html>
<head>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#table3').DataTable( 
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
    <div class="col-sm-2">
        <label style="color: orange">Total Rows</label><?=$total_rows?> 
    </div>
    <div class="col-sm-2">
        <label style="color: red">Total Debit</label><?=$dr_total?> 
    </div>
    <div class="col-sm-2">
        <label style="color: red">Total Credit</label><?=$cr_total?> 
    </div>
    <table id="table3" class="table table-hover animated fadeIn display">
        <thead>
          <tr style="background: #000011;color:#fff">
                <th>Employee Name</th>
                <th>Employee Grade</th>
                <th>Title</th>
                <th>Credit</th>
                <th>Debit</th>
                <th>Description</th>
            </tr>  
        </thead>
        <tbody>
           <?=$table_data?> 
        </tbody>
    </table>
</body>
</html>

