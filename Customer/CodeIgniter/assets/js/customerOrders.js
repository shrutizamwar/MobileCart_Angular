$(document).ready(start);

function start() {
    $("#my-orders").on("click", showOrderPage);
}

function showOrderPage(event){
    hideNavigationBar();
    hideCategoryBtn();
    var tabID = $(this).data("tab-id");
    $(tabID).addClass("active").removeClass("not-active").siblings().removeClass("active").addClass("not-active");
    getOrders();
}


function getOrders(){
    $.ajax({
        url:"../../index.php/order/getOrders",
        type:"POST",
        contentType:"application/x-www-form-urlencoded",
        success: function(data){
            if(data!="[]"){
                showOrders(data);
            }
            else{
                $("#orderDetails").empty().text("No previous orders..");
            }

        }
    });
}

function showOrders(data){
    var orders = JSON.parse(data);
    var orderDetailsDiv = $("#orderDetails");
    var orderBody="";
    for(var orderID in orders){
        var order = orders[orderID];
        var orderTotal = order["OrderTotal"];
        var orderItems = order["OrderItems"];
        var orderDate = order["OrderDate"];
        var date = new Date(orderDate);
        var day = date.getDate();
        var month = monthNames[date.getMonth()];
        var year = date.getFullYear();
        var dateString = month+" "+day+", "+year;
        orderBody+="<div id='accordion'>";
        orderBody+="<h3 style='border: 1px solid #aaaaaa !important;background: #ECE9E9 !important;'>";
        orderBody+="<div class='orderRow'><span class='orderKey'>Order ID </span><span class='orderValue'>"+orderID+"</span></div>";
        orderBody+="<div class='orderRow orderDate'><span class='orderKey'>Order Date </span><span class='orderValue'>"+dateString+"</span></div>";
        orderBody+="<div class='orderRow orderTotal'><span class='orderKey'>Total: </span><span class='orderValue'>$ "+orderTotal+"</span></div>";
        orderBody+="</h3>";
        orderBody+="<div>";
        for( var i=0; i<orderItems.length;i++){
            var imgSrc= "../../../../images/"+orderItems[i].MainIcon;
            orderBody+="<div class='orderItemRow'>"
            orderBody+="<div class='orderItemDetail orderItemImage'><img src="+imgSrc+"></div>";
            orderBody+="<div class='orderItemDetail orderItemName'><span>"+orderItems[i].MobileName+"</span><p>Qty: "+orderItems[i].OrderQuantity+"</p></div>";
            orderBody+="<div class='orderItemDetail orderItemPrice'><span>Price: $ "+orderItems[i].MobilePrice+"</span>";
            if(orderItems[i].SaleID){
                orderBody+="<p style='color: green;'>Sale Offer Applied</p>";
            }
            orderBody+="</div>";
            orderBody+="<div class='orderItemDetail orderItemSubTotal'><span>Subtotal: $ "+(orderItems[i].MobilePrice * orderItems[i].OrderQuantity)+"</span></div>";
            orderBody+="</div>";
        }
        orderBody+="<div class='orderItemDetail orderDateBottom'><span class='orderKey'>Order Date </span><span class='orderValue'>"+dateString+"</span></div>";
        orderBody+="<div class='orderItemDetail orderTotalBottom'><span class='orderKey'>Total: </span><span class='orderValue'>$ "+orderTotal+"</span></div>";
        orderBody+="</div>";
    }
    order+="</div>";
    orderDetailsDiv.html(orderBody);
    $("#accordion").accordion({
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

function placeOrder(){
    $.ajax({
        url:"../../index.php/order/addOrder",
        type:"POST",
        contentType:"application/x-www-form-urlencoded",
        success: function(){
            showSilentMessage("Order Placed!!");
            clearCart();
        }
    });
}