//blank line is required
app.controller('report',['$scope','$rootScope','$http', function($scope,$rootScope,$http) 
{
	rootUrl=$rootScope.site_url;
	reportmodule="hr_report/"
//	$scope.root_url
	$http.get(rootUrl+reportmodule+"/index").success (function(data){if(data==0){window.location.assign('login.html');}});	
	
	$scope.login=localStorage.getItem('usertype');
	if($scope.login =='Master'|| $scope.login =='Distributor'|| $scope.login=='Retailer')
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
	}
	if($scope.login =='Master'|| $scope.login =='Distributor'|| $scope.login=='Admin')
	{
	    $('#recharge101').remove();
	}
	if( $scope.login=='Retailer')
	{
	    $('#createpassword101').remove();
	    $('#member101').remove();
	}
	if( $scope.login=='Admin')
	{
		$('#display1').remove();
	    $('#walletrequest101').remove();
	    $('#adminmaster101').remove();
		$('#adminwallet101').remove();
	}
	
	$('#DOB1').datepicker();
	$('#DOB2').datepicker();
	$('#DOB3').datepicker();
	$('#DOB4').datepicker();
	$('#DOB5').datepicker();
	$('#DOB6').datepicker();
	$('#DOB7').datepicker();
	$('#DOB8').datepicker();
	$('#DOB9').datepicker();
	$('#DOB10').datepicker();
	$('#DOB11').datepicker();
	$('#DOB12').datepicker();
	$scope.x = {};
	$scope.label={};
	
	$scope.resetdf = function() 
	{
		$scope.x.cust_no = '0';
		$scope.x.trans_id = '0';
		$scope.x.sercode = "ALL";
//		$scope.x.status = "ALL";
//		$scope.x.cat_id="ALL";
//		$scope.x.tran_type="ALL";
//		$scope.x.title = 'ALL';
//		$scope.x.designation = 'ALL';
//		$scope.x.min_salary = '0';
//		$scope.x.max_salary = '0';
//		$scope.x.task_title = 'ALL';
	}
	$scope.resetdf();

	
	$scope.fetch_employee = function(gid)
	{
		$http.get(rootUrl+"hr_staff_details/view?grade=" + gid).success(function(data) {
			$scope.employees = data;
		})
	}
	$scope.fetch_head_list=function(type2)
	{
		if(type2=='OW')
		{
			$http.get(rootUrl+"category/view_data?journal=1&data=cat_id,name").success(function(data){
				$scope.categories=data;
			})
		}
		if(type2=='EM')
		{
			$http.get(rootUrl+"hr_staff_details/view?data=emp_id,name").success(function(data){
				$scope.employees=data;
			})
		}
	}
	
	$scope.salarywise_filter = function(x) 
	{
		$.ajax({
			type : "POST",
			url : rootUrl+"hr_report/get_salary_report",
			data : $("#salaryform1").serialize(),
			beforeSend : function() 
			{
				$('#webprogress').css('display', 'inline');
			},
			success : function(data)
			{
//				 console.log(data);
				$('#webprogress').css('display', 'none');
				$('#result1').html(data);
				$scope.label = $scope.x.day_date;
				$scope.$digest();
			}
		});
	};
	
	$scope.daywise_filter = function(x) 
	{
//		console.log(x);
		$.ajax({
			type : "POST",
			url : rootUrl+"hr_report/get_daily",
			data : $("#dayform1").serialize(),
			beforeSend : function() {
				$('#webprogress').css('display', 'inline');
			},
			success : function(data)
			{
				$('#webprogress').css('display', 'none');
				$('#result1').html(data);
				$scope.label = $scope.x.day_date;
				$scope.$digest();
			}
		});
	};
	
	$scope.monthwise_filter = function(x) 
	{
		$('#header').css('display', 'inline');
		$.ajax({
			type : "POST",
			url : rootUrl+"hr_report/get_daily",
			data : $("#monthform1").serialize(),
			beforeSend : function() {
				$('#webprogress').css('display', 'inline');
			},
			success : function(data) 
			{
				$('#webprogress').css('display', 'none');
				$('#result1').html(data);
				$scope.label = $scope.x.month;
				$scope.$digest();
			}
		});
	};
	$scope.rangewise_filter = function(x) {
//		console.log(x);
		$('#header').css('display', 'inline');
		$.ajax({
			type : "POST",
			url :rootUrl+"hr_report/get_daily",
			data : $("#rangeform1").serialize(),
			beforeSend : function() {
				$('#webprogress').css('display', 'inline');
			},
			success : function(data) {
//				console.log(data);
				$('#webprogress').css('display', 'none');
				$('#result1').html(data);
				$scope.label = $scope.x.sdate + " to " + $scope.x.edate;
				$scope.$digest();
			}
		});
	};
}]);