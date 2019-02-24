app.controller('master_management', ['$scope', '$rootScope', '$http', function($scope, $rootScope, $http){
	rootUrl = $rootScope.site_url;
	masmodule = "master_management_back";
	dismodule = "distributor_management_back";
	rtmodule = "retailer_management_back";
	//setting privileges
	$http.get(rootUrl+masmodule+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});
	$http.get(rootUrl+dismodule+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});
	$http.get(rootUrl+rtmodule+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});
	$scope.login = localStorage.getItem('usertype');
	$scope.mem_id = localStorage.getItem('mem_id');
	if($scope.login == 'Master' || $scope.login == 'Distributor' || $scope.login == 'Retailer'){
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
		$('#pancard_coupon101').remove();
	}
	if($scope.login == 'Master' || $scope.login == 'Distributor' || $scope.login == 'Admin'){
	    $('#recharge101').remove();
		$('#pancard_coupon101').remove();
	}
	if( $scope.login == 'Retailer'){
	    $('#createpassword101').remove();
	    $('#member101').remove();
	}
	if($scope.login == 'Admin'){
		$('#display1').remove();
	    $('#walletrequest101').remove();
	    $('#adminmaster101').remove();
		$('#adminwallet101').remove();
		$('#pancard_coupon101').remove();
	}
	// checking whether user is admin/administrator/master/etc
	if($scope.login == 'Admin'){
		$http.get(rootUrl+"master_management_back/view?data=ms_id,name").success(function(data){
			$scope.master_details = data;
		})
		$http.get(rootUrl+"distributor_management_back/view?data=ds_id,name").success(function(data){
			$scope.dist_details = data;
		})
	}	
	// showing data of master/distributor/retailer
	$scope.show_mem_detials = function(x){
		if(x == 'ms'){
			$http.get(rootUrl+"master_management_back/view").success(function(data){
				$scope.datamem = data;
			})
		}
		if(x == 'ds' && $scope.login == 'Master'){
			$http.get(rootUrl+"distributor_management_back/view?ms_id="+$scope.mem_id).success(function(data){
				$scope.datamem = data;
			})
		}
		if(x == 'ds' &&($scope.login == 'Admin')){
			$http.get(rootUrl+"distributor_management_back/view").success(function(data){
				$scope.datamem = data;
			})
		}
		if(x == 'rt' && $scope.login == 'Distributor'){
			$http.get(rootUrl+"retailer_management_back/view?ds_id="+$scope.mem_id).success(function(data){
				$scope.datamem = data;
			})
		}
		if(x == 'rt' && ($scope.login == 'Admin')){
			$http.get(rootUrl+"retailer_management_back/view").success(function(data){
				$scope.datamem = data;
			})
		}
	}
	// filter/Clearing the form
	$scope.mx = {};
	$scope.datamem = '';
	$scope.m = {};
	$scope.d = {};
	$scope.r = {};
	$scope.filter_mem_detials = function(){
		$scope.mx = {};
		$scope.datamem = '';
		$scope.m = {};
		$scope.d = {};
		$scope.r = {};
	}
	// updating master/distributor/retailer form
	$scope.update_call_mem = function(y){
		if(y.mem_typ == 'ms'){
			$http.get(rootUrl+"master_management_back/get_user_single?id="+y.ms_id).success(function(data){
				$scope.m = y;
				$scope.m.username = data[0].username;
				$scope.m.password = data[0].password;
				$("#addform12").trigger('click');
			})
		}
		if(y.mem_typ == 'ds'){
			$http.get(rootUrl+"distributor_management_back/get_user?id="+y.ds_id).success(function(data){
				$scope.d = y;
				if(data[0] == "" || typeof data[0] == "undefined"){
					alert("Username and Password not generated for this member. Please generate it first.");	
					return false;
				}
				$scope.d.username = data[0].username;
				$scope.d.password = data[0].password;
				$("#disform").trigger('click');
			})
		}
		if(y.mem_typ == 'rt'){
			$http.get(rootUrl+"retailer_management_back/get_user?id="+y.rt_id).success(function(data){
				$scope.r = y;
				if(data[0] == "" || typeof data[0] == "undefined"){
					alert("Username and Password not generated for this member. Please generate it first.");	
					return false;
				}
				$scope.r.username = data[0].username;
				$scope.r.password = data[0].password;
				$("#retform").trigger('click');
			})
		}
	}
	
	$scope.save_data_mem = function(){
		$('#memberform').ajaxForm({
			type			: "POST",
			url				: rootUrl+"master_management_back/save",
			beforeSend		: function(){
				$('#submitbtnmas').attr('disabled',true);
				$('#loadermas').css('display','inline');
			},
			success: function(data){
				if(data == 1){
					messages("success", "Success!", "Master Member Added Successfully", 3000);
					$scope.m = {};
					$scope.filter_mem_detials();
				}else if(data == 0){
					messages("warning", "Info!", "No Data Affected", 3000);
					$scope.filter_mem_detials();
				}else{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loadermas').css('display','none');
				$('#submitbtnmas').attr('disabled',false);
			}
		});
	}
	
	$scope.save_data_dis = function(){
		$('#distform').ajaxForm({
			type		: "POST",
			url			: rootUrl+"distributor_management_back/save",
			beforeSend	: function(){
				$('#submitbtn2dis').attr('disabled',true);
				$('#loader1dist').css('display','inline');
			},
			success: function(data){
				if(data == 1){
					messages("success", "Success!", "Distributor Member Added Successfully", 3000);
					$scope.filter_mem_detials();
					$scope.d = {};
				}else if(data == 0){
					messages("warning", "Info!", "No Data Affected", 3000);
					$scope.filter_mem_detials();
					$scope.d = {};
				}else{
					messages("danger", "Warning!", data, 6000);
				}
				$('#loader1dist').css('display','none');
				$('#submitbtn2dis').attr('disabled',false);
			}
		});
	}
	
	$scope.add_ret1 = function(){
		$('#retform1').ajaxForm({
			type		: "POST",
			url			: rootUrl+"retailer_management_back/save",
			beforeSend	: function(){
				$('#submitbtn3').attr('disabled',true);
				$('#loader3').css('display','inline');
			},
			success: function(data){
				if(data == 1){
					messages("success", "Success!", "Retailer Member Added Successfully", 3000);
					$scope.filter_mem_detials();
					$scope.r = {};
				}else if(data == 0){
					messages("warning", "Info!", "No Data Affected", 3000);
					$scope.r = {};
				}else{
					messages("danger", "Warning!", data, 6000);
				}
				$('#loader3').css('display','none');
				$('#submitbtn3').attr('disabled',false);
			}
		});
	}
	
	$scope.delete_data = function(id, mem_typ){
		if(confirm("Deleting documents may hamper your data associated with it.")){
			if(confirm("Are you Sure to DELETE ??")){
				if(mem_typ == 'ms'){
					$http.get(rootUrl+masmodule+"/delete?ms_id="+id).success(function(data){
						if(data == 1){
							messages("success", "Success!", "Master Deleted Successfully", 3000);
							$http.get(rootUrl+"master_management_back/view").success(function(data){
								$scope.datamem = data;
							});
						}else{
							messages("success", "success!", "Master Not Deleted", 4000);
						}
					});
				}
				if(mem_typ == 'ds'){
					$http.get(rootUrl+dismodule+"/delete?ds_id="+id).success(function(data){
						if(data == 1){
							messages("success", "Success!","Distributor Deleted Successfully", 3000);
							$http.get(rootUrl+"distributor_management_back/view").success(function(data){
								$scope.datamem = data;
							})
						}else{
							messages("success", "success!", "Distributor Not Deleted", 4000);
						}
					})
				}
				if(mem_typ == 'rt'){
					$http.get(rootUrl+rtmodule+"/delete?rt_id="+id).success(function(data){
						if(data == 1){
							messages("success", "Success!", "Retailer Deleted Successfully", 3000);
							$http.get(rootUrl+"retailer_management_back/view").success(function(data){
								$scope.datamem = data;
							})
						}else{
							messages("success", "success!","Retailer Not Deleted", 4000);
						}
					})
				}
			}
		}
	}
}]);