app.controller('package',['$scope','$rootScope','$http','$state',function($scope,$rootScope,$http,$state)
{
	rootUrl=$rootScope.site_url;
	pmodule="package_back/";
	
	$http.get(rootUrl+pmodule+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});
	$scope.login=localStorage.getItem("usertype");
	
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
	
	$scope.initp=function()
	{
		$http.get(rootUrl+pmodule+"view").success(function(data)
		{
			$scope.datadb=data;
		})
	}
	$scope.initp();
	
	$http.get(rootUrl+"service_back/view").success(function(data)
	{
		$scope.service_details=data;
	})
	
	$scope.update_call=function(y)
	{
		$scope.pac=y;
	}
	
	$scope.filter_package=function()
	{
		$scope.pac={};
	}
	
	$scope.set=function(x)
	{
		$scope.p=x.pid;
		$state.go("package_commission", { id: $scope.p });
	}
	$scope.save_package_data=function()
	{
		$('#packageform').ajaxForm({
			type: "POST",
			url: rootUrl+"package_back/save",
			beforeSend: function()
			{
				$('#submitbtdp1').attr('disabled',true);
				$('#loaderp1').css('display','inline');
			},
			success: function(data)
			{
				if(data=="1")
				{
					messages("success", "Success!"," Saved Successfully", 3000);
					$scope.initp();
					$scope.filter_package();
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 3000);
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loaderp1').css('display','none');
				$('#submitbtdp1').attr('disabled',false);
			}
		});
	}
	
	$scope.delete_data=function(id)
	{
		if(confirm("Deleting documents may hamper your data associated with it."))
		{
			if(confirm("Are you Sure to DELETE ??"))
			{
				$http.get(rootUrl+pmodule+"delete?pac_id="+id).success(function(data)
				{
					if(data=="1")
					{
						messages("success", "Success!","Package Deleted Successfully", 3000);
					}
					else
					{
						messages("warning", "Warning!","Package  Not Deleted", 4000);
					}
					$scope.initp();
				})
			}
		}
	}
	
	
	
	
}]);