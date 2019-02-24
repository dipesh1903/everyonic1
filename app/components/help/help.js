//blank line is required
app.controller('help',['$scope','$rootScope','$http',function($scope,$rootScope,$http)
{
	module='help/';
	rootUrl=$rootScope.site_url;
	$http.get(rootUrl+module+"/index").success (function(data) {if(data==0){window.location.assign('login.html');} else if(data==2){messages("success", "Privilege not assigned.", 1000);window.location.assign('index.html');}});
	
	
}]);