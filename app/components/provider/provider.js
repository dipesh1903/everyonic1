app.controller('provider',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	promodule="provider_back/";
	$http.get(rootUrl+promodule+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});	
	
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
	
	$scope.pr={};
	$http.get(rootUrl+"service_back/view?data=ser_id,service_name").success(function(data)
	{
		$scope.service=data;
	})
	$scope.filter_prv=function()
	{
		$scope.pr={};
	}
	
	$scope.initpro=function()
	{
		$http.get(rootUrl+promodule+"/view").success(function(data)
		{
			$scope.datadb=data;
		})
	}
	$scope.initpro();
	
	$scope.update_call=function(y)
	{
		$scope.pr=y;
	}
	
	$scope.prv_save=function()
	{
		$('#prvform').ajaxForm({
			type: "POST",
			url: rootUrl+"provider_back/save",
			beforeSend: function()
			{
				$('#submitbtn1pro').attr('disabled',true);
				$('#loader1pro').css('display','inline');
			},
			success: function(data)
			{
				if(data=="1")
				{
					messages("success", "Success!","Saved Successfully", 3000);
					$scope.initpro();
					$scope.filter_prv();
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 3000);
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loader1pro').css('display','none');
				$('#submitbtn1pro').attr('disabled',false);
			}
		});
	}
	
	$scope.delete_data=function(id)
	{
		if(confirm("Deleting documents may hamper your data associated with it."))
		{
			if(confirm("Are you Sure to DELETE ??"))
			{
				$http.get(rootUrl+promodule+"/delete?pro_id="+id).success(function(data)
				{
					if(data=="1")
					{
						messages("success", "Success!","Provider Deleted Successfully", 3000);
					}
					else
					{
						messages("danger", "Sorry!","Provider  Not Deleted", 4000);
					}
					$scope.initpro();
				})
			}
		}
	}
	
	
	
	
	
}]);