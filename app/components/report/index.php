<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/af-2.3.0/b-1.5.2/cr-1.5.0/fc-3.2.5/kt-2.4.0/r-2.2.2/rr-1.2.4/datatables.min.css"/>  
<!-- <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/af-2.3.0/b-1.5.2/cr-1.5.0/fc-3.2.5/kt-2.4.0/r-2.2.2/rr-1.2.4/datatables.min.js"></script> -->
<link  href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs-3.3.6/jq-2.2.3/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/af-2.1.2/b-1.2.2/b-colvis-1.2.2/b-flash-1.2.2/b-html5-1.2.2/b-print-1.2.2/cr-1.3.2/fc-3.2.2/fh-3.1.2/kt-2.1.3/r-2.1.0/rr-1.1.2/sc-1.4.2/se-1.2.0/datatables.min.css"/> 
 <script type="text/javascript" src="https://cdn.datatables.net/v/bs-3.3.6/jq-2.2.3/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/af-2.1.2/b-1.2.2/b-colvis-1.2.2/b-flash-1.2.2/b-html5-1.2.2/b-print-1.2.2/cr-1.3.2/fc-3.2.2/fh-3.1.2/kt-2.1.3/r-2.1.0/rr-1.1.2/sc-1.4.2/se-1.2.0/datatables.min.js"></script>

<div class="col-sm-12">
	<div class="col-sm-6">
		<input name="id" ng-model="x.type_id" hidden>
		<div>
			<div class="col-sm-5">
				<label><u>Select Report Type</u></label>
			</div>
			<div class="col-sm-7">
				<select ng-model="x.type" class="form-control">
					<option value="">--Select--</option>
					<option value="MOB">Mobile Recharge Report</option>
					<option value="LR">DTH Recharge Report</option>
<!-- 					<option value="PE">Payment & Expense</option> -->
<!-- 					<option value="FR">Follow_Up Report</option> -->
<!-- 					<option value="SR">Staff Report</option> -->
<!-- 					<option value="PR">Pay_Slip Report</option> -->
				</select>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="col-md-12">
		<div ng-show="x.type == 'MOB'">
		  <div ng-include="'./app/components/report/pages/mobile_report.php'"></div>
		</div>
		<div ng-show="x.type == 'PE'" >
		  <div ng-include="'./app/components/report/pages/payment_expense.php'"></div>
		</div>
		<div ng-show="x.type == 'LR'">
		  <div ng-include="'./app/components/report/pages/leave_report.php'"></div>
		</div>
		<div ng-show="x.type == 'FR'">
		  <div ng-include="'./app/components/report/pages/follow_up_report.php'"></div>
		</div>
		<div ng-show="x.type == 'SR'">
		  <div ng-include="'./app/components/report/pages/staff_report.php'"></div>
		</div>
		<div ng-show="x.type == 'PR'">
		  <div ng-include="'./app/components/report/pages/pay_slip_report.php'"></div>
		</div>
		
        <div id="webprogress" style="display: none">
        	<img id="loader" src="./assets/images/loaders/1.gif" style="height:25px;display:none">
        </div>
        <div class="cleMOBfix"></div>
        <div class="col-sm-4 myhead" ng-show="x.type=='MOB'||x.type=='PE'||x.type=='LR'||x.type=='FR'||x.type=='PR'">
        	<form name="dayform1" id="dayform1" ng-submit="daywise_filter(x)" autocomplete="off">
        		<h5 align="center">
        			<u>Date Wise Report</u>
        		</h5>
        	       <input ng-model="x.cust_no" name="cust_no" hidden> 
                   <input ng-model="x.trans_id" name="trans_id" hidden>
            	   <input ng-model="x.sercode" name="sercode" hidden> 
