app.controller('recharge',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	mobmodule='mobile_back';
	mobmodule='dth_back';
	
	$http.get(rootUrl+mobmodule+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});	
	$http.get(rootUrl+mobmodule+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});

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
	
	
	
	$scope.m={};
	$scope.d={};
	
	$scope.filter_data_recharge=function()
	{
		$scope.m={};
		$scope.d={};
	}
	$scope.save_mob_data=function()
	{
		$('#mobform1').ajaxForm({
			type: "POST",
			url: rootUrl+"mobile_back/save",
			beforeSend: function()
			{
				$('#submitbtn1mob').attr('disabled',true);
				$('#loader1mob').css('display','inline');
			},
			success: function(data)
			{
				if(data.error==0)
				{
					messages("success", "Success!",data.msg,6000);
					$scope.filter_data_recharge();
				}
				else if(data.error==1)
				{
					messages("warning", "Failed!",data.msg, 6000);
					$scope.filter_data_recharge();
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 6000);
					$scope.filter_data_recharge();
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loader1mob').css('display','none');
				$('#submitbtn1mob').attr('disabled',false);
			}
		});
	}
	
	$scope.save_dth_data=function()
	{
		$('#dthform').ajaxForm({
			type: "POST",
			url: rootUrl+"dth_back/save",
			beforeSend: function()
			{
				$('#submitbtn1dth').attr('disabled',true);
				$('#loader1dth').css('display','inline');
			},
			success: function(data)
			{
				console.log(data);
				if(data.error==0)
				{
					messages("success", "Success!",data.msg,6000);
					$scope.filter_data_recharge();
				}
				else if(data.error==1)
				{
					messages("warning", "Failed",data.msg, 6000);
					$scope.filter_data_recharge();
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 6000);
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loader1dth').css('display','none');
				$('#submitbtn1dth').attr('disabled',false);
			}
		});
	}
	$scope.save_post_data=function()
	{
		$('#postform').ajaxForm({
			type: "POST",
			url: rootUrl+"mobile_back/postsave",
			beforeSend: function()
			{
				$('#submitbtnd1').attr('disabled',true);
				$('#loader1').css('display','inline');
			},
			success: function(data)
			{
				if(data=="1")
				{
					messages("success", "Success!","Saved Successfully", 3000);
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 3000);
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loader2').css('display','none');
				$('#submitbtn1').attr('disabled',false);
			}
		});
	}
	
	$("#DOB2").datepicker();
	$(".datepicker").datepicker();
	
	$scope.elc_save=function()
	{
		$('#ecform').ajaxForm({
			type: "POST",
			url: rootUrl+"elec_back/save",
			beforeSend: function()
			{
				$('#submitbtnec').attr('disabled',true);
				$('#loader3').css('display','inline');
			},
			success: function(data)
			{
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
				$('#loader3').css('display','none');
				$('#submitbtnec').attr('disabled',false);
			}
		});
	}
	
	$scope.ins_save=function()
	{
		$('#insform').ajaxForm({
			type: "POST",
			url: rootUrl+"ins_back/save",
			beforeSend: function()
			{
				$('#submitbtnins').attr('disabled',true);
				$('#loader4').css('display','inline');
			},
			success: function(data)
			{
				if(data=="1")
				{
					messages("success", "Success!","Saved Successfully", 3000);
					$scope.filter_ins();
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
				$('#loader4').css('display','none');
				$('#submitbtnins').attr('disabled',false);
			}
		});
	}
	
	$scope.save_dc_data=function()
	{
		$('#dcform').ajaxForm({
			type: "POST",
			url: rootUrl+"datacard_back/save",
			beforeSend: function()
			{
				$('#submitbtndc').attr('disabled',true);
				$('#loader2').css('display','inline');
			},
			success: function(data)
			{
				console.log(data);
				if(data=="1")
				{
					messages("success", "Success!","Saved Successfully", 3000);
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 3000);
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loader2').css('display','none');
				$('#submitbtn1').attr('disabled',false);
			}             
		});
	}
	
	
	$scope.save_land_data=function()
	{
		$('#landform').ajaxForm({
			type: "POST",
			url: rootUrl+"mobile_back/postsave",
			beforeSend: function()
			{
				$('#submitbtn7').attr('disabled',true);
				$('#loader2').css('display','inline');
			},
			success: function(data)
			{
				console.log(data);
				if(data=="1")
				{
					messages("success", "Success!","Saved Successfully", 3000);
//					$scope.init();
//					$scope.filter_new_mob();
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 3000);
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loader2').css('display','none');
				$('#submitbtn1').attr('disabled',false);
			}
		});
	}
	
	$scope.delete_data=function(id)
	{
		if(confirm("Deleting documents may hamper your data associated with it."))
		{
			if(confirm("Are you Sure to DELETE ??"))
			{
				$http.get(rootUrl+memmodule+"/delete?mm_id="+id).success(function(data){
					if(data=="1")
					{
						messages("success", "Success!","Member Deleted Successfully", 3000);
					}
					else
					{
						messages("success", "success!","Member  Deleted", 4000);
					}
					$scope.init_mem();
				})
			}
		}
	}
	
}]);