app.controller('package_assigner',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	pamodule="package_assigner_back/";
	$http.get(rootUrl+pamodule+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});	
	
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
	
	$http.get(rootUrl+"package_back/view?data=pac_id,package_name").success(function(data)
	{
		$scope.package_details=data;
	})
	if($scope.login=='Administrator')
	{
		$http.get(rootUrl+"admin_back/view?data=admin_id,name").success(function(data)
		{
			$scope.admin_details=data;
		})
	}
	if($scope.login=='Admin')
	{
		$http.get(rootUrl+"master_management_back/view?data=ms_id,name").success(function(data)
		{
			$scope.master_details=data;
		})
		$http.get(rootUrl+"distributor_management_back/view?data=ds_id,name").success(function(data)
		{
			$scope.dist_details=data;
		})
		$http.get(rootUrl+"retailer_management_back/view?data=rt_id,name").success(function(data)
		{
			$scope.ret_details=data;
		})
	}
	$scope.update_call=function(y)
	{
		$scope.sc=y;
	}
	
	$scope.filter_pac_ass=function()
	{
		$scope.pa={};
	}
	$scope.save_pack_ass=function()
	{
		$('#passignform').ajaxForm({
			type: "POST",
			url: rootUrl+"package_assigner_back/save",
			beforeSend: function()
			{
				$('#submitbtdpa1').attr('disabled',true);
				$('#loaderpa1').css('display','inline');
			},
			success: function(data)
			{
				console.log(data);
				if(data=="0"||data=="1")
				{
					messages("success", "Success!","Packages Assigned Successfully", 3000);
					$scope.filter_pac_ass();
				}
				else if(data.error==1)
				{
					messages("warning", "Failed",data.msg, 6000);
					$scope.filter_pac_ass();
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loaderpa1').css('display','none');
				$('#submitbtdpa1').attr('disabled',false);
			}
		});
	}
	
	
}]);