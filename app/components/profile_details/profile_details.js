app.controller('profile_details', ['$scope', '$rootScope', '$http', function($scope, $rootScope, $http){
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
	}
	if($scope.login == 'Master'|| $scope.login == 'Distributor'|| $scope.login == 'Admin'){
	    $('#recharge101').remove();
	}
	if($scope.login == 'Retailer'){
	    $('#createpassword101').remove();
	    $('#member101').remove();
	}
	if($scope.login == 'Admin'){
		//$('#display1').remove();
	    $('#walletrequest101').remove();
	    $('#adminmaster101').remove();
		$('#adminwallet101').remove();
	}
	$scope.init = function(){
		$http.get(rootUrl+"profile_details_back/view?usertype="+$scope.login+"&mem_id="+$scope.mem_id).success(function(data){
		    console.log(data);
			$scope.m = data;
		})
	}
	$scope.init();
	$scope.p = {};
	$scope.filter_profile_data = function(){
		$scope.p = {};
	}
	$scope.save_profile_details = function(){
		$('#memberform_profile').ajaxForm({
			type: "POST",
			url: rootUrl+"profile_details_back/update?mem_id="+$scope.mem_id,
			beforeSend: function(){
				$('#saveprofiledetails').attr('disabled', true);
				$('.loadermas').css('display', 'inline');
			},
			success: function(data){
				if(data == "1"){
					messages("success", "Success!","Details Updated Successfully.", 3000);
					$scope.b = {};
					$scope.filter_bank_detials();
				}else if(data == "0"){
					messages("warning", "Info!", "No Data Affected", 3000);
					$scope.filter_bank_detials();
				}else{
					messages("danger", "Warning!",data, 6000);
				}
				$('.loadermas').css('display', 'none');
				$('#saveprofiledetails').attr('disabled', false);
			}
		});
	}
}]);