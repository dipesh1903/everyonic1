app.controller('admin_master',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	admin_module="admin_back";
	
	$http.get(rootUrl+admin_module+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});
	
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
		$http.get(rootUrl+"admin_back/view").success(function(data)
		{
			$scope.datadb=data;
		})
	}
	$scope.loader();
	
	$scope.update_call_admin=function(y)
	{
		$scope.a=y;
		$("#addform").trigger('click');
	}
	
	
	$scope.filter_admin_data=function()
	{
		$scope.a={};
	}
	
	$scope.save_admin_data=function()
	{
		$('#adminform').ajaxForm({
			type: "POST",
			url: rootUrl+"admin_back/save",
			beforeSend: function()
			{
				$('#submitbtnad').attr('disabled',true);
				$('#loaderad').css('display','inline');
			},
			success: function(data)
			{
				console.log(data);
				if(data=="1")
				{
					messages("success", "Success!","Saved Successfully", 3000);
//					$scope.init();
//					$scope.filter_new_mob();
					$("#we_id").trigger('click');
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 3000);
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loaderad').css('display','none');
				$('#submitbtnad').attr('disabled',false);
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