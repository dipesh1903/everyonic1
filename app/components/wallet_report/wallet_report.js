app.controller('wallet_report',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	
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
		$('#setup_commision101').remove();
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
	
	
	$scope.init=function()
	{
		$http.get(rootUrl+"wallet_request_back/view?status=0").success(function(data){
			$scope.datawal=data;
			console.log(data);
		})
	}
	$scope.init();
	
	$scope.accept=function(x,v)
	{
		$scope.status=v;
		$('#walletreportform'+x).ajaxForm({
			type: "POST",
			url: rootUrl+"wallet_request_back/save",
			beforeSend: function()
			{ 
				$('#submitbtn1wqr'+x).attr('disabled',true);
				$('#loaderwalr'+x).css('display','inline');
			},
			success: function(data)
			{
				if(data.error==1)
				{
					messages("danger", "Warning!",data.msg, 6000);
					$scope.init();
				}
				else if(data.error=="0")
				{
					messages("success", "Success!",data.msg, 3000);
					$scope.init();
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#submitbtn1wqr'+x).css('display','none');
				$('#loaderwalr'+x).css('display','none');
			}
		});
		
	}
	
	$scope.accept_request = function(x, v, m, c, mt, amt){
		$scope.status = v;
		$.ajax({
			type		: 'POST',
			url			: rootUrl+"wallet_request_back/save_request",
			data		: {"wq_id": x, "status": v, "mem_id" : m, "com_id": c, "mem_typ" : mt, "amt": amt},
			dataType	: 'JSON',
			success		: function(data){
				if(data.error == 1){
					messages("danger", "Warning!", data.msg, 6000);
					$scope.init();
				}else if(data.error == "0"){
					messages("success", "Success!", data.msg, 3000);
					$scope.init();
				}else{
					messages("danger", "Warning!", data, 6000);
				}
				$('#submitbtn1wqr'+x).css('display', 'none');
				$('#loaderwalr'+x).css('display', 'none');
			}
		});
	}
	
	$scope.decline=function(x,v)
	{
		$scope.status=v;
		$('#walletreportform'+x).ajaxForm({
			type: "POST",
			url: rootUrl+"wallet_request_back/save",
			beforeSend: function()
			{ 
				$('#submitbdivpac2'+x).attr('disabled',true);
				$('#loaderwalr'+x).css('display','inline');
			},
			success: function(data)
			{
				if(data.error==1)
				{
					messages("danger", "Warning!",data.msg, 6000);
					$scope.init();
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#submitbtn1wqr'+x).css('display','none');
				$('#loaderwalr'+x).css('display','none');
			}
		});
	}
	
}]);