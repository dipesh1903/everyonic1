app.controller('setup_commission', ['$scope', '$rootScope', '$http', function($scope, $rootScope, $http){
	rootUrl = $rootScope.site_url;
	$scope.login = localStorage.getItem('usertype');
	$scope.mem_id = localStorage.getItem('mem_id');
	if($scope.login == 'Master' || $scope.login == 'Distributor' || $scope.login == 'Retailer'){
		$('#display1').remove();
		$('#adminmaster101').remove();
		$('#adminwallet101').remove();
		$('#wallet101').remove();
		$('#walletloc101').remove();
		$('#walletreport101').remove();
		$('#service101').remove();
		$('#package101').remove();
		$('#packageassigner101').remove();
		$('#operator101').remove();
		$('#smssend101').remove();
		$('#setup_commision101').remove();
	}
	if($scope.login == 'Master'|| $scope.login == 'Distributor'|| $scope.login == 'Admin'){
	    $('#recharge101').remove();
	}
	if($scope.login == 'Retailer'){
	    $('#createpassword101').remove();
	    $('#member101').remove();
	}
	if($scope.login == 'Admin'){
	    $('#walletrequest101').remove();
	    $('#adminmaster101').remove();
		$('#adminwallet101').remove();
	}
	if($scope.login == 'Admin'){
		$http.get(rootUrl+"setup_commission_back/view?mem_id="+$scope.mem_id+"&usertype="+$scope.login).success(function(data){
			$scope.commission_details = data;
		})
	}
	$scope.m = {};
	$scope.clear_commission_data = function()	{
		$scope.m = {};
	}
	$scope.save_commission_details = function(){
		$('#set_commission_form').ajaxForm({
			type		: "POST",
			url			: rootUrl+"setup_commission_back/save",
			beforeSend	: function(){
				$('#savecommissiondetails').attr('disabled', true);
				$('.loadermas').css('display', 'inline');
			},
			success: function(data){
				if(data == "1"){
					messages("success", "Success!","Commission Details Added/Updated Successfully.", 3000);
					$scope.m = {};
					$scope.clear_commission_data();
					location.reload();
				}else if(data == "0"){
					messages("warning", "Info!", "No Data Affected", 3000);
					$scope.clear_commission_data();
				}else{
					messages("danger", "Warning!",data, 6000);
				}
				$('.loadermas').css('display', 'none');
				$('#savecommissiondetails').attr('disabled', false);
			}
		});
	}
}]);