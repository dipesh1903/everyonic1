<div class="col-md-12 myhead"
	style="margin-bottom: -10px; margin-top: 0px">
	
	<div class="col-md-3">
		<label>Employee Name</label> 
		<select ng-model="x.emp_id" class="form-control" required>
			<option value="ALL">All</option>
			<option ng-repeat="e in employees" value="{{e.emp_id}}">{{e.snam}}</option>
		</select>
	</div>
	<div class="col-md-3">
        <label>Activity</label>
        <select ng-model="x.title" name="title" class="form-control">
            <option value="ALL">All</option>
            <option>Reminder</option>
            <option>Talk in Progress</option>
            <option>Interview</option>
            <option>Enquiry</option>
            <option>Finalizing Soon</option>
            <option>Send Quotations</option>
            <option>Comparing with others</option>
            <option>No Response - Multiple attempts</option>
            <option>Confirmed</option>
            <option>Appointment Fixed</option>
            <option>Need to Visit</option>
            <option>Not answered</option>
            <option>Call Back Later</option>
            <option>Call Cancelled</option>
            <option>Call Busy</option>
            <option>Not Interested</option>
            <option>Switch Off</option>
            <option>Invalid Phone Number</option>
            <option>Normal Followup</option>
            <option>Other</option>
        </select>
      </div>
	<div class="col-md-3">
        <label>Task Title</label>
        <select ng-model="x.task_title" name="task_title" class="form-control">
            <option value="ALL">All</option>
            <option ng-repeat="t in tasks" value="{{t.title}}">{{t.title}}</option>
        </select>
	</div>
</div>