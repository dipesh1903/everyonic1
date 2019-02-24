<html>
<head>
<script type="text/javascript">
$(document).ready(function() 
{
    $('#table7').DataTable( 
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
    <table id="table7" class="table table-hover animated fadeIn">
        <thead>
           <tr style="background: #000011;color:#fff">
                <th>Employee Name</th>
                <th>Employee Grade</th>
                <th>Task Title</th>
                <th>Comment</th>
                <th>Date</th>
                <th>Duration</th>
            </tr> 
        </thead>
        <tbody>
            <?=$table_data?>
        </tbody>
    </table>
</body>
</html>
