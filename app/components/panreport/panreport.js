app.controller('panreport',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	$scope.init=function()
	{
		$http.get(rootUrl+"pan_back/view?join=1").success(function(data){
			$scope.datapan=data;
			console.log(data);
		})
	}
	$scope.init();
	
	$scope.p={};
	$scope.filter_pan_data=function()
	{
		$scope.p={};
	}
	$scope.save_pan_data=function()
	{
		$('#panform').ajaxForm({
			type: "POST",
			url: rootUrl+"pan_back/save",
			beforeSend: function()
			{
				$('#submitbtnpan').attr('disabled',true);
				$('#loader2').css('display','inline');
			},
			success: function(data)
			{
				if(data=="1")
				{
					messages("success", "Success!","Saved Successfully", 3000);
					$scope.filter_pan_data();
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
				$('#submitbtnpan').attr('disabled',false);
			}
		});
	}
	
	
	$scope.accept=function(x,v)
	{
		$scope.status=v;
		$('#panreportform'+x).ajaxForm({
			type: "POST",
			url: rootUrl+"pan_back/save",
			beforeSend: function()
			{ 
//				$('#submitbdivpac1'+x).attr('disabled',true);
//				$('#loaderpac'+x).css('display','inline');
			},
			success: function(data)
			{
				if(data=="1")
				{
					messages("success", "Success!"," Saved Successfully", 3000);
					$scope.init();
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 3000);
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
			}
		});
		
	}
	
	$scope.decline=function(x,v)
	{
		$scope.status=v;
		$('#panreportform'+x).ajaxForm({
			type: "POST",
			url: rootUrl+"pan_back/save",
			beforeSend: function()
			{ 
//				$('#submitbdivpac2'+x).attr('disabled',true);
//				$('#loaderpac'+x).css('display','inline');
			},
			success: function(data)
			{
				if(data=="1")
				{
					messages("success", "Success!"," Saved Successfully", 3000);
					$scope.init();
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 3000);
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#submitbdivpac1'+x).css('display','none');
				$('#submitbdivpac2'+x).css('display','none');
			}
		});
	}
	
	
}]);