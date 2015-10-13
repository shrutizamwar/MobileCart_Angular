var mobileCartServices = angular.module('mobileCartApp.mobileCartServices',[]);

mobileCartServices.factory('mobileCartService',function($http){
    var mobileCartAPI = {};

    mobileCartAPI.getSystems = function() {
        return $http({
            url: "../../index.php/categories/getsystems"
        });
    };

    mobileCartAPI.getBrands = function() {
        return $http({
            url: "../../index.php/categories/getbrands"
        });
    };
    mobileCartAPI.getMobiles = function() {
        return $http({
            url: '../../index.php/product/getProductsBySearch',
            data: {}
        });
    };

    mobileCartAPI.getMobileDetails = function(mobileID){
        return $http({
            url: '../../index.php/product/getDetails/'+mobileID
        });
    };

    mobileCartAPI.getSaleMobileDetails = function(mobileID,saleID){
        return $http({
            url: '../../index.php/product/getSaleMobileDetail/'+mobileID+'/'+saleID
        });
    };

    mobileCartAPI.getCart = function(){
        return $http({
            url:"../../index.php/cart/getCart"
        });
    };

    mobileCartAPI.getOrders = function(){
        return $http({
            url:"../../index.php/order/getOrders"
        });
    };

    mobileCartAPI.getAccountDetails = function () {
        return $http({
            url:"../../index.php/customer/getDetails"
        });
    };

    mobileCartAPI.getCartCount = function(){
        return $http({
            url:"../../index.php/cart/getCartCount"
        });
    };

    mobileCartAPI.logout = function(){
        return $http({
            url: "../../index.php/auth/logout"
        });
    };

    mobileCartAPI.clearCart = function(){
        return $http({
            url:"../../index.php/cart/clearCart"
        });
    };

    mobileCartAPI.addOrder = function(){
        return $http({
            url:"../../index.php/order/addOrder"
        });
    };

    mobileCartAPI.addToCart = function(data){
        return $http({
            method:'POST',
            url: "../../index.php/cart/addToCart",
            data: $.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
    };

    mobileCartAPI.removeItem = function(cartID){
        return $http({
            url: '../../index.php/cart/removeCartItem/'+cartID
        });
    };

    mobileCartAPI.getCartValue = function(){
        return $http({
            url:"../../index.php/cart/getCartCount"
        });
    };

    mobileCartAPI.updateItemQuantity = function(cartID, data){
        return $http({
            method:'POST',
            url:'../../index.php/cart/updateCartQuantity/'+cartID,
            data: $.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
    }
    return mobileCartAPI;
});