/*mobileDevice = navigator.userAgent.match(/iPhone/i);
tabletDevice = navigator.userAgent.match(/iPad/i);*/
check = true;

$(document).ready(start);

function start(){
    $(document).on({
        ajaxStart: function() {
            $(document.body).addClass("loading");
        },
        ajaxStop: function() {
            $(document.body).removeClass("loading");
        }
    });
    $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
        var response = JSON.parse(jqxhr.responseText);
        if(check) {
            if (jqxhr.status == 401) {
                check = false;
                alert(response.message);
                window.location.assign("../login.html");
            }
        }

        if(jqxhr.status == 402){
            window.location.assign("../login.html");
        }

        if(jqxhr.status == 400){
            alert(response.message);
        }
    });

    windowWidth = window.innerWidth;

    $( window ).resize(function(event) {
        var width = window.innerWidth;
        if(width != windowWidth){
            windowWidth = width;
            if(width >0 && width <= 767){
                if($("#homePage").hasClass('active')) {
                    $("#category-btn").show();
                }
                $(".filter").hide();
                $(".header-options ul").hide();
                $("#nav-btn").show();
            }
            else{
                if($("#homePage").hasClass('active')) {
                    $("#category-btn").hide();
                }
                $(".filter").show();
                $(".header-options ul").show();
                $("#nav-btn").hide();
            }
        }

    });


    monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    $("#my-account").data({"tab-id":"#myAccount"});
    $("#my-orders").data({"tab-id":"#myOrders"});
    $("#my-cart").data({"tab-id":"#myCart"});
    $(".logo").data({"tab-id":"#homePage"});
    getAllBrands();
    filterResults();
    getAllOperatingSystem();
    $("#logout").on("click",logout);
    $(".logo").on("click", showHomePage);
    $("#searchOptions").change(filterResults);
    /*$("#searchTermBtn").on("click",filterResults);*/
    $('#searchOptions').keyup(function(e) {
        code = e.keyCode || e.which;
        if (code == '13') {
            e.preventDefault();
            filterResults();
        }
    });

    $("#nav-btn").on("click",function(){
        $(this).next().toggle();
        $(".filter").hide();

    });

    $("#category-btn").on("click", function(event){
        event.stopPropagation();
        $(".filter").toggle("slide",{direction:"left"},500);
        $(".header-options ul").hide();
    })
}

function hideNavigationBar(){
    var width = window.innerWidth;
    if(width >0 && width <= 767){
        $(".header-options ul").hide();
    }
    else{
        $(".header-options ul").show();
    }
}

function hideCategoryBtn(){
    $("#category-btn").hide();
}

function showSilentMessage(msg){
    $('.silentMessage').find("span.message").html(msg);
    $('.silentMessage').css({"visibility":"visible"});
    setTimeout(function() {
        $('.silentMessage').css({"visibility":"hidden"});
    }, 4000);
}

function filterResults(){
    var width = window.innerWidth;
    if(width >0 && width <= 767){
        $(".filter").hide();
    }
    else{
        $(".filter").show();
    }
    var searchTerm = $(".searchInput");
    searchTerm.val(searchTerm.val().trim());
    var data = $("#searchOptions").serialize();
    $.ajax({
        url: "../../index.php/product/getProductsBySearch",
        contentType: "application/x-www-form-urlencoded",
        type: "POST",
        data: data,
        success: function (data) {
            $(".filterResults").addClass("active").removeClass("not-active");
            var mainDivEle = $(".filterResultsRows");
            if(data!="[]") {
                var list = JSON.parse(data);
                createProductColumns(list, mainDivEle);
            }
            else{
                mainDivEle.empty().text("No matching products available");
            }
        }
    });
}

function showHomePage(event){
    hideNavigationBar();
    var width = window.innerWidth;
    if(width >0 && width <= 767){
        $("#category-btn").show();
        $(".filter").hide();
    }
    else{
        $("#category-btn").hide();
        $(".filter").show();
    }
    var tabID = $(this).data("tab-id");
    $("#searchInput").val("");
    $('#searchOptions input:checkbox').removeAttr('checked');
    $(tabID).addClass("active").removeClass("not-active").siblings().removeClass("active").addClass("not-active");
    getAllBrands();
    filterResults();
    getAllOperatingSystem();
}

function logout(){
    $.ajax({
        url: "../../index.php/auth/logout",
        contentType: "application/x-www-form-urlencoded",
        type: "POST",
        success: function (data) {
            window.location.assign("../login.html");
        }
    });
}

function getAllBrands(){
    $.ajax({
        url: "../../index.php/categories/getbrands",
        contentType: "application/x-www-form-urlencoded",
        type: "POST",
        success: function (data) {
            var list = JSON.parse(data);
            var ulEle = $(".brandList");
            ulEle.empty();
            var liElements = ""
            for(var i=0;i<list.length;i++){
                liElements +='<li><label><input type="checkbox" value="'+list[i].BrandID+'" name="BrandIds[]" />'+ list[i].BrandName+' </label></li>'
            }
            ulEle.append(liElements);
        }
    });
}

function getAllOperatingSystem(){
    $.ajax({
        url: "../../index.php/categories/getsystems",
        contentType: "application/x-www-form-urlencoded",
        type: "POST",
        success: function (data) {
            var list = JSON.parse(data);
            var ulEle = $(".OSList");
            ulEle.empty();
            var liElements = ""
            for(var i=0;i<list.length;i++){
                liElements +='<li><label><input type="checkbox" value="'+list[i].SystemID + '" name="OSIds[]" />'+ list[i].SystemName+' </label></li>'
            }
            ulEle.append(liElements);
        }
    });
}

function createProductColumns(list,parentElement){
    parentElement.empty();
    var columnElement = "";
    for(var i=0;i<list.length;i++){
        var imgSrc= "../../../../images/"+list[i].MainIcon;
        if(list[i].SaleID) {
            columnElement += "<div class='productColumn removeWrap' data-saleID = "+list[i].SaleID+" data-pid=" + list[i].MobileID + ">" ;
        }
        else{
            columnElement += "<div class='productColumn removeWrap' data-pid=" + list[i].MobileID + ">" ;
        }
        columnElement+="<img src='"+ imgSrc+"'>";
        columnElement+="<br>";
        if(list[i].SaleID){
            columnElement+="<div style='position: relative;'><div class='offerDiv'>SPECIAL OFFER</div></div><br>";
        }
        columnElement+="<div class='productColumnDetails'>";
        columnElement+="<br>";
        columnElement+="<span>"+list[i].MobileName+"</span>";
        columnElement+="<p>$ "+list[i].Price +"</p>";
        columnElement+="<ul>";
        if(list[i].Description){
            columnElement+="<li>"+list[i].Description+"</li>";
        }
        columnElement+="<li>"+list[i].ScreenSize+" Screen</li>";
        columnElement+="<li> Primary Camera "+list[i].RearCamera+"</li>";
        columnElement+="<li> RAM "+list[i].RAM+"</li>";
        columnElement+="</ul>";
        columnElement+="</div>";
        columnElement+="</div>";
    }
    parentElement.append(columnElement);
    $(".productColumn").on("click", showProductDetails);
}


