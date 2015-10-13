var mobileCartDirective = angular.module('mobileCartApp.mobileCartDirective',[]);


mobileCartDirective.directive("accordion", function(){
    return {
        restrict: 'AE',
        replace: true,
        link: function (scope, element, attrs) {
            scope.$watch('orderList', function (newValue, oldValue) {
                if (oldValue != newValue) {
                    element.accordion({
                        header: "h3",
                        heightStyle: "content",
                        active:false,
                        collapsible:true,
                        activate: function(event,ui){
                            ui.newHeader.find(".orderDate").hide();
                            ui.newHeader.find(".orderTotal").hide();
                            ui.oldHeader.find(".orderDate").show();
                            ui.oldHeader.find(".orderTotal").show();
                        }
                    });
                }
            });
        }
    };
});

mobileCartDirective.directive("tab", function(){
    return {
        restrict: 'AE',
        replace: true,
        link: function (scope, element, attrs) {
            scope.$watch('customer', function (newValue, oldValue) {
                if (oldValue != newValue) {
                    element.tabs();
                }
            });
        }
    };
});