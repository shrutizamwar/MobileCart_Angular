$(document).ready(start);

function start() {
    showCartValue();
    $("#my-cart").on("click", showCartPage);
}

function getCartItems(){
    $.ajax({
        url:"../../index.php/cart/getCart",
        type:"GET",
        contentType:"application/x-www-form-urlencoded",
        success: function(data){
            if(data!="[]") {
                showCartItems(data);
            }
            else{
                $("#cartDetails").empty().text("There are no items in your cart..");
            }
        }
    });
}

function showCartPage(event){
    hideNavigationBar();
    hideCategoryBtn();
    var tabID = $(this).data("tab-id");
    $(tabID).addClass("active").removeClass("not-active").siblings().removeClass("active").addClass("not-active");
    getCartItems();

}

function showCartItems(data){
    var list = JSON.parse(data);
    var cartBody = $("#cartDetails");
    var grandTotal = 0;
    cartBody.empty();
    var cartHead = "";
    cartHead+="<div class='cartBody'><table width='100%' cellpadding='0' cellspacing='0' class='cart-table'>";
    cartHead+="<thead class='cartHead'>";
    cartHead+="<tr>";
    cartHead+="<td class='product-info head-cell lborder' colspan='2'>";
    cartHead+="<table width='100%' cellspacing='0' cellpadding='0'>";
    cartHead+="<tbody><tr>";
    cartHead+="<td class='image-cell'>&nbsp;</td>";
    cartHead+="<td class='item-cell'><span>Item</span></td>";
    cartHead+="</tr>";
    cartHead+="</tbody></table></td>";
    cartHead+="<td class='product-price head-cell lborder'>Price</td>";
    cartHead+="<td class='product-quantity head-cell lborder'>Qty</td>";
    cartHead+="<td class='product-total-price head-cell lborder'>Total Price</td>";
    cartHead+="</tr>";
    cartHead+="</thead>";
    cartHead+="<tbody>";
    for(var i=0;i<list.length;i++){
        var imgSrc= "../../../../images/"+list[i].MainIcon;
        grandTotal+=list[i].TotalPrice;
        cartHead+="<tr class='cart-row'>";
        cartHead+="<td colspan='2' class='product-info lborder cell'>";
        cartHead+="<table width='100%' height='100%' cellspacing='0' cellpadding='0'>";
        cartHead+="<tbody><tr>";
        cartHead+="<td class='image-cell'><img height='50px' src="+imgSrc+"></td>";
        cartHead+="<td class='item-cell'><span>"+ list[i].MobileName+"</span>";
        if(list[i].SaleID){
            cartHead+="<p style='color:green;text-transform: none'>Offer Applied "+list[i].PercentageOff+"% OFF</p>";
        }
        cartHead+="</td>";
        cartHead+="</tr></tbody></table>";
        cartHead+="<a class='removeItem' cart-id="+ list[i].CartID+">Remove</a>";
        cartHead+="</td>";
        cartHead+="<td class='product-price lborder cell'>$ "+list[i].Price+"</td>";
        cartHead+="<td class='product-quantity lborder cell'>";
        cartHead+="<form>";
        cartHead+="<input class='qty-text' required name='quantity' type='number' maxlength='3' min='1' max='100' step='none' value="+list[i].Quantity+" >";
        cartHead+="<br><br><a class='updateQuantity' cart-id="+ list[i].CartID+">Update</a>";
        cartHead+="</form>";
        cartHead+="</td>";
        cartHead+="<td class='product-total-price lborder cell'>$ "+list[i].TotalPrice+"</td>";
        cartHead+="</tr>";
    }
    grandTotal = Math.round(grandTotal * 100) / 100;
    cartHead+="</tbody></table></div>";
    cartHead+="<div class='cart-footer'>";
    cartHead+="<span style='font-size: 20px;color:gray;font-weight: lighter'>Grand Total: </span>";
    cartHead+="<span style='font-weight: bold;font-size: 18px; color:#4A4A4A'>$ "+grandTotal+"</span>";
    cartHead+="<br><br><div><input style='margin-right: 20px;' id='clearCart' type='button' class='action-btn' value='CLEAR CART'/>";
    cartHead+="<input id='placeOrder' type='button' class='action-btn' value='PLACE AN ORDER'/></div>";
    cartHead+="</div>";

    cartBody.append(cartHead);
    $("a.removeItem").on("click", removeCartItem);
    $("input.qty-text").on("change",function(){
        var updateLink = $(this).siblings('.updateQuantity');
        if(updateLink.is(':hidden')) {
            updateLink.show();
        }
    });

    $("a.updateQuantity").on("click", updateCartItemQuantity);

    $("#placeOrder").on("click",placeOrder);

    $("#clearCart").on("click",clearCart);
}

function updateCartItemQuantity(){

    var valid = true;
    var validityCheck = $(this).siblings('.qty-text');
    for(var i=0;i<validityCheck.length;i++) {
        if (validityCheck[i].checkValidity() == false) {
            valid = false;
        }
    }
    if(valid) {
        var data = $(this).closest('form').serialize();
        data += "&cartID=" + $(this).attr('cart-id');
        $.ajax({
            url: "../../index.php/cart/updateCartQuantity",
            type: "POST",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            success: function (data) {
                getCartItems();
                var count = JSON.parse(data).count;
                $("#item-in-cart").text(count);
            }
        });
    }
    else{
        getCartItems();
    }
}

function clearCart(){
    $.ajax({
        url:"../../index.php/cart/clearCart",
        type:"GET",
        contentType:"application/x-www-form-urlencoded",
        success: function(){
            $("#cartDetails").empty().text("There are no items in your cart..");
            $("#item-in-cart").text(0);
        }
    });
}


function removeCartItem(){
    var cartID = $(this).attr('cart-id');
    var data = "cartID="+cartID;
    $.ajax({
        url:"../../index.php/cart/removeCartItem",
        type:"POST",
        contentType:"application/x-www-form-urlencoded",
        data:data,
        success: function(){
            showSilentMessage("Cart Item Deleted");
            getCartItems();
            showCartValue();
        }
    });
}

function showCartValue(){
    $.ajax({
        url:"../../index.php/cart/getCartCount",
        type:"GET",
        contentType:"application/x-www-form-urlencoded",
        success: function(data){
            var count = JSON.parse(data).count;
            $("#item-in-cart").text(count);
        }
    });
}

function addToCart(){
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

    var qty = $("#addToCartForm").find(".qty-text");
    qty.val(qty.val().trim());
    if(valid) {
        var data = $("#addToCartForm").serialize();
        $.ajax({
            url: "../../index.php/cart/addToCart",
            type: "POST",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            success: function (data) {
                showSilentMessage("Item added to cart!!");
                var count = JSON.parse(data).count;
                $("#item-in-cart").text(count);
            },
            error: function () {

            }
        });
    }
}
