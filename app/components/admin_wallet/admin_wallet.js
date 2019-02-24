app.controller('admin_wallet',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	adminwallet_module="admin_wallet_back";
	
	$scope.wallet=0;
	$http.get(rootUrl+adminwallet_module+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});
	
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
	
	
	$scope.loader=function()
	{
		$http.get(rootUrl+"admin_wallet_back/view").success(function(data)
		{
			$scope.datadb=data;
		})
	}
	$scope.loader();
	
	$scope.calc_adwallet=function(admin_id)
	{
		console.log(admin_id);
		$scope.wallet=1;
		$http.get(rootUrl+"admin_back/fetch_admin_wallet?admin_id="+admin_id).success(function(data)
		{
			if(data=='0')
				$scope.ad.wal_bal1=0;
			else
				$scope.ad.wal_bal1=data[0].wal_bal;
		})
	}
	
	$http.get(rootUrl+"admin_back/view?data=admin_id,name").success(function(data)
	{
		$scope.admin_details=data;
	})
	
	$scope.update_call_admin=function(y)
	{
		$scope.a=y;
		$("#addform").trigger('click');
	}
	
	$scope.ad={};
	$scope.filter_adminwallet=function()
	{
		$scope.ad={};
	}
	
	$scope.save_adwal_data=function()
	{
		$('#adminwalform').ajaxForm({
			type: "POST",
			url: rootUrl+"admin_wallet_back/save",
			beforeSend: function()
			{
				$('#submitbtd1adwal').attr('disabled',true);
				$('#loader1adwal').css('display','inline');
			},
			success: function(data)
			{
				console.log(data);
				if(data=="1")
				{
					messages("success", "Success!","Saved Successfully", 3000);
					$scope.filter_adminwallet();
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 3000);
					$scope.filter_adminwallet();
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loader1adwal').css('display','none');
				$('#submitbtd1adwal').attr('disabled',false);
			}
		});
	}
	
	$scope.delete_data=function(id)
	{
		if(confirm("Deleting Staff Details may hamper your data associated with it."))
		{
			if(confirm("Are you Sure to DELETE ??"))
			{
				$http.get(rootUrl+"admin_back/delete?admin_id="+id).success(function(data){
					if(data=="1")
					{
						messages("success", "Success!","Admin Deleted Successfully", 3000);
					}
					else
					{
						messages("danger", "Warning!","Admin not Deleted. "+data, 4000);
					}
				})
			}
			$scope.loader();
		}
	}
	
	
}]);