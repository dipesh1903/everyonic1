app.controller('news_center', ['$scope', '$rootScope', '$http', function($scope, $rootScope, $http){
	rootUrl = $rootScope.site_url; 
	bankdetailsmodule = "news_center_back";
	$http.get(rootUrl+bankdetailsmodule+"/index").success(function(data){
		if(data == 0){
			window.location.assign('login.html');
		} 
	});
	$("#ns_id").val("");
	$('#froala-editor').froalaEditor({
		height: 200,								 
	});
	
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
	}
	if($scope.login == 'Master'|| $scope.login == 'Distributor'|| $scope.login == 'Admin'){
	    $('#recharge101').remove();
	}
	if($scope.login == 'Retailer'){
	    $('#createpassword101').remove();
	    $('#member101').remove();
	}
	if($scope.login == 'Admin'){
		//$('#display1').remove();
	    $('#walletrequest101').remove();
	    $('#adminmaster101').remove();
		$('#adminwallet101').remove();
	}
	// checking whether user is admin/administrator/master/etc
	if($scope.login == 'Admin'){
		$http.get(rootUrl+"news_center_back/view?mem_id="+$scope.mem_id+"&usertype="+$scope.login).success(function(data){
			$scope.news_details = data;
			$scope.news_details.ut = $scope.login;
		})
	}
	
	$scope.update_news_details = function(y){
		if($scope.login == 'Admin'){	
			$("#froala-editor").froalaEditor('html.insert', y.news_text, true);
			$("#ns_id").val("").val(y.ns_id);
			if(y.news_status == 1){
				$("#show_this_news").attr("checked", true);
			}else{
				$("#show_this_news").attr("checked", false);	
			}
		}
	}
	// updating master/distributor/retailer form
	$scope.save_news = function(){
		if($scope.login == 'Admin'){
			var html = $("#froala-editor").froalaEditor('html.get', true);
			var ns_id = $("#ns_id").val();
			var news_status = ($("#show_this_news").is(":checked")) ? 1 : 0;
			$.ajax({
				type		: "POST",
				url			: rootUrl+"news_center_back/save",
				data		: {"mem_id" : $scope.mem_id, "news_text" : html, "ns_id" : ns_id, "news_status" : news_status},
				beforeSend	: function(){
					$('#savenews').attr('disabled', true);
					$('.loadermas').css('display', 'inline');
				},
				success: function(data){
					if(data == "1"){
						messages("success", "Success!","News Added/Edited Successfully.", 3000);
						$('#savenews').attr('disabled', false);
					}else if(data == "0"){
						messages("warning", "Info!", "No Data Affected", 3000);
						$scope.filter_bank_detials();
					}else{
						messages("danger", "Warning!",data, 6000);
					}
					$('.loadermas').css('display', 'none');
					$('#savenews').attr('disabled', false);
				}
			});
		}
	}
	setTimeout(function(){
		window.dispatchEvent(new Event('resize'));
		$(".fr-quick-insert").remove();
	}, 2000);
}]);