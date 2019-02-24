app.controller('wallet_request',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	walrequest_module='wallet_request_back';
	
	$http.get(rootUrl+walrequest_module+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});	
	
	$scope.type=localStorage.getItem("usertype");
	if($scope.type =='Master'|| $scope.type =='Distributor'|| $scope.type=='Retailer')
	{
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
	if($scope.type =='Master'|| $scope.type =='Distributor'|| $scope.type=='Admin')
	{
	    $('#recharge101').remove();
	}
	if( $scope.type=='Retailer')
	{
	    $('#createpassword101').remove();
	    $('#member101').remove();
	}
	if( $scope.type=='Admin')
	{
		$('#display1').remove();
	    $('#walletrequest101').remove();
	    $('#adminmaster101').remove();
		$('#adminwallet101').remove();
	}
	
	
	
	$scope.wr={};
	$scope.filter_wr_data=function()
	{
		$scope.wr={};
	}
	
	$scope.save_wr_data=function()
	{
		$('#wqform').ajaxForm({
			type: "POST",
			url: rootUrl+"wallet_request_back/save",
			beforeSend: function()
			{
				$('#submitbtn1wreq').attr('disabled',true);
				$('#loader1wreq').css('display','inline');
			},
			success: function(data)
			{
				if(data=="1")
				{
					messages("success", "Success!","Saved Successfully", 3000);
					$scope.filter_wr_data();
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 3000);
					$scope.filter_wr_data();
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loader1wreq').css('display','none');
				$('#submitbtn1wreq').attr('disabled',false);
			}
		});
	}
	
	
	
	
	
}]);