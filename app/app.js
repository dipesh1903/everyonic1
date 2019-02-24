var app = angular.module('groveusCms', ['ngAnimate', 'ui.router', 'summernote', 'angularUtils.directives.dirPagination']);
app.config(function($stateProvider, $urlRouterProvider,$httpProvider){
	$httpProvider.defaults.withCredentials = true; //$httpProvider
    $urlRouterProvider.otherwise('/dashboard');
    $stateProvider
	.state
	('login', {
		url: '/login',
		templateUrl: 'app/components/login',
		controller: 'login'
	})
	.state
	('dashboard', {
		url: '/dashboard',
		templateUrl: 'app/components/dashboard',
		controller: 'dashboard'
	})
	.state
	('create_password', {
		url: '/create_password',
		templateUrl: 'app/components/create_password',
		controller: 'create_password'
	})
	.state
	('admin_master', {
		url: '/admin_master',
		templateUrl: 'app/components/admin_master',
		controller: 'admin_master'
	})
	.state
	('admin_wallet', {
		url: '/admin_wallet',
		templateUrl: 'app/components/admin_wallet',
		controller: 'admin_wallet'
	})
	.state
	('master_management', {
		url: '/master_management',
		templateUrl: 'app/components/master_management',
		controller: 'master_management'
	})
	.state
	('wallet', {
		url: '/wallet',
		templateUrl: 'app/components/wallet',
		controller: 'wallet'
	})
	.state
	('wallet_lock', {
		url: '/wallet_lock',
		templateUrl: 'app/components/wallet_lock',
		controller: 'wallet_lock'
	})
	.state
	('wallet_request', {
		url: '/wallet_request',
		templateUrl: 'app/components/wallet_request',
		controller: 'wallet_request'
	})
	.state
	('wallet_report', {
		url: '/wallet_report',
		templateUrl: 'app/components/wallet_report',
		controller: 'wallet_report'
	})
	.state
	('service', {
		url: '/service',
		templateUrl: 'app/components/service',
		controller: 'service'
	})
	.state
	('package', {
		url: '/package',
		templateUrl: 'app/components/package',
		controller: 'package'
	})
	.state
	('package_assigner', {
		url: '/package_assigner',
		templateUrl: 'app/components/package_assigner',
		controller: 'package_assigner'
	})
//	    .state
//	    ('package_manager', {
//	        url: '/package_manager',
//	        templateUrl: 'app/components/package_manager',
//	        controller: 'package_manager'
//	    })  
	 .state
	('package_commission', {
		url: '/package_commission/:id',
		templateUrl: 'app/components/package_commission',
		controller: 'package_commission'
	})
	.state
	('provider', {
		url: '/provider',
		templateUrl: 'app/components/provider',
		controller: 'provider'
	})
	.state
	('recharge', {
		url: '/recharge',
		templateUrl: 'app/components/recharge',
		controller: 'recharge'
	})
	.state
	('sms', {
		url: '/sms',
		templateUrl: 'app/components/sms',
		controller: 'sms'
	})  
	.state
	('logs', {
		url: '/logs',
		templateUrl: 'app/components/logs',
		controller: 'logs'
	})
	.state
	('mobile_report', {
		url: '/mobile_report',
		templateUrl: 'app/components/mobile_report',
		controller: 'mobile_report'
	})
	.state
	('dth_report', {
		url: '/dth_report',
		templateUrl: 'app/components/dth_report',
		controller: 'dth_report'
	})
	.state
	('news_center', {
		url: '/news_center',
		templateUrl: 'app/components/news_center',
		controller: 'news_center'
	})
	.state
	('bank_details', {
		url: '/bank_details',
		templateUrl: 'app/components/bank_details',
		controller: 'bank_details'
	})
	.state
	('profile_details', {
		url: '/profile_details',
		templateUrl: 'app/components/profile_details',
		controller: 'profile_details'
	})
	.state
	('pancard_coupon', {
		url: '/pancard_coupon',
		templateUrl: 'app/components/pancard_coupon',
		controller: 'pancard_coupon'
	})
	.state
	('coupon_request', {
		url: '/coupon_request',
		templateUrl: 'app/components/coupon_request',
		controller: 'coupon_request'
	})
	.state
	('setup_commission', {
		url: '/setup_commission',
		templateUrl: 'app/components/setup_commission',
		controller: 'setup_commission'
	})
});
app.run(function($rootScope) {
	$rootScope.site_url = "/api/index.php/";
});