//blank line is required
app.controller('dashboard',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	dasmodule="dashboard/";
	
	$http.get(rootUrl+dasmodule+"index").success (function(data)
	{
		if(data==0){window.location.assign('login.html');}
	});
	
	if(localStorage.getItem("usertype")==null)
	{
	    console.log("null"+localStorage.getItem("usertype"));
	    console.log("null"+localStorage.getItem("com_id"));
	    setTimeout(function(){ window.location.reload(); }, 2000);
	}
	
	else
	{
	    
	    console.log("dashboard"+localStorage.getItem("usertype"));
    	console.log("dashboard"+localStorage.getItem("com_id"));
    	
    	$scope.type=localStorage.getItem("usertype");
    	$("#l_usertype").html(localStorage.getItem("usertype"));
    	$("#l_username1").html(localStorage.getItem("username"));
    	$("#l_usertype1").html(localStorage.getItem("usertype"));
    	$("#l_username2").html(localStorage.getItem("username"));
    
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
    		$('#newscenter101').remove();
    	}
    	if($scope.type =='Master'|| $scope.type =='Distributor'|| $scope.type=='Admin')
    	{
    	    $('#psa101').remove();
    	    $('#recharge101').remove();
			$('#pancard_coupon101').remove();
    	}
    	if( $scope.type=='Retailer')
    	{
    	    $('#display1').remove();
    	    $('#createpassword101').remove();
    	    $('#member101').remove();
    	}
    	if( $scope.type=='Admin')
    	{
    	    $('#psa101').remove();
    // 		$('#display1').remove();
    	    $('#walletrequest101').remove();
    	    $('#adminmaster101').remove();
    		$('#adminwallet101').remove();
			$('#pancard_coupon101').remove();
    	}
    	
    	
    	$scope.mem_id=localStorage.getItem("mem_id");
    	$scope.mem_typ=localStorage.getItem("usertype");
    	
    	$http.get(rootUrl+"dashboard/wallet_bal?id="+$scope.mem_id+"&mem_typ="+$scope.mem_typ).success(function(data)
    	{
    		$scope.$wallet_bal=data['wal_bal'];
    	});
    	if($scope.type == 'Admin'){
        	$http.get(rootUrl+"pancard_coupon_back/pancard_coupon_request?id="+$scope.mem_id+"&mem_typ="+$scope.mem_typ).success(function(data){
        		$scope.pancard_coupon_request = data;
    			var n = parseInt($("#responsecontainer1").html());
    			var tot = n + data;
    			if(data > 0){
    				$("#droppancardresponse").text(data).css({"background": "red"});
    			}
    			if(tot > 0){
    				$("#responsecontainer1").html(tot).css({"background": "red"});
    			}
        	});
    	}
		
		if($scope.type == 'Admin' || $scope.type == 'Master' || $scope.type == 'Distributor' || $scope.type == 'Retailer'){
			$http.get(rootUrl+"news_center_back/view_dash?usertype="+$scope.login).success(function(data){
				if(data == 0){
					$("#tx").remove();
				}else{
					$('#tx').telex({
						messages: data,
						delay: 0
					});
				}
			})
		}
    	
    	
    	/// For populating news
    	if($scope.type == 'Admin' || $scope.type == 'Master' || $scope.type == 'Distributor' || $scope.type == 'Retailer'){
			$http.get(rootUrl+"news_center_back/view_dash?usertype="+$scope.login).success(function(data){
				if(data == 0){
					$("#tx").remove();
				}else{
					$('#tx').telex({
						messages: data,
						delay: 0
					});
				}
			})
		}
    	
    	
    	if($scope.type=='Admin')
    	{
    		$http.get(rootUrl+"dashboard/admin_total_mem").success(function(data)
    		{
    			$scope.$total_mem=data['total_mem'];
    			$scope.$total_mas=data['master'];
    			$scope.$total_dis=data['distributor'];
    			$scope.$total_rt=data['retailer'];
    		})
    		$http.get(rootUrl+"dashboard/admin_mob_total").success(function(data)
    		{
    			$scope.$total_mob_total=data['mob_total'];
    			$scope.$total_mob_success=data['mob_success'];
    			$scope.$total_mob_failure=data['mob_fail'];
    		})
    		$http.get(rootUrl+"dashboard/admin_dth_total").success(function(data)
    		{
    			$scope.$total_dth_total=data['dth_total'];
    			$scope.$total_dth_success=data['dth_success'];
    			$scope.$total_dth_failure=data['dth_fail'];
    		})
    	}// admin if condition end here
    	
    	if($scope.type=='Master')
    	{
    		$http.get(rootUrl+"dashboard/mas_total_mem").success(function(data)
    		{
    			$scope.$total_mas_mem=data['mas_total'];
    			$scope.$total_mas_dis=data['mas_distri'];
    			$scope.$total_mas_rt=data['mas_retail'];
    		})
    		$http.get(rootUrl+"dashboard/mas_mob_total").success(function(data)
    		{
    			$scope.$mas_mob_total=data['mas_mob_total'];
    			$scope.$mas_mob_success=data['mas_mob_succ'];
    			$scope.$mas_mob_failure=data['mas_mob_fail'];
    		})
    		$http.get(rootUrl+"dashboard/mas_dth_total").success(function(data)
    		{
    			$scope.$mas_dth_total=data['mas_dth_total'];
    			$scope.$mas_dth_success=data['mas_dth_succ'];
    			$scope.$mas_dth_failure=data['mas_dth_fail'];
    		})
    	}
    	//distributor
    	if($scope.type=='Distributor')
    	{
    		$http.get(rootUrl+"dashboard/dis_total_mem").success(function(data)
    		{
    			$scope.$total_dis_rt=data['dis_retail'];
    		})
    		$http.get(rootUrl+"dashboard/dis_mob_total").success(function(data)
    		{
    			$scope.$dis_mob_total=data['dis_mob_total'];
    			$scope.$dis_mob_success=data['dis_mob_succ'];
    			$scope.$dis_mob_failure=data['dis_mob_fail'];
    		})
    		$http.get(rootUrl+"dashboard/dis_dth_total").success(function(data)
    		{
    			$scope.$dis_dth_total=data['dis_dth_total'];
    			$scope.$dis_dth_success=data['dis_dth_succ'];
    			$scope.$dis_dth_failure=data['dis_dth_fail'];
    		})
    	}
    	if($scope.type=='Retailer')
    	{
    		$http.get(rootUrl+"dashboard/ret_mob_total").success(function(data)
    		{
    			$scope.$ret_mob_total=data['ret_mob_total'];
    			$scope.$ret_mob_success=data['ret_mob_succ'];
    			$scope.$ret_mob_failure=data['ret_mob_fail'];
    		})
    		$http.get(rootUrl+"dashboard/ret_dth_total").success(function(data)
    		{
    			$scope.$ret_dth_total=data['ret_dth_total'];
    			$scope.$ret_dth_success=data['ret_dth_succ'];
    			$scope.$ret_dth_failure=data['ret_dth_fail'];
    		})
    		$http.get(rootUrl+"dashboard/ret_profile").success(function(data)
    		{
    			$scope.$ret_name=data['name'];
    		})
    	}
	    
	}
	
}]);