<!--             	   <input ng-model="x.leave_type" name="leave_type" hidden> -->
<!--             	   <input ng-model="x.type2" name="type2" hidden> -->
<!--             	   <input ng-model="x.type" name="type" hidden> -->
<!--             	   <input ng-model="x.cat_id" name="cat_id" hidden> -->
<!--             	   <input ng-model="x.tran_type" name="tran_type" hidden> -->
<!--             	   <input ng-model="x.showlabel" name="showlabel" hidden> -->
<!--             	   <input ng-model="x.f_id" name="f_id" hidden> -->
<!--             	   <input ng-model="x.task_title" name="task_title" hidden> -->
<!--             	   <input ng-model="x.designation" name="designation" hidden> -->
<!--             	   <input ng-model="x.min_salary" name="min_salary" hidden> -->
<!--             	   <input ng-model="x.max_salary" name="max_salary" hidden> -->
<!--             	   <input ng-model="x.city" name="city" hidden> -->
<!--             	   <input ng-model="x.state" name="state" hidden> -->
<!--             	   <input ng-model="x.title" name="title" hidden> -->
        		<span class="col-sm-9"> 
        		  <input class="form-control" name="day" id="DOB1" ng-model="x.day_date" placeholder="Select Date" required>
        		</span>
        		<span class="col-sm-2">
        		  <input type="submit" class="btn btn-success btn-xs" id="submitbtn" ng-disabled="dayform1.$invalid" value="Submit">
        		</span>
        	</form>
        </div>
    
        <div class="col-sm-3 myhead " ng-show="x.type=='MOB'||x.type=='PE'||x.type=='LR'||x.type=='FR'||x.type=='PR'">
        	<form name="monthform1" id="monthform1" ng-submit="monthwise_filter(x)">
        	    <input ng-model="x.cust_no" name="cust_no" hidden> 
                <input ng-model="x.trans_id" name="trans_id" hidden>
            	<input ng-model="x.sercode" name="sercode" hidden> 
