app.controller('coupon_request', ['$scope', '$rootScope', '$http', function($scope, $rootScope, $http){
	rootUrl = $rootScope.site_url;
	$scope.type = localStorage.getItem("usertype");
	if($scope.type == 'Master' || $scope.type == 'Distributor' || $scope.type == 'Retailer'){
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
	if($scope.type == 'Master' || $scope.type == 'Distributor' || $scope.type == 'Admin'){
	    $('#recharge101').remove();
	}
	if($scope.type == 'Retailer'){
	    $('#createpassword101').remove();
	    $('#member101').remove();
	}
	if($scope.type == 'Admin'){
		//$('#display1').remove();
	    $('#walletrequest101').remove();
	    $('#adminmaster101').remove();
		$('#adminwallet101').remove();
		$('#pancard_coupon101').remove();
	}
	$scope.init = function(){
		$http.get(rootUrl+"pancard_coupon_back/view_request?status=0").success(function(data){
			$scope.req_status = data.coupon_request_status;
			$scope.datawal = data;
		})
	}
	$scope.getNumber = function(num){
		return new Array(num);   
	}
	$scope.init();
	$scope.respond_request_coupon = function(y, status){
		var can_give = $("#can_give_"+y.pr_id).val();
		if(can_give == "" || typeof can_give == "undefined"){
			alert("Please select number of coupons.");	
			return false;
		}
		$.ajax({
			type		: "POST",
			url			: rootUrl+"pancard_coupon_back/update_request",
			data		: {"mem_id" : y.mem_id, "pr_id" :y.pr_id, "can_give" : can_give, "status" : status},
			beforeSend	: function(){
				$('.accept_request_'+y.pr_id).attr('disabled', true);
				$('#loaderwalr'+y.pr_id).css('display', 'inline');
			},
			success: function(data){
				if(data == "1"){
					messages("success", "Success!","Coupon added Successfully.", 3000);
					$('.accept_request_'+y.pr_id).attr('disabled', false);
				}else if(data == "0"){
					messages("warning", "Info!", "No Data Affected", 3000);
				}else{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loaderwalr'+y.pr_id).css('display', 'none');
				$('.accept_request_'+y.pr_id).attr('disabled', false);
			}
		});
	}
}]);