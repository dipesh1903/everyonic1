app.controller('create_password',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url; 
	create_module="create_password_back";
	
	$http.get(rootUrl+create_module+"/index").success(function(data) 
	{
		if(data==0)
		{
			window.location.assign('login.html');
		} 
	});
	
	$scope.login=localStorage.getItem('usertype');
	$scope.mem_id=localStorage.getItem('mem_id');
	console.log($scope.mem_id);
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
	
	if($scope.login=="Admin")
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
	if($scope.login=="Master")
	{
		$http.get(rootUrl+"distributor_management_back/view?data=ds_id,name&ms_id="+$scope.mem_id).success(function(data)
		{
			$scope.dist_details=data;
		})
	}
	
	if($scope.login=="Distributor")
	{
		$http.get(rootUrl+"retailer_management_back/view?data=rt_id,name&ds_id="+$scope.mem_id).success(function(data)
		{
			$scope.ret_details=data;
		})
	}
	
	
	$scope.filter_pass=function()
	{
		$scope.cp={};
	}
	
	
	/* New function added by yunus for ajax submit */
	
	$scope.save_crpass = function(y){
		$('#passwordform').ajaxForm({
			type		: "POST",
			url			: rootUrl+"create_password_back/save",
			beforeSend	: function(){
				$('#submitbtnmas').attr('disabled',true);
				$('#loadercp1').css('display', 'inline');
			},
			success: function(data){
				if(data == 1){
					messages("success", "Success!", "User Name And Password Saved Successfully", 3000);
					$scope.x={};
					$scope.filter_pass();
				}else if(data == 0){
					messages("warning", "Info!","No Data Affected", 3000);
				}else{
					messages("danger", "Warning!", data, 6000);
				}
				$('#loadermas').css('display','none');
				$('#submitbtnmas').attr('disabled',false);
			}
		});	
	}
	
	
	
	$scope.save_crpass_old=function(y)
	{
		$.ajax({
			type: "POST",
			url: rootUrl+"create_password_back/save",
			data: $("#passwordform").serialize(),
//			xhrFields: { withCredentials: true },
			beforeSend: function()
			{
				$('#submitbtncp1').attr('disabled',true);
				$('#loadercp1').css('display','inline');
			},
			success: function(data)
			{
				if(data==1)
				{
					$scope.x={};                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
					messages("success", "Success!","Password Generated!!", 3000);
					$scope.filter_pass();
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
				$('#loadercp1').css('display','none');
				$('#submitbtncp1').attr('disabled',false);
			}
		});
	}

}]);