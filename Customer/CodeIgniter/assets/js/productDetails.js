
function showProductDetails(event){
    hideNavigationBar();
    hideCategoryBtn();
    var mobileID = $(this).attr("data-pid");
    var saleID = $(this).attr("data-SaleID");

    if(saleID == undefined){
        getMobileDetails(mobileID);
    }
    else{
        getSaleMobileDetails(mobileID,saleID);
    }

    getProductSuggestion(mobileID);
}

function getProductSuggestion(mobileID){
    $.ajax({
        url:"../../index.php/product/getProductSuggestion/"+mobileID,
        contentType:"application/x-www-form-urlencoded",
        type:"GET",
        success: function(data){
            var mainDivEle = $(".mobileSuggestionRow");
            var suggestionDiv = $("#suggestionMessage");
            if(data!="[]") {
                suggestionDiv.html('<h3>People also bought these with this product</h3> <hr><br>');
                var list = JSON.parse(data);
                createProductColumns(list, mainDivEle);
            }
            else{
                mainDivEle.empty();
                suggestionDiv.empty();
            }
        }
    })
}

function getMobileDetails(mobileID){
    var url = "../../index.php/product/getDetails/"+mobileID;
    $.ajax({
        url: url,
        contentType: "application/x-www-form-urlencoded",
        type: "GET",
        success: function (data) {
            var list = JSON.parse(data);
            showMobileDetails(list);
        }
    });
}

function getSaleMobileDetails(mobileID, saleID){
    var url = "../../index.php/product/getSaleMobileDetail/"+mobileID+"/"+saleID;
    $.ajax({
        url: url,
        contentType: "application/x-www-form-urlencoded",
        type: "GET",
        success: function (data) {
            var list = JSON.parse(data);
            showMobileDetails(list);
        }
    });
}

function showMobileDetails(list){
    var detailBody = $("#mobileDetailsBody");
    detailBody.empty();
    var src= "../../../../images/"+list.MainIcon;
    var mobileImageDiv = "<div class='mobileImage'><img src="+src+"></div>";

    var mobileDetails="";
    mobileDetails+="<div class='mobileDetails'>";
    mobileDetails+="<h2 class='mobileName'>"+list.MobileName+"</h2>";
    if(list.Description){
        mobileDetails+="<p>"+list.Description+"</p>";
    }
    mobileDetails+="<ul>";

    mobileDetails+="<li>"+list.ScreenSize+" Screen</li>";
    mobileDetails+="<li> Primary Camera "+list.RearCamera+"</li>";
    mobileDetails+="<li> Secondary Camera "+list.FrontCamera+"</li>";
    mobileDetails+="<li> RAM "+list.RAM+"</li>";
    mobileDetails+="<li> Internal Memory "+list.InternalMemory+"</li>";
    mobileDetails+="<li> External Memory "+list.ExtendableMemory+"</li>";
    if(list.GPS){
        (list.GPS == "Yes")? mobileDetails+="<li>GPS "+'\u2713'+"</li>":mobileDetails+="<li>GPS "+'\u2718'+"</li>";
    }
    if(list.Bluetooth){
        (list.Bluetooth == "Yes")? mobileDetails+="<li>Bluetooth "+'\u2713'+"</li>":mobileDetails+="<li>Bluetooth "+'\u2718'+"</li>";
    }
    if(list.Infrared){
        (list.Infrared == "Yes")? mobileDetails+="<li>Infrared "+'\u2713'+"</li>":mobileDetails+="<li>Infrared "+'\u2718'+"</li>";
    }
    if(list.OtherFeatures){
        mobileDetails+="<li> Other Features "+list.OtherFeatures+"</li>";
    }
    mobileDetails+="</div>";
    mobileDetails+="<div class='mobilePrice'>" ;
    mobileDetails+="<h4>Selling Price</h4>";
    if(list.OriginalPrice){
        mobileDetails+="<span class='originalPrice'>$ "+list.OriginalPrice+"</span><br>"
    }
    mobileDetails+="<span class='price'>$ "+list.Price +"</span>";
    if(list.PercentageOff){
        mobileDetails+="<span class='salePercentage'>"+list.PercentageOff+"% OFF</span>";
        var date = new Date(list.EndDate);
        var day = date.getUTCDate();
        var month = monthNames[date.getMonth()];
        var year = date.getFullYear();
        mobileDetails+="<span class='saleEndDate'>&nbsp;&nbsp;&nbsp;Offer valid till "+month+" "+day+", "+year+"</span>"
    }

    mobileDetails+="<form id='addToCartForm'>";
    mobileDetails+="<br><br>";
    mobileDetails+="<label>Quantity <input required style='width: 140px;' class='qty-text' value='1' name='quantity' type='number' min='1' max='100' step='none'></label>";
    mobileDetails+="<p style='font-size: 12px;' class='errorMessage'></p>";
    mobileDetails+="<br><br><input class = 'action-btn' type='button' value='ADD TO CART' id='addToCart'/>";
    mobileDetails+='<input value="'+list.MobileID+'" type="hidden"  name="mobileID"/>';
    if(list.SaleID){
        mobileDetails+='<input value="'+list.SaleID+'" type="hidden"  name="saleID"/>';
    }
    mobileDetails+="</form>";
    mobileDetails+="</div>";
    detailBody.append(mobileImageDiv);
    detailBody.append(mobileDetails);
    $("#addToCart").on("click", addToCart);
    $("#mobileDetails").addClass("active").removeClass("not-active").siblings().removeClass("active").addClass("not-active");
}

