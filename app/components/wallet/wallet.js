//blank line is required
app.controller('wallet',['$scope','$rootScope','$http',function($scope,$rootScope,$http){
	rootUrl=$rootScope.site_url;
	walmodule="wallet_back";
	$http.get(rootUrl+walmodule+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});	
	
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
	
	
	
	$scope.pac_id={};
	$scope.w={};
	$scope.rt="rt";
	$scope.ms="ms";
	$scope.ds="ds";
	
//	if($scope.w.wal_bal==' '){$scope.w.wal_bal=0;}
//	if($scope.w.net_wal==' '){$scope.w.net_wal=0;}
	
	$scope.loader=function()
	{
		$http.get(rootUrl+module+"/view").success(function(data)
		{
			$scope.datadb=data;
		})
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
	
	$scope.calc_wallet=function(id,pac)
	{
		$scope.mem_typ=pac;
		$http.get(rootUrl+"wallet_back/fetch_wallet?id="+id+"&mem_typ="+$scope.mem_typ).success(function(data)
		{
			if(data.error==1)
			{
				$scope.w.wal_bal=0;
//				$("#wal_bal").val(0);
			}
			else
			{
				$scope.w.wal_bal=data[0].net_wal;
//				$scope.w.wal_bal=data[data.length-1].net_wal;
			}
		})
	}
	
	$scope.calc_net=function(wbal,add_min,val)
	{
		 $scope.wbal=parseFloat(wbal);
		 $scope.add_min=parseFloat(add_min);
		if(val==1)
		{
			if($scope.wbal>0 && $scope.wbal>$scope.add_min)
			{
				$scope.add=parseFloat($scope.wbal)-parseFloat($scope.add_min);
				$("#net_wal").val($scope.add);
			}
			else
			{
				$("#net_wal").val(0);
			}
		}
		if(val==2)
		{
			if($scope.wbal>=0)
			{
				$scope.add=parseFloat($scope.wbal)+parseFloat($scope.add_min);
				$("#net_wal").val($scope.add);
			}
			else
			{
				$("#net_wal").val(0);
			}
		}
	}
	
	$scope.filter_new_wal=function()
	{
		$scope.w={};
		$("#net_wal").val(0);
	}

	$scope.save_data_wallet=function(y)
	{
		$.ajax({
			type: "POST",
			url: rootUrl+walmodule+"/save",
			data: $("#walletform1").serialize(),
			beforeSend: function()
			{
				$('#submitbtnwal12').attr('disabled',true);
				$('#loaderwal12').css('display','inline');
			},
			success: function(data)
			{
				console.log(data);
				if(data=="1")
				{
					messages("success", "Success!","Wallet  Saved Successfully", 3000);
//					$scope.loader();
					$scope.filter_new_wal();
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", .3000);
				}
				else
				{
					messages("danger", "Warning!",data, 8000);
				}
				$('#loaderwal12').css('display','none');
				$('#submitbtnwal12').attr('disabled',false)
			}
		});
	}
	
	$scope.delete_data=function(id)
	{
		if(confirm("Deleting Grade may hamper your data associated with it."))
		{
			if(confirm("Are you Sure to DELETE ??"))
			{
				$http.get(rootUrl+module+"delete?id="+id).success(function(data){
					if(data=="1")
					{
						messages("success", "Success!","Grade Deleted Successfully", 3000);
					}
					else
					{
						messages("danger", "Warning!",data+", Grade not Deleted", 4000);
					}
					$scope.loader();
				})
			}
		}
	}
	
}]);