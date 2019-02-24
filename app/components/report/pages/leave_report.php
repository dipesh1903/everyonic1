<div class="col-md-12 myhead"
	style="margin-bottom: -10px; margin-top: 0px">
	<div class="col-md-3">
		<label>Grade Name </label>
		 <select ng-model="x.grade" name="grade" class="form-control" ng-change="fetch_employee(x.grade)" required>
			<option value="ALL">All</option>
			<option ng-repeat="g in grades" value="{{g.grade}}">{{g.grade}}</option>
		</select>
	</div>
	<div class="col-md-3">
		<label>Employee Name</label> 
		<select name="emp_id" class="form-control" ng-model="x.emp_id" required>
			 <option value="ALL">All</option>
			<option ng-repeat="e in employees" value="{{e.emp_id}}">{{e.snam}}</option>
		</select>
	</div>
	<div class="col-md-3">
		<label>Leave Type</label> <select ng-model="x.leave_type" name="leave_type" class="form-control" required>
		   <option value="ALL">All</option>
           <option value="1">Casual Leave</option>
           <option value="2">Sick Leave</option>
           <option value="3">Privileged / Earned Leave</option>
           <option value="4">Maternity Leave</option>
           <option value="5">Other</option>
		</select>
	</div>
</div>
<div id="webprogress" style="display: none">
    	<img id="loader" src="./assets/images/loaders/1.gif" style="height:25px;display:none">
</div>
