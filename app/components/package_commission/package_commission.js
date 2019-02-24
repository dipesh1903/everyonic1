app.controller('package_commission',['$scope','$rootScope','$http','$state','$stateParams',function($scope,$rootScope,$http,$state,$stateParams)
{
	rootUrl=$rootScope.site_url;
	commodule="package_back/";
	$http.get(rootUrl+commodule+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});		
	$scope.id = $stateParams.id;
	
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
	
	
	$scope.initp=function()
	{
		$http.get(rootUrl+commodule+"view?join=1&id="+$scope.id).success(function(data)
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
	
	$scope.save_commission=function(x)
	{
//		$scope.opcode=x;
		$('#commissionform'+x).ajaxForm({
			type: "POST",
			url: rootUrl+"packagecommission_back/save",
			beforeSend: function()
			{
				$('#submitbtdpac1'+x).attr('disabled',true);
				$('#loaderpac'+x).css('display','inline');
			},
			success: function(data)
			{
				if(data==1)
				{
					messages("success", "Success!","Package Commission Set Successfully", 3000);
					$scope.initp();
					$scope.filter_package();
				}
				else if(data==0)
				{
					messages("warning", "Info!","No Data Affected", 3000);
				}
				else if(data.error==1)
				{
					messages("danger", "Warning!",data.msg, 6000);
				}
				else
				{
					
					messages("danger", "Warning!",data, 6000);
				}
				$('#loaderpac'+x).css('display','none');
				$('#submitbtdpac1'+x).attr('disabled',false);
			}
		});
	}
	
	$scope.delete_data=function(id)
	{
		if(confirm("Deleting documents may hamper your data associated with it."))
		{
			if(confirm("Are you Sure to DELETE ??"))
			{
				$http.get(rootUrl+pmodule+"/delete?ser_id="+id).success(function(data){
					if(data=="1")
					{
						messages("success", "Success!","Service Deleted Successfully", 3000);
					}
					else
					{
						messages("success", "success!","Service  Not Deleted", 4000);
					}
					$scope.init_mem();
				})
			}
		}
	}
	
	
	
	
}]);