<!--             	   <input ng-model="x.leave_type" name="leave_type" hidden> -->
<!--             	   <input ng-model="x.type2" name="type2" hidden> -->
<!--             	   <input ng-model="x.type" name="type" hidden> -->
<!--             	   <input ng-model="x.cat_id" name="cat_id" hidden> -->
<!--             	   <input ng-model="x.tran_type" name="tran_type" hidden> -->
<!--             	   <input ng-model="x.showlabel" name="showlabel" hidden> -->
<!--             	   <input ng-model="x.f_id" name="f_id" hidden> -->
<!--             	   <input ng-model="x.task_title" name="task_title" hidden> -->
<!--             	   <input ng-model="x.designation" name="designation" hidden> -->
<!--             	   <input ng-model="x.min_salary" name="min_salary" hidden> -->
<!--             	   <input ng-model="x.max_salary" name="max_salary" hidden> -->
<!--             	   <input ng-model="x.city" name="city" hidden> -->
<!--             	   <input ng-model="x.state" name="state" hidden> -->
<!--             	   <input ng-model="x.title" name="title" hidden> -->
        		<h5 align="center">
        			<u>Month Wise Report</u>
        		</h5>
        		<span class="col-sm-9"> <select class="form-control" name="month" ng-model="x.month" ng-submit="monthwise_filter(x)" required>
        				<option>--SELECT--</option>
        				<option>January</option>
        				<option>February</option>
        				<option>March</option>
        				<option>April</option>
        				<option>May</option>
        				<option>June</option>
        				<option>July</option>
        				<option>August</option>
        				<option>September</option>
        				<option>October</option>
        				<option>November</option>
        				<option>December</option>
        		</select>
        		</span> 
        		<span class="col-sm-2"> 
        		  <input type="submit" class="btn btn-success btn-xs" id="submitbtn2" ng-disabled="monthform1.$invalid" value="Submit">
        		</span>
        	</form>
        </div>
    
        <div class="col-sm-4 myhead" ng-show="x.type=='MOB'||x.type=='PE'||x.type=='LR'||x.type=='FR'||x.type=='PR'">
        	<form name="rangeform1" id="rangeform1" ng-submit="rangewise_filter(x)"  autocomplete="off">
        	       <input ng-model="x.grade" name="grade" hidden> 
                   <input ng-model="x.emp_id" name="emp_id" hidden>
            	   <input ng-model="x.status" name="status" hidden> 
            	   <input ng-model="x.leave_type" name="leave_type" hidden>
            	   <input ng-model="x.type2" name="type2" hidden>
            	   <input ng-model="x.type" name="type" hidden>
            	   <input ng-model="x.cat_id" name="cat_id" hidden>
            	   <input ng-model="x.tran_type" name="tran_type" hidden>
            	   <input ng-model="x.showlabel" name="showlabel" hidden>
            	   <input ng-model="x.f_id" name="f_id" hidden>
            	   <input ng-model="x.task_title" name="task_title" hidden>
            	   <input ng-model="x.designation" name="designation" hidden>
            	   <input ng-model="x.min_salary" name="min_salary" hidden>
            	   <input ng-model="x.max_salary" name="max_salary" hidden>
            	   <input ng-model="x.city" name="city" hidden>
            	   <input ng-model="x.state" name="state" hidden>
            	   <input ng-model="x.title" name="title" hidden>
        		<h5 align="center">
        			<u>Range Wise Report</u>
        		</h5>
        		<span class="col-sm-5"> 
        		  <input type="text" class="form-control" id="DOB3" name="sdate" ng-model="x.sdate" placeholder="Start Date" required>
        		</span>
        	    <span class="col-sm-5"> 
        	       <input type="text" class="form-control" id="DOB2" name="edate" ng-model="x.edate" placeholder="End Date" required>
        		</span> 
        		<span class="col-sm-2"> 
        		  <input type="submit" class="btn btn-success btn-xs" id="submitbtn2" ng-disabled="rangeform1.$invalid" value="Submit">
        		</span>
        	</form>
        </div>
        <div class="clearfix"></div>
        <hr style="border: 2px solid grey; margin-top: 5px;">
        <div class="clearfix"></div>
    
        <div id="printarea" style="background: white; margin-top: -30px">
        	<div id="header" style="display: none; top: 10px; margin-left: 10px; margin-right: 10px">
        		<h6 align="center">
        			<i><u>Report as on <b>{{label}}</b></u></i>
        		</h6>
        		<div class="pull-left" style="margin-left: 10px; margin-left: 10px">
                    Dated : <?=date('d/m/Y')?>
                </div>
        		<div class="pull-right" style="margin-left: 10px; margin-right: 10px">
                    Time : <?=date('H:i:s A')?>
                </div>
        	</div>
        	<div style="background: white;" id="result1">
        	   <br> <br> <br>
        	</div>
        </div>
        <div class="col-lg-12">
        	<br> <a class="pull-right btn btn-default" onclick="print()">Print</a>
        	<br> <br> <br> <br> <br> <br> <br> <br>
        </div>
	</div>
</div>
<style>
select {padding-left: 3px !important;}
.myhead {margin-top: 5px;}
input, select {height: 30px !important;padding-top: 2px !important;padding-bottom: 0px !important;}
label b {font-size: 2em;}
</style>

<script>
function modal_print()
{
    var DocumentContainer = document.getElementById('modal_print');
    var WindowObject = window.open("", "PrintWindow",
    "width=1300,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
    WindowObject.document.writeln(DocumentContainer.innerHTML);
    WindowObject.document.close();
    WindowObject.focus();
    WindowObject.print();
    WindowObject.close();
}
function PrintContent()
{
    var DocumentContainer = document.getElementById('printarea');
    var WindowObject = window.open("", "PrintWindow",
    "width=1300,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
    WindowObject.document.writeln(DocumentContainer.innerHTML);
    WindowObject.document.close();
    WindowObject.focus();
    WindowObject.print();
    WindowObject.close();
}


</script>