app.controller('package_manager',['$scope','$rootScope','$http','$state',function($scope,$rootScope,$http,$state)
{
	rootUrl=$rootScope.site_url;
	pmmodule="package_back/";
	
	$http.get(rootUrl+pmmodule+"/index").success (function(data) {if(data==0){window.location.assign('login.html');} else if(data==2){messages("success", "Privilege not assigned.", 1000);window.location.assign('index.html');}});
//	$scope.init_mob();
//	$scope.mempmodule="mobile_back";s

	
	
	$scope.initp=function()
	{
		$http.get(rootUrl+pmmodule+"view").success(function(data)
		{
			$scope.datadb=data;
//			console.log(data);
		})
	}
	$scope.initp();
	
	$http.get(rootUrl+"service_back/view").success(function(data)
	{
		
		$scope.service_details=data;
	})
	
	$scope.set=function(x)
	{
//		console.log(x);
		$scope.p=x.id;
		$state.go("package_commission", { id: $scope.p });
	}
	
	$scope.update_call=function(y)
	{
		$scope.pac=y;
	}
	
	$scope.filter_package=function()
	{
		$scope.pac={};
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