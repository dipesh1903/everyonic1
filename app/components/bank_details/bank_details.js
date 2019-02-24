app.controller('bank_details', ['$scope', '$rootScope', '$http', function($scope, $rootScope, $http){
	rootUrl = $rootScope.site_url; 
	bankdetailsmodule = "bank_details_back";
	$http.get(rootUrl+bankdetailsmodule+"/index").success(function(data){
		if(data == 0){
			window.location.assign('login.html');
		} 
	});
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
	// checking whether user is admin/administrator/master/etc
	if($scope.login == 'Admin'){
		$http.get(rootUrl+"bank_details_back/view?mem_id="+$scope.mem_id+"&usertype="+$scope.login).success(function(data){
			$scope.master_details = data;
			$scope.master_details.ut = $scope.login;
		})
	}
	if($scope.login == 'Retailer' || $scope.login == 'Distributor'){
		$http.get(rootUrl+"bank_details_back/get_bank_details_all").success(function(data){
			$scope.all_bank_details = data;
			$("#show_all_bank_details").show();
		});
	}
	// updating master/distributor/retailer form
	$scope.update_bank_details = function(y, utype){
		if(utype == 'Admin'){
			$http.get(rootUrl+"bank_details_back/get_bank_details?id="+y.bd_id).success(function(data){
				$scope.y = y;
				$("#addbankdetails").trigger('click');
			})
		}
	}
	// filter/Clearing the form
	$scope.y = {};
	$scope.filter_bank_detials = function()	{
		$scope.y = {};
	}
	$scope.clear_bank_details = function(){
		//$scope.y = {};
	}
	$scope.save_bank_details = function(){
		$('#bank_details_form').ajaxForm({
			type: "POST",
			url: rootUrl+"bank_details_back/save",
			beforeSend: function(){
				$('#savebankdetails').attr('disabled', true);
				$('.loadermas').css('display', 'inline');
			},
			success: function(data){
				if(data == "1"){
					messages("success", "Success!","Bank Details Added Successfully.", 3000);
					$scope.b = {};
					$scope.filter_bank_detials();
				}else if(data == "0"){
					messages("warning", "Info!", "No Data Affected", 3000);
					$scope.filter_bank_detials();
				}else{
					messages("danger", "Warning!",data, 6000);
				}
				$('.loadermas').css('display', 'none');
				$('#savebankdetails').attr('disabled', false);
			}
		});
	}
}]);