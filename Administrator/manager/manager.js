/**
 * Created by shruti on 20/6/15.
 */
$(document).ready(start);

function start(){
    $('.tabs .tab-links a').on('click', function(e)  {
        var currentAttrValue = $(this).attr('href');
        jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
        jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
        e.preventDefault();
    });

    $('body').css({
        'font-family': 'Helvetica Neue,Helvetica,Arial,sans-serif',
        'font-size': '14px',
        'line-height':'1.42857143',
        'color': '#333',
        'background': 'url("../../images/NB-Dp4Apps-background.jpg") no-repeat center',
        '-webkit-background-size': 'cover',
        '-moz-background-size': 'cover',
        '-o-background-size': 'cover',
        'background-size': 'cover'
    });

    $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
        alert("You will be redirected to Login, because your session expired!");
        logout();
    });

    $("#byMobileNameBtn").on("click",getMobiles);
    $("li.byBrandNameTab").on("click",getBrandList);
    $("li.byOSTab").on("click",getOSList);
    $("#byMobileBrandNameBtn").on("click",getMobiles);
    $("#byMobileOSBtn").on("click",getMobiles);
    $("#byMobilePriceBtn").on("click",getMobiles);
    $("#byUserTypeBtn").on("click",getUsers);
    $("#byUserPayBtn").on("click",getUsers);
    $("#byAllUserBtn").on("click",getUsers);
    $("#byUserNameBtn").on("click",getUsers);
    $("#logout").on("click",logout);
    $("#byallMobileOptionsBtn").on("click",getMobiles);
    $("li.byAllOptions").on("click",getAllLists);
    $("li.bySaleTab").on("click",getAllSalesList);
    $("#bySaleBtn").on("click", getProductsBySale);
    $("li.byOrderTab").on("click",getAllCategoryList);
    $("#byOrderBtn").on("click", getAllOrders);
}

function getAllOrders(){
    var valid = true;
    var errMessages = $("#byOrder").find(".errMessage");
    for(var i=0;i<errMessages.length;i++){
        errMessages[i].innerHTML = "";
    }
    if($("input[name=byDate]:checked").val() == "2"){
        $(".errMessage").text("");
        var form = $("#byOrderOptions");
        var validityCheck = $("#byOrderOptions input:invalid");
        for(i=0;i<validityCheck.length;i++){
            if(validityCheck[i].checkValidity() == false){
                valid = false;
                errMessages.text( validityCheck[i].validationMessage );
            }
        }
        var fromDate = form.find("input[name=from]").val();
        var toDate = form.find("input[name=to]").val();
        if(fromDate >toDate){
            valid = false;
            errMessages.text ("From date cannot be greater than to date");
        }
    }
    if(valid) {
        var data = $("#byOrderOptions").serialize();
        $.ajax({
            url: "getOrdersList.php",
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            data: data,
            success: function (data) {
                var resultsDiv = $("#byOrder").find(".searchResults");
                var flag=false;
                if (data != "[]") {
                    var list = JSON.parse(data);
                    var rowHeader = $("#byOrder").find(".row");
                    var obj = list[0];
                    var keys = [];
                    var head = "";
                    for (var k in obj) {
                        keys.push(k);
                        head += "<div class='column'>" + k + "</div>";
                    }
                    rowHeader.html(head);
                    resultsDiv.empty();
                    for (var i = 0; i < list.length; i++) {
                        var child = "<div class='row'>";
                        for (var j = 0; j < keys.length; j++) {
                            child += "<div class='column'>" + list[i][keys[j]] + "</div>"
                            if(list[i][keys[j]] == null){
                                flag=true;
                                break;
                            }
                        }
                        if(flag){
                            rowHeader.empty();
                            resultsDiv.html("No Results..");
                            break;
                        }
                        child += "</div>";
                        resultsDiv.append(child);
                    }

                }
                else {
                    resultsDiv.html("No Results..");
                }
            }
        });
    }
}

function getAllCategoryList(){
    var sort = $("#sortOptions");
    var groupBy = $("#groupByOptions");
    $("input[name=sumBy]").on("change", function(){
        $("#byOrder").find(".searchResults").empty();
        $("#byOrder").find(".row").empty();
        if($("input[name=sumBy]:checked").val()=="0"){
            groupBy.css({"display":"none"});
        }
        else{
            groupBy.css({"display":"block"});
        }
    });

    $("input[name=byDate]").on("change", function(){
        $("#byOrder").find(".searchResults").empty();
        $("#byOrder").find(".row").empty();
    });

    $("input[name=sumBy]").on("change", function(){
        $("#byOrder").find(".searchResults").empty();
        $("#byOrder").find(".row").empty();
    });

    $("input[name=orderBy]").on("change", function(){
        $("#byOrder").find(".searchResults").empty();
        $("#byOrder").find(".row").empty();
    });

    $("input[name=groupBy]").on("change", function(){
        $("#byOrder").find(".searchResults").empty();
        $("#byOrder").find(".row").empty();
        if($("input[name=groupBy]:checked").val()!="0") {
            $(this).parents("#groupByOptions").find("select").attr("disabled", "disabled");
            $(this).closest(".groupBySelect").children("select").removeAttr("disabled").trigger("change");
        }
        else{
            $(this).parents("#groupByOptions").find("select").attr("disabled", "disabled");
        }
    });

    $("#byOrderOptions select").on("change",function(){
        $("#byOrder").find(".searchResults").empty();
        $("#byOrder").find(".row").empty();
        if($(this).val()!="000"){
            sort.css({"display":"none"});
        }
        else{
            sort.css({"display":"block"});
        }
    });

    var today = new Date().toISOString().split('T')[0];
    var form = $("#byOrderOptions");
    var fromDate = form.find("input[name=from]");
    var toDate = form.find("input[name=to]");

    fromDate.attr('max',today);
    toDate.attr('max',today);

    $.ajax({
        url:"getBrandsList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function(data){
            var options = JSON.parse(data);
            var brandSel = $("#groupByBrand");
            brandSel.empty();
            brandSel.append($("<option></option>").attr("value", "000").text("All"));
            for(var i=0; i<options.length; i++){
                brandSel.append($("<option></option>").attr("value", options[i].BrandID).text(options[i].BrandName));
            }
        }
    });


    $.ajax({
        url:"getOSList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function(data){
            var options = JSON.parse(data);
            var osSel = $("#groupByOS");
            osSel.empty();
            osSel.append($("<option></option>").attr("value", "000").text("All"));
            for(var i=0; i<options.length; i++){
                osSel.append($("<option></option>").attr("value", options[i].SystemID).text(options[i].SystemName));
            }
        }
    });

    $.ajax({
        url:"getProductList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function(data){
            var options = JSON.parse(data);
            var osSel = $("#groupByProduct");
            osSel.empty();
            osSel.append($("<option></option>").attr("value", "000").text("All"));
            for(var i=0; i<options.length; i++){
                osSel.append($("<option></option>").attr("value", options[i].MobileID).text(options[i].MobileName));
            }
        }
    });

    $.ajax({
        url:"getSaleItemList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function(data){
            var options = JSON.parse(data);
            var osSel = $("#groupBySaleProduct");
            osSel.empty();
            osSel.append($("<option></option>").attr("value", "000").text("All"));
            for(var i=0; i<options.length; i++){
                osSel.append($("<option></option>").attr("value", options[i].SaleID).text(options[i].MobileName+" "+options[i].PercentageOff+"%OFF"));
            }
        }
    });
}

function getProductsBySale(event){
    var data = $("#bySaleOptions").serialize();
    var resultDiv = $("#bySale").find(".searchResults");
    var valid = true;
    valid = validateSaleOptions("#bySale");
    if(valid) {
        resultDiv.empty();
        $.ajax({
            url: "getProductsBySale.php",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            success: function (data) {
                var list = JSON.parse(data);
                for (var i = 0; i < list.length; i++) {
                    var child = "<div class='row'>";
                    child += "<div class='column'>" + list[i].SalesID + "</div>"
                    child += "<div class='column'>" + list[i].MobileName + "</div>";
                    child += "<div class='column'>$" + list[i].Price + "</div>";
                    child += "<div class='column'>" + list[i].PercentageOff + "%</div>";
                    child += "<div class='column'>$" + list[i].NewPrice + "</div>";
                    child += "<div class='column'>" + list[i].StartDate + "</div>";
                    child += "<div class='column'>" + list[i].EndDate + "</div>";
                    child += "</div>";
                    resultDiv.append(child);
                }
            }
        });
    }
}

function getAllSalesList(){
    $.ajax({
        url:"getBrandsList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function(data){
            var options = JSON.parse(data);
            var brandSel = $("#saleBrandList");
            brandSel.empty();
            brandSel.append($("<option></option>").attr("value", "").text("Please select a Brand"));
            for(var i=0; i<options.length; i++){
                brandSel.append($("<option></option>").attr("value", options[i].BrandID).text(options[i].BrandName));
            }
        }
    });


    $.ajax({
        url:"getOSList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function(data){
            var options = JSON.parse(data);
            var osSel = $("#saleSystemList");
            osSel.empty();
            osSel.append($("<option></option>").attr("value", "").text("Please select a Operating System"));
            for(var i=0; i<options.length; i++){
                osSel.append($("<option></option>").attr("value", options[i].SystemID).text(options[i].SystemName));
            }
        }
    });

    $.ajax({
        url:"../employee/getProductList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function(data){
            var options = JSON.parse(data);
            var mobileListSel = $("#saleMobileList");
            mobileListSel.empty();
            mobileListSel.append($("<option></option>").attr("value", "").text("Please select a Mobile Name"));
            for(var i=0; i<options.length; i++){
                mobileListSel.append($("<option></option>").attr("value", options[i].MobileID).text(options[i].MobileName));
            }
        }
    });
}

function getAllLists(){

    $.ajax({
        url:"getBrandsList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function(data){
            var options = JSON.parse(data);
            var brandSel = $("#brandListForAll");
            brandSel.empty();
            brandSel.append($("<option></option>").attr("value", "").text("Please select a Brand"));
            for(var i=0; i<options.length; i++){
                brandSel.append($("<option></option>").attr("value", options[i].BrandID).text(options[i].BrandName));
            }
        }
    });


    $.ajax({
        url:"getOSList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function(data){
            var options = JSON.parse(data);
            var osSel = $("#OSListForAll");
            osSel.empty();
            osSel.append($("<option></option>").attr("value", "").text("Please select a Operating System"));
            for(var i=0; i<options.length; i++){
                osSel.append($("<option></option>").attr("value", options[i].SystemID).text(options[i].SystemName));
            }
        }
    });
}

function getBrandList(){
    $.ajax({
        url:"getBrandsList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function(data){
            var options = JSON.parse(data);
            var brandSel = $("#brandList");
            brandSel.empty();
            brandSel.append($("<option></option>").attr("value", "").text("Please select a Brand"));
            for(var i=0; i<options.length; i++){
                brandSel.append($("<option></option>").attr("value", options[i].BrandID).text(options[i].BrandName));
            }
        }
    });
}

function getOSList(){
    $.ajax({
        url:"getOSList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function(data){
            var options = JSON.parse(data);
            var osSel = $("#OSList");
            osSel.empty();
            osSel.append($("<option></option>").attr("value", "").text("Please select a Operating System"));
            for(var i=0; i<options.length; i++){
                osSel.append($("<option></option>").attr("value", options[i].SystemID).text(options[i].SystemName));
            }
        }
    });
}

function logout(event){
    $.ajax({
        url:"../logout.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function() {
            window.location.assign("http://"+window.location.host + "/MobileCart/Administrator/login/login.php");
        }
    });

}

function getMobiles(event){
    var target = "#"+$(event.currentTarget).attr('id');
    var data="";
    var resultDiv="";
    var valid = true;
    if(target == "#byMobileNameBtn"){
        resultDiv = $("#byMobileName").find(".searchResults");
        data = $("#mobileNameForm").serialize();
        data+="&operation=byName";
    }
    else if(target == "#byMobileBrandNameBtn"){
        resultDiv = $("#byBrandName").find(".searchResults");
        data = $("#mobileBrandForm").serialize();
        data+="&operation=byBrandName";
    }
    else if(target == "#byMobileOSBtn"){
        resultDiv = $("#byOS").find(".searchResults");
        data = $("#mobileOSForm").serialize();
        data+="&operation=byOS";
    }
    else if(target == "#byMobilePriceBtn"){
        valid = validateMobileSearch("#byMobilePrice");
        resultDiv = $("#byMobilePrice").find(".searchResults");
        data = $("#mobilePriceForm").serialize();
        data+="&operation=byPrice";
    }
    else if(target == "#byallMobileOptionsBtn"){
        valid = validateMobileSearch("#byallMobileOptions");
        resultDiv = $("#byallMobileOptions").find(".searchResults");
        data = $("#mobileAllForm").serialize();
        data+="&operation=byAll";
    }
    if(valid) {
        resultDiv.empty();
        $.ajax({
            url: "getProducts.php",
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            data: data,
            success: function (data) {
                var list = JSON.parse(data);
                for (var i = 0; i < list.length; i++) {
                    var child = "<div class='row'>";
                    child += "<div class='column'>" + list[i].MobileName + "</div>"
                    child += "<div class='column'>$" + list[i].MobilePrice + "</div>";
                    child += "<div class='column'>" + list[i].MobileQuantity + "</div>";
                    child += "<div class='column'>" + list[i].MobileScreenSize + "</div>";
                    child += "<div class='column'>" + list[i].MobileInternalMemory + "</div>";
                    child += "<div class='column'>" + list[i].MobileRAM + "</div>";
                    child += "</div>";
                    resultDiv.append(child);
                }
            }
        });
    }
}

function getUsers(){
    var target = "#"+$(event.currentTarget).attr('id');
    var data="";
    var resultDiv="";
    var valid = true;
    if(target == "#byUserTypeBtn"){
        resultDiv = $("#byUserType").find(".searchResults");
        data = $("#userTypeForm").serialize();
        data+="&operation=byUserType";
    }
    else if(target == "#byUserPayBtn"){
        valid = validateUserSearch("#byUserPay");
        resultDiv = $("#byUserPay").find(".searchResults");
        data = $("#userPayForm").serialize();
        data+="&operation=byUserPay";
    }
    else if(target == "#byUserNameBtn"){
        resultDiv = $("#byUserName").find(".searchResults");
        data = $("#userNameForm").serialize();
        data+="&operation=byUserName";
    }
    else if(target == "#byAllUserBtn"){
        valid = validateUserSearch("#byAllUserOptions");
        resultDiv = $("#byAllUserOptions").find(".searchResults");
        data = $("#userAllOptions").serialize();
        data+="&operation=byAll";
    }
    if(valid) {
        resultDiv.empty();
        $.ajax({
            url: "getUsers.php",
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            data: data,
            success: function (data) {
                var list = JSON.parse(data);
                for (var i = 0; i < list.length; i++) {
                    var child = "<div class='row'>";
                    child += "<div class='column'>" + list[i].UserType + "</div>"
                    child += "<div class='column'>" + list[i].FirstName + "</div>";
                    child += "<div class='column'>" + list[i].LastName + "</div>";
                    child += "<div class='column'>" + list[i].Gender + "</div>";
                    child += "<div class='column'>" + list[i].EmailAddress + "</div>";
                    child += "<div class='column'>$" + list[i].Salary + "</div>";
                    child += "</div>";
                    resultDiv.append(child);
                }
            },
            error: function () {
                alert("error");
            }
        });
    }
}

