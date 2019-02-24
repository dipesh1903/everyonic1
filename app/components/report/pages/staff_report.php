<div class="col-md-12 myhead" style="margin-bottom: -10px; margin-top: 0px">
	<form name="salaryform1" id="salaryform1" ng-submit="salarywise_filter(x)" autocomplete="off">
        <div class="row">
    	   <div class="col-md-3">
        		<label>Grade Name</label> 
        		<select ng-model="x.grade" name="grade" class="form-control" ng-change="fetch_employee(x.grade)" >
        			<option value="ALL">All</option>
        			<option ng-repeat="g in grades" value="{{g.grade}}">{{g.grade}}</option>
        		</select>
    	   </div>
        	<div class="col-md-3">
        		<label>Employee Name</label> 
        		<select name="emp_id" class="form-control" ng-model="x.emp_id" >
        			<option value="ALL">All</option>
        			<option ng-repeat="e in employees" value="{{e.emp_id}}">{{e.snam}}</option>
        		</select>
        	</div>
        	<div class="col-md-3">
        		<label>Designation</label> 
        		 <select ng-model="x.designation" name="designation" class="form-control" >
        			<option value="ALL">All</option>
        			<option ng-repeat="d in departments" value="{{d.d_id}}">{{d.name}}</option>
        		</select>
        	</div>
        	<div class="col-md-3">
        		<label>Chart Labels</label><br> <input type="checkbox"
        			ng-model="x.showlabel" style="width: 20px; border: 0px; shadow: none"
        			ng-true-value="'1'" ng-false-value="'0'">
        	</div>
	    </div>
	    <div class="row">
    	   <div class="col-md-3">
               <div><label >Salary</label></div>
        	   <span class="col-sm-6"> 
        	       <input type="text" class="form-control" name="min_salary" ng-model="x.min_salary" placeholder="Minimum Salary" >
               </span>
               <span class="col-sm-6"> 
                    <input type="text" class="form-control" name="max_salary" ng-model="x.max_salary" placeholder="Maximum Salary" >
               </span> 
    	   </div>
    	   <div class="col-md-3">
        		<label>City</label> 
        		<input ng-model="x.city" name="city" class="form-control">
    	   </div>
    	   <div class="col-md-3">
        		<label>State</label> 
        		<input ng-model="x.state" name="state" class="form-control">
    	   </div>
        </div>
        <div class="clearfix"></div><br>
    	<div>
    	   <div id="webprogress" style="display: none">
                <img id="loader" src="./assets/images/loaders/1.gif" style="height:25px;display:none">
           </div>
    	   <input type="submit" class="btn btn-success btn-xs" id="submitbtn2"  value="Submit">
    	</div>	   
	</form>
</div>
<div class="clearfix"></div><br><br>
