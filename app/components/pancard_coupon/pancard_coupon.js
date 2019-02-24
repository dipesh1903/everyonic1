app.controller('pancard_coupon', ['$scope', '$rootScope', '$http', function($scope, $rootScope, $http){
	rootUrl = $rootScope.site_url; 
	pancard_coupon = "pancard_coupon_back";
	$http.get(rootUrl+pancard_coupon+"/index").success(function(data){
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
		$('#pancard_coupon101').remove();
		
	}
	$scope.y = {};
	$scope.filter_coupon_request_details = function(){
		$scope.y = {};
	}
	if($scope.login == 'Retailer'){
		$http.get(rootUrl+"pancard_coupon_back/view?mem_id="+$scope.mem_id+"&usertype="+$scope.login).success(function(data){
			$scope.coupon_requests = data;
			$scope.coupon_requests.ut = $scope.login;
		})
	}
	$scope.update_request_details = function(y){
		$http.get(rootUrl+"pancard_coupon_back/view_single?id="+y.rt_id).success(function(data){
			$scope.y = y;
			$("#make_coupon_request").trigger('click');
		})
	}
	// checking whether user is admin/administrator/master/etc
	$scope.coupon_request_details_save = function(e){
		if($scope.login == 'Master'){
			var mem_typ = "ms";
		}else if($scope.login == 'Distributor'){
			var mem_typ = "ds";
		}else{
			var mem_typ = "rt";
		}
		$('#coupon_request_details_form').ajaxForm({
			type: "POST",
			url: rootUrl+"pancard_coupon_back/save?mem_id="+$scope.mem_id+"&mem_typ="+mem_typ,
			beforeSend: function(){
				$('#coupon_request_details_button').attr('disabled', true);
				$('.loadermas').css('display', 'inline');
			},
			success: function(data){
				if(data == "1"){
					messages("success", "Success!","Coupon Request Added Successfully.", 3000);
					$scope.b = {};
					$scope.filter_coupon_request_details();
				}else if(data == "0"){
					messages("warning", "Info!", "No Data Affected", 3000);
					$scope.filter_coupon_request_details();
				}else{
					messages("danger", "Warning!",data, 6000);
				}
				$('.loadermas').css('display', 'none');
				$('#coupon_request_details_button').attr('disabled', false);
			}
		});
	}
}]);