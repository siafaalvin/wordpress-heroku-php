var app = angular.module('hyroglf_app', ['ngRoute', 'ngAnimate', 'toaster', 'ngSanitize']);
app.constant('BASE', 'wp-content/themes/hyroglf/');
app.run(['$rootScope' , 'BASE', function($rootScope, BASE) {
    $rootScope.base = BASE;
}]);
app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
	//$locationProvider.html5Mode(true).hashPrefix('!');
  	$locationProvider.html5Mode(true);
      	/*$routeProvider.otherwise({
        	title: 'Home Page',
            templateUrl: 'wp-content/themes/hyroglf/angular/partials/homepage.html'});*/
		$routeProvider.when('/', {
        	title: 'Home Page',
            templateUrl: 'wp-content/themes/hyroglf/angular/partials/homepage.html'});
  }]);
