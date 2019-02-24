<div class="col-md-12 myhead" style="margin-bottom: -10px; margin-top: 0px">
	<div class="col-md-3">
		<label>Customer Number</label>
		<input ng-model="r.cust_no" name="cust_no" class="form-control">
	</div>
	<div class="col-md-3">
		<label>Transact ID Number</label>
		<input ng-model="r.trans_id" name="trans_id" class="form-control">
	</div>
	<div class="col-sm-3">
		<label>Service Code</label>
			<select name="sercode" ng-model="r.sercode" class="form-control">
				<option value="">Select Operator</option>
                <option value="AR">Airtel</option>
                <option value="BS">BSNL</option>
                <option value="ID">Idea</option>
                <option value="VF">Vodafone</option>
                <option value="RJ">Reliance JIO</option>
                <option value="TD">Tata Indicom</option>
                <option value="TI">Tata Docomo</option>
                <option value="AI">Aircel</option>
                <option value="TE">Telenor</option>
                <option value="VG">Virgin GSM</option>
                <option value="VC">VIRGIN CDMA</option>
                <option value="MTS">MTS</option>
			</select>
	</div>
</div>
<div id="webprogress" style="display: none">
    	<img id="loader" src="./assets/images/loaders/1.gif" style="height:25px;display:none">
</div>
