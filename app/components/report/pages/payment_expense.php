<div class="col-md-12 myhead"
	style="margin-bottom: -10px; margin-top: 0px">
	<div class="col-md-3">
		<label>Type</label>
		<select name="type2" ng-model="x.type2" class="form-control" ng-change="fetch_head_list(x.type2)">
			<option value="">--Select Type--</option>
			<option value="All">All</option>
			<option value="OW">Own Office</option>
			<option value="EM">Employee</option>
		</select>
	</div>
	  <div ng-show="x.type2 == 'EM'">
          <div class="col-md-3">
            <label>Employee</label>
            <select ng-model="x.emp_id" name="emp_id" class="form-control">
                <option value="ALL">All</option>
                <option ng-repeat="e in employees" value="{{e.emp_id}}">{{e.snam}} | {{e.eid}}</option>
            </select>
          </div>
      </div>
      <div ng-show="x.type2 == 'OW'">
          <div class="col-md-3">
            <label>Category</label>
              <select ng-model="x.cat_id" name="cat_id"  class="form-control">
                <option value="ALL">All</option>
                <option ng-repeat="ca in categories" value="{{ca.cat_id}}">{{ca.name}}</option>
              </select>
          </div>
      </div>
      <div class="col-md-3">
            <label>Transaction Type</label>
              <select ng-model="x.tran_type" name="tran_type" class="form-control">
                <option value="ALL">All</option>
                <option value="c">Credit</option>
                <option value="d">Debit</option>
              </select>
          </div>
	<div class="col-md-3">
		<label>Chart Labels</label><br> 
		  <input type="checkbox" ng-model="x.showlabel" style="width: 20px; border: 0px; shadow: none" 
		      ng-true-value="'1'" ng-false-value="'0'">
	</div>
</div>