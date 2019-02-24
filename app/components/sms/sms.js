app.controller('sms',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	$scope.mem_id=1;
	smsmodule="sms_back";
	$http.get(rootUrl+smsmodule+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});	
	
	$scope.loader=function()
	{
		$http.get(rootUrl+"pan_back/view?mem_id="+$scope.mem_id).success(function(data){
			$scope.datapan=data;
		})
	}
	//$scope.loader();
	$scope.walletBalance=function()
	{
		$http.get(rootUrl+"wallet_back/fetch_wallet?id="+$scope.mem_id+"&mem_typ=rt").success(function(data){
			$scope.bal=data[0].net_wal;
		})	
	}
	$scope.walletBalance();
	$scope.p={};
	$scope.filter_sms_data=function()
	{
		$scope.p={};
	}
	$scope.save_sms_data=function()
	{
		$('#panform').ajaxForm({
			type: "POST",
			url: rootUrl+"sms_back/save",
			beforeSend: function()
			{
				$('#submitbtnsms').attr('disabled',true);
				$('#loader2').css('display','inline');
			},
			success: function(data)
			{
				console.log(data);
				if(data=="1")
				{
					messages("success", "Success!","Saved Successfully", 3000);
					$scope.loader();
					$scope.filter_sms_data();
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 3000);
				}
				else
				{
					console.log('response',data);
					messages("danger", "Warning!",data, 6000);
				}
				$('#loader2').css('display','none');
				$('#submitbtnsms').attr('disabled',false);
			}
		});
	}
	
	
	
	
	
}]);