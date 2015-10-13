
var mobileCartApp= angular.module('mobileCartApp', [
        'ngRoute',
        'mobileCartApp.mobileCartControllers',
        'mobileCartApp.mobileCartServices',
        'mobileCartApp.mobileCartFilters',
        'mobileCartApp.mobileCartDirective'
    ]);

mobileCartApp.factory('httpInterceptor', function ($q) {
    return {
        responseError: function (rejection) {
            if (rejection.status == 401) {
                check = false;
                alert(rejection.data.message);
                window.location.assign("../login.html");
            }

            if(rejection.status == 402){
                window.location.assign("../login.html");
            }

            if(rejection.status == 400){
                alert(rejection.data.message);
            }
        }
    };
});

mobileCartApp.config(['$httpProvider', function($httpProvider) {
    $httpProvider.interceptors.push('httpInterceptor');
}]);

mobileCartApp.config(['$routeProvider','$provide', '$httpProvider',
    function($routeProvider) {
        $routeProvider.
            when('/mobiles', {
                templateUrl: 'mobile-list.html',
                controller: 'MobileListCtrl'
            }).
            when('/mobiles/:mobileID', {
                templateUrl: 'mobile-detail.html',
                controller: 'MobileDetailCtrl'
            }).
            when('/mobiles/:mobileID/:saleID', {
                templateUrl: 'mobile-sale-detail.html',
                controller: 'MobileSaleDetailCtrl'
            }).
            when('/myaccount',{
                templateUrl: 'myaccount.html',
                controller: 'MyAccountCtrl'
            }).
            when('/myorders',{
                templateUrl: 'myorders.html',
                controller: 'MyOrdersCtrl'
            }).
            when('/mycart',{
                templateUrl: 'mycart.html',
                controller: 'MyCartCtrl'
            }).
            otherwise({
                redirectTo: '/mobiles'
            });
    }]);




