app.controller('dth_report',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url; 
	dthreportmodule="mobile_report_back";
	
	$http.get(rootUrl+dthreportmodule+"/index").success(function(data) 
	{
		if(data==0)
		{
			window.location.assign('login.html');
		} 
	});
	
	$scope.login=localStorage.getItem('usertype');
	$scope.mem_id=localStorage.getItem('mem_id');
	if($scope.login =='Master'|| $scope.login =='Distributor'|| $scope.login=='Retailer')
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
	if($scope.login =='Master'|| $scope.login =='Distributor'|| $scope.login=='Admin')
	{
	    $('#recharge101').remove();
	}
	if( $scope.login=='Retailer')
	{
	    $('#createpassword101').remove();
	    $('#member101').remove();
	}
	if( $scope.login=='Admin')
	{
		$('#display1').remove();
	    $('#walletrequest101').remove();
	    $('#adminmaster101').remove();
		$('#adminwallet101').remove();
	}
	
	if( $scope.login=='Admin')
	{
		$http.get(rootUrl+"dth_report_back/admin_dth_view").success(function(data)
		{
			$scope.datadb=data;
		});
	}
	if( $scope.login=='Master')
	{
		$http.get(rootUrl+"dth_report_back/mas_dth_view").success(function(data)
		{
			$scope.datadb=data;
		});
	}
	if( $scope.login=='Distributor')
	{
		$http.get(rootUrl+"dth_report_back/dis_dth_view").success(function(data)
		{
			$scope.datadb=data;
		});
	}
	if( $scope.login=='Retailer')
	{
		$http.get(rootUrl+"dth_report_back/ret_dth_view").success(function(data)
		{
			$scope.datadb=data;
		});
	}
	

}]);