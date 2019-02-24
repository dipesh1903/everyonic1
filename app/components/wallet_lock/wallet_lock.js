app.controller('wallet_lock',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	walloc_module="wallet_lock";
	$http.get(rootUrl+walloc_module+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});	
	
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
	
	$scope.filter_lock_wal=function()
	{
		$scope.wl={};
	}
			
	$scope.save_lock_wallet=function(y)
	{
	
		$.ajax({
			type: "POST",
			url: rootUrl+"wallet_lock/save",
			data: $("#walletlock").serialize(),
//			xhrFields: { withCredentials: true },
			beforeSend: function()
			{
				$('#submitbtnwl1').attr('disabled',true);
				$('#loaderwl1').css('display','inline');
			},
			success: function(data)
			{
				console.log(data);
				if(data=="0"||data=="1")
				{
//					$scope.x={};                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
					messages("success", "Success!","Wallet Loc  Saved Successfully", 3000);
					$scope.filter_lock_wal();
				}
				else if(data==0)
				{
					messages("warning", "Info!","No Data Affected", .3000);
					$scope.filter_lock_wal();
				}
				else
				{
					messages("danger", "Warning!",data, 8000);
				}
				$('#loaderwl1').css('display','none');
				$('#submitbtnwl1').attr('disabled',false);
			}
		});
		}
	
}]);