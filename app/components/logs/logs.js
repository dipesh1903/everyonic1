app.controller('logs',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	rootUrl=$rootScope.site_url;
//	$http.get(rootUrl+'login/auth').success (function(data) {if(data!=1){window.location.assign('login.html');}});
	
	$http.get(rootUrl+"logs/view_data").success(function(data)
	{
		console.log(data);
		$scope.datadb=data;
	})
	$scope.fetch_log=function(id)
	{
	 	$(".modal-body").html('');
	 	$("#modalbtn").trigger('click');
	 	$("#myModalLabel").html("Log Details");
	 	$.get(rootUrl+"logs/get_object?id="+id, function(data, status)
	 	{
	 		$(".modal-body").html(data);
	     });
	 }
}]);