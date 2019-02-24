app.controller('service',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	sermodule="service_back/";
	
	$http.get(rootUrl+sermodule+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});	
	
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
	
	$scope.initser=function()
	{
		$http.get(rootUrl+sermodule+"/view").success(function(data)
		{
			$scope.datadb=data;
		})
	}
	$scope.initser();
	$scope.sc={};
	
	$scope.update_call_service=function(y)
	{
		$scope.sc=y;
	}
	
	$scope.filter_service=function()
	{
		$scope.sc={};
	}
	$scope.save_service_data=function()
	{
		$('#serviceform').ajaxForm({
			type: "POST",
			url: rootUrl+"service_back/save",
			beforeSend: function()
			{
				$('#submitbtds1').attr('disabled',true);
				$('#loaders1').css('display','inline');
			},
			success: function(data)
			{
				if(data=="1")
				{
					messages("success", "Success!","Services Saved Successfully", 3000);
					$scope.initser();
					$scope.filter_service();
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 3000);
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loaders1').css('display','none');
				$('#submitbtds1').attr('disabled',false);
			}
		});
	}
	
	$scope.delete_data=function(id)
	{
		if(confirm("Deleting documents may hamper your data associated with it."))
		{
			if(confirm("Are you Sure to DELETE ??"))
			{
				$http.get(rootUrl+sermodule+"/delete?ser_id="+id).success(function(data){
					if(data=="1")
					{
						messages("success", "Success!","Service Deleted Successfully", 3000);
					}
					else
					{
						messages("success", "success!","Service  Not Deleted", 4000);
					}
					$scope.initser();
				})
			}
		}
	}
	
	
	
	
}]);