<div class="col-md-12 myhead"
	style="margin-bottom: -10px; margin-top: 0px">
	<div class="col-md-3">
		<label>Grade Name</label> 
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
		<label>Attendance</label> 
		 <select ng-model="x.status" name="status" class="form-control" required>
			<option value="ALL">All</option>
			<option value="1">Present</option>
			<option value="0">Absent</option>
		</select>
	</div>
	<div class="col-md-3">
		<label>Chart Labels</label><br> <input type="checkbox"
			ng-model="x.showlabel" style="width: 20px; border: 0px; shadow: none"
			ng-true-value="'1'" ng-false-value="'0'">
	</div>
</div>
<div id="webprogress" style="display: none">
    	<img id="loader" src="./assets/images/loaders/1.gif" style="height:25px;display:none">
</div>
