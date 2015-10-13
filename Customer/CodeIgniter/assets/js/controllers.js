
var mobileCartControllers = angular.module('mobileCartApp.mobileCartControllers', []);


mobileCartControllers.controller('filterCtrl', function($scope, mobileCartService) {
    $scope.systemList = [];
    $scope.brandList = [];

    mobileCartService.getSystems().success(function (response) {
        $scope.systemList = response;
    });

    mobileCartService.getBrands().success(function (response) {
        $scope.brandList = response;
    });

    $scope.getFilter = function(){
      console.log($scope.filter);
    };

    /*$scope.$watch($scope.form, function(){
        console.log(filter);
    });*/
});

mobileCartControllers.controller('MobileListCtrl', function($scope, mobileCartService) {
    $scope.mobilesList = [];

    mobileCartService.getMobiles().success(function (response) {
        $scope.mobilesList = response;
    });
});

mobileCartControllers.controller('MobileDetailCtrl', function($scope, $routeParams,mobileCartService) {
    mobileCartService.getMobileDetails($routeParams.mobileID).success(function(response){
        $scope.mobile = response;
        $scope.mobile.quantity = 1;
        $scope.addToCart = function(){
            var valid = true;
            var validityCheck = $("#addToCartForm input:invalid");
            var form = $("#addToCartForm");
            var errorMessage = form.find(".errorMessage");
            errorMessage.text("");
            for(var i=0;i<validityCheck.length;i++) {
                if (validityCheck[i].checkValidity() == false) {
                    valid = false;
                    errorMessage.text(validityCheck[i].validationMessage);
                }
            }
            if(valid){
                var data ={
                    'mobileID' : $scope.mobile.MobileID,
                    'quantity' : $scope.mobile.quantity
                };
                mobileCartService.addToCart(data).success(function(response){
                    $scope.$root.cartCount = response.count;
                });
            }
        }
    });
});

mobileCartControllers.controller('MobileSaleDetailCtrl', function($scope, $routeParams,mobileCartService) {
    mobileCartService.getSaleMobileDetails($routeParams.mobileID,$routeParams.saleID).success(function(response){
        $scope.mobile = response;
        $scope.mobile.quantity = 1;
        $scope.addToCart = function(){
            var valid = true;
            var validityCheck = $("#addToCartForm input:invalid");
            var form = $("#addToCartForm");
            var errorMessage = form.find(".errorMessage");
            errorMessage.text("");
            for(var i=0;i<validityCheck.length;i++) {
                if (validityCheck[i].checkValidity() == false) {
                    valid = false;
                    errorMessage.text(validityCheck[i].validationMessage);
                }
            }
            if(valid) {
                var data = {
                    'mobileID': $scope.mobile.MobileID,
                    'saleID': $scope.mobile.SaleID,
                    'quantity': $scope.mobile.quantity
                };
                mobileCartService.addToCart(data).success(function (response) {
                    $scope.$root.cartCount = response.count;
                });
            }
        }
    });
});

mobileCartControllers.controller('MyCartCtrl', function($scope, mobileCartService){
    $scope.updateCartValue = function() {
        mobileCartService.getCartValue().success(function(response){
            $scope.$root.cartCount = response.count;
        });
    };

    $scope.getCart = function(){
        mobileCartService.getCart().success(function(response){
            $scope.cartList = response;
        });
    };

    $scope.getCart();

    $scope.clearCart = function(){
        mobileCartService.clearCart().success(function(){
            $scope.cartList=[];
            $scope.$root.cartCount = 0;
        });
    };

    $scope.addOrder = function(){
        mobileCartService.addOrder().success(function(){
            $scope.clearCart();
        });
    };

    $scope.removeItem = function(cartID){
        mobileCartService.removeItem(cartID).success(function(){
            $scope.getCart();
            $scope.updateCartValue();
        });
    };

    $scope.showUpdateLink = function(){
        var updateLink = $(event.target).siblings('.updateQuantity');
        if(updateLink.is(':hidden')) {
            updateLink.show();
        }
    };

    $scope.updateItemQuantity = function(cartID,quantity){
        var valid = true;
        var validityCheck = $(event.target).siblings('.qty-text');
        for(var i=0;i<validityCheck.length;i++) {
            if (validityCheck[i].checkValidity() == false) {
                valid = false;
            }
        }
        if(valid) {
            var data = {
                'quantity': quantity
            };
            mobileCartService.updateItemQuantity(cartID, data).success(function () {
                $scope.getCart();
                $scope.updateCartValue();
            });
        }
        else{
            $scope.getCart();
        }
    }
});

mobileCartControllers.controller('MyOrdersCtrl', function($scope, mobileCartService){
    mobileCartService.getOrders().success(function(response){
        $scope.orderList = response;
    });
});

mobileCartControllers.controller('MyAccountCtrl', function($scope, mobileCartService){
   mobileCartService.getAccountDetails().success(function(response){
       $scope.customer = response;
       var date = new Date($scope.customer.CreditCardExpiry);
       $scope.customer.CreditCardExpiry = new Date(date.getUTCFullYear(), date.getUTCMonth(),date.getUTCDate());
   });
});

mobileCartControllers.controller('headerCtrl', function($scope, mobileCartService){
    mobileCartService.getCartCount().success(function(response){
        $scope.$root.cartCount = response.count;
    });

    $scope.signOut = function(){
        mobileCartService.logout().success(function(response){
            window.location.assign("../login.html");
        });
    }
});