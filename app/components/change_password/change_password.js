//blank line is required
app.controller('change_password',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	ch_module="change_password";
	$http.get(rootUrl+ch_module+"/index").success (function(data) {if(data==0){window.location.assign('login.html');} else if(data==2){messages("success", "Privilege not assigned.", 1000);window.location.assign('index.html');}});
	
//	$scope.login=localStorage.getItem("");
	
	$scope.x={};
	$scope.filter_new=function()
	{
		$scope.x={};
	}
	$scope.save_data=function(x)
	{
		$('#submitbtn').attr('disabled',true);
		$.ajax({
			type: "POST",
			url: rootUrl+"login/change_password_submit",
			data: $("#changeform1").serialize(),
			beforeSend: function()
			{
				$('#loader').css('display','inline');
			},
			success: function(data)
			{
				if(data=="1")
				{
					$scope.filter_new();
					messages("success", "Success!","Password Changed Successfully", 3000);
				}
				else if(data=="0")
				{
					messages("warning", "Info!","No Data Affected", 3000);
				}
				else
				{
					messages("danger", "Warning!",data, 6000);
				}
				$('#loader').css('display','none');
				$('#submitbtn').attr('disabled',false);
			}
		});
	}
	
}]);