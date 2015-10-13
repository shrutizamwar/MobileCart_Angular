var mobileCartFilter = angular.module('mobileCartApp.mobileCartFilters', []);

mobileCartFilter.filter('checkmark', function() {
    return function(input) {
        return input =='Yes' ? '\u2713' : '\u2718';
    };
});

mobileCartFilter.filter('totalPrice', function() {
    return function(items) {
        var total = 0, i = 0;
        for (i = 0; i < items.length; i++) {
            total += items[i].Price * items[i].Quantity
        }
        return total;
    }
});
