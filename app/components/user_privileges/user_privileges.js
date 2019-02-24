app.controller('user_privileges',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
	module="user_privileges/";
	
	$http.get(rootUrl+module+"index").success (function(data) 
	{
		if(data==0){window.location.assign('login.html');} 
		else if(data==2)
			{
				messages("success", "Privilege not assigned.", 1000);
				window.location.assign('index.html');
			}
	});
	
	$('#progress').hide();
//	$("#btnsubmit").prop('disabled',true);
	
//	$http.get(rootUrl+'create_password_back/view?data=usertype').success (function(data) 
//	{
//		console.log(data);
//		$scope.masters=data;
//	});
	
	$http.get(rootUrl+'user_privileges/view_privileges_json').success (function(data) 
	{
			$scope.modules=data;
//			console.log($scope.modules);
	});

	$scope.reset_all=function()
	{
		$scope.user="";
		$scope.user_name="";
		$scope.x.status="";
	};
	
	
	$scope.fetch_data=function(usertype)
	{
		console.log(usertype);
		$('#progress').toggle();
		$http.get(rootUrl+'user_privileges/view_data?usertype='+usertype).success (function(data)
		{
			console.log(data);
				$scope.user=data;
				$('#progress').hide();
		});
		$("#btnsubmit").prop('disabled',false);
	};
	
	$scope.update_data=function(x)
	{
//		console.log("shcvsjcvskzc");die();
		$("#btnsubmit").text('Please Wait...');
		$("#btnsubmit").prop('disabled',true);
		$("#uprogress").css( 'display' , 'inline');
		$.ajax({
			type: "POST",
			url: rootUrl+"user_privileges/update_data",
			data: $("#form2").serialize(),
			beforeSend: function()
			{
				$('#progress').toggle();
			},
			success: function(data)
			{
				$("#btnsubmit").text('Save');
				$("#btnsubmit").prop('disabled',false);
				$('#progress').toggle();
				console.log(data);
				var data = $.parseJSON(data);
				if (data.type == "1") {
					messages("danger", "Warning!",data.error, 8000);
				} else {
					setTimeout($scope.unlockwindow, 2000);
					messages("success", "Success!",data.error, 4000);
				}
			}
		});
	};
	
	$scope.unlockwindow=function()
	{
		window.location.reload();
	}
	
	$scope.update_data_other=function(x)
	{
		$.ajax({
			type: "POST",
			url: "other_privileges/update_data",
			data: $("#form3").serialize(),
			beforeSend: function()
			{
				$('#webprogress').css('display','inline');
			},
			success: function(data){
				var arr = $.parseJSON(data);
				if(arr.type=="1")
				{
					$('#error_msg').html(arr.error);
					$('#error_modal').trigger("click");
				}
				else
				{
					setTimeout($scope.unlockwindow, 3000);
					$('#success_msg').html(arr.error);
					$('#alert_modal').trigger("click");
				}
				$('#webprogress').css('display','none');
			}
		});
	};
	
}]);