/**
 * Created by shruti on 17/6/15.
 */


$(document).ready(start);

function start(){
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
    $('.tabs .tab-links a').on('click', function(e)  {
        e.preventDefault();
        var currentAttrValue = $(this).attr('href');
        jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
        jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
    });

    $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
        alert("You will be redirected to Login, because your session expired!");
        logout();
    });

    $("#addOSBtn").on("click", addOS);
    $("li.updateList").on("click",showOSList);
    $("#updateOSBtn").on("click",updateOS);
    $("li.deleteList").on("click",showDeleteList);
    $("#deleteOSButton").on("click", deleteOS);
    $("li.updateBrandList").on("click",showBrandUpdateList);
    $("li.deleteBrandList").on("click",showBrandDeleteList);
    $("#addBrandBtn").on("click",addBrand);
    $("#updateBrandBtn").on("click", updateBrand);
    $("#deleteBrandBtn").on("click",deleteBrand);
    $("li.addProductTab").on("click", updateAddProductList);
    $("#addProductBtn").on("click",addProduct);
    $("li.updateProductTab").on("click",updateProductList);
    $("#updateProductBtn").on("click",updateProduct);
    $("li.deleteProductTab").on("click",showProductDeleteList);
    $("#deleteProductBtn").on("click",deleteProduct);
    $("li.addSaleTab").on("click",updateMobileList);
    $("li.updateSaleTab").on("click",showSaleList);
    $("#addSaleBtn").on("click",addSale);
    $("#logout").on("click",logout);
    $("#updateSaleBtn").on("click",updateSale);
    $("li.deleteSaleTab").on("click",showDeleteSaleList);
    $("#deleteSaleBtn").on("click",deleteSale);
}


function deleteSale(event){
    var data = $("#deleteSaleForm").serialize();
    if(data!="") {
        $.ajax({
            url: "deleteSale.php",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            success: function () {
                alert("Products deleted successfully!");
                showDeleteSaleList();
            }
        });
    }
}


function showDeleteSaleList(){
    var dataRows = $("#deleteSaleForm").find(".dataRows");
    dataRows.empty();
    $.ajax({
        url:"getSalesList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function(data) {
            var list = JSON.parse(data);
            for (var i = 0; i < list.length; i++) {
                var child = "<div class='row'>";
                child += "<div class='column'><input type='checkbox' name='saleIds[]' value='" +list[i].SalesID + "'/>"+list[i].SalesID+"</div>"
                child += "<div class='column'>" + list[i].MobileName + "</div>";
                child += "<div class='column'>" + list[i].PercentageOff + "%</div>";
                child += "<div class='column'>" + list[i].StartDate + "</div>";
                child += "<div class='column'>" + list[i].EndDate + "</div>";
                child += "</div>";
                dataRows.append(child);
            }
        }
    });
}

function showSaleList(){

    var mobileList = getMobileList();
    mobileList.success(function(data){
        var options = JSON.parse(data);
        var mobileListSel = $("#mobileNameListUpdate");
        mobileListSel.empty();
        for(var i=0; i<options.length; i++){
            mobileListSel.append($("<option></option>").attr("value", options[i].MobileID).text(options[i].MobileName));
        }
    });

    var parent = $("#saleList");
    displayEle(parent);
    hideEle($("#saleUpdateForm"));
    var dataRows = parent.find(".dataRows");
    dataRows.empty();
    $.ajax({
        url: "getSalesList.php",
        type: "GET",
        success: function(data) {
            var list = JSON.parse(data);
            for (var i = 0; i < list.length; i++) {
                var child = "<div class='row'>";
                child += "<div class='column'>" + list[i].MobileName + "</div>";
                child += "<div class='column'>" + list[i].PercentageOff + "%</div>";
                child += "<div class='column'>" + list[i].StartDate + "</div>";
                child += "<div class='column'>" + list[i].EndDate + "</div>";
                child += "<div class='column'><a class='saleID' href='' data-id=" + list[i].SalesID + ">Update</a></div>";
                child += "</div>";
                dataRows.append(child);
            }
            $("a.saleID").on("click", showUpdateSaleForm);
        }
    });
}

function showUpdateSaleForm(){
    hideEle($("#saleList"));
    displayEle($('#saleUpdateForm'));
    event.preventDefault();
    var id = this.attributes[2].value;
    var data = "id="+id;
    $.ajax({
        url: "getSaleDetails.php",
        data : data,
        contentType: "application/x-www-form-urlencoded",
        type: "POST",
        success:function(data){
            var details = JSON.parse(data);
            $("#updateSaleID").val(details['SalesID']);
            $("#mobileNameListUpdate").val(details['MobileID']);
            $("#updatePercentDiscount").val(details['PercentageOff']);
            $("#updateSaleStartDate").val(details['StartDate']);
            $("#updateSaleEndDate").val(details['EndDate']);
        }
    });
}

function updateSale(event){
    if(validateSale('#updateSaleForm')) {
        var data = $("#updateSaleForm").serialize();
        data += "&operation=Update";
        $.ajax({
            url: "addUpdateSale.php",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            success: function () {
                alert("Sale updated successfully!");
            }
        });
    }
}

function addSale(event){
    if(validateSale('#addSaleForm')) {
        var data = $("#addSaleForm").serialize();
        data += "&operation=Add";
        $.ajax({
            url: "addUpdateSale.php",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            success: function () {
                alert("Sale added successfully!");
            }
        });
    }
}

function updateMobileList(){
    var mobileList = getMobileList();
    mobileList.success(function(data){

        var options = JSON.parse(data);
        var mobileListSel = $("#mobileNameList");
        mobileListSel.empty();
        for(var i=0; i<options.length; i++){
            mobileListSel.append($("<option></option>").attr("value", options[i].MobileID).text(options[i].MobileName));
        }
    });
}

function getMobileList(){
    return($.ajax({
        url:"getProductList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET"
    }));
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

function showProductDeleteList(){
    var dataRows = $("#deleteProductForm").find(".dataRows");
    dataRows.empty();
    $.ajax({
        url:"getProductList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success:function(data) {
            var list = JSON.parse(data);
            for (var i = 0; i < list.length; i++) {
                var child = "<div class='row'>";
                child +="<div class='column'><input type='checkbox' name='mobileIDs[]' value='" +list[i].MobileID + "'/>"+list[i].MobileID+"</div>"
                child += "<div class='column'>" + list[i].MobileName + "</div>";
                child += "<div class='column'>$" + list[i].MobilePrice + "</div>";
                child += "<div class='column'>" + list[i].Quantity + "</div>";
                child += "</div>";
                dataRows.append(child);
            }
        }
    });
}

function deleteProduct(){
    var data = $("#deleteProductForm").serialize();
    if(data!="") {
        $.ajax({
            url: "deleteProduct.php",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            success: function () {
                alert("Products deleted successfully!");
                showProductDeleteList();
            }
        });
    }
}

function updateProduct(){
    if(validateProduct('#updateProductForm')) {
        var formData = new FormData($("#updateProductForm")[0]);
        formData.append('operation', 'Update');
        $.ajax({
            url: "addUpdateProduct.php",
            data: formData,
            async: false,
            type: "POST",
            success: function (data) {
                alert("Product updated successfully!");
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }
}

function updateProductList(){
    var osList = getOSList();
    var brandList = getBrandList();

    osList.success(function(data){
        var options = JSON.parse(data);
        var osSelect = $("#updateMobileOS");
        osSelect.empty();
        for(var i=0; i<options.length; i++){
            osSelect.append($("<option></option>").attr("value", options[i].SystemID).text(options[i].SystemName));
        }
    });

    brandList.success(function(data){
        var options = JSON.parse(data);
        var brandSelect = $("#updateMobileBrand");
        brandSelect.empty();
        for(var i=0; i<options.length; i++){
            brandSelect.append($("<option></option>").attr("value", options[i].BrandID).text(options[i].BrandName));
        }
    });

    var parent = $("#updateProductList");
    displayEle(parent);
    hideEle($("#productUpdateForm"));
    var dataRows = parent.find(".dataRows");
    dataRows.empty();
    var brandList = getBrandList();
    $.ajax({
        url:"getProductList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET",
        success: function (data) {
            var list = JSON.parse(data);
            for (var i = 0; i < list.length; i++) {
                var child = "<div class='row'>";
                child += "<div class='column'>" + list[i].MobileName + "</div>";
                child += "<div class='column'>$" + list[i].MobilePrice + "</div>";
                child += "<div class='column'>" + list[i].Quantity + "</div>";
                child += "<div class='column'><a class='mobileID' href='' data-id=" + list[i].MobileID + ">Update</a></div>";
                child += "</div>";
                dataRows.append(child);
            }

            $("a.mobileID").on("click", showUpdateProductForm);
        },
        error: function(){
            alert("error");
        }
    });
}

function showUpdateProductForm(){
    hideEle($("#updateProductList"));
    displayEle($('#productUpdateForm'));
    event.preventDefault();
    var id = this.attributes[2].value;
    var data = "id="+id;
    $.ajax({
        url: "getProductDetails.php",
        data : data,
        contentType: "application/x-www-form-urlencoded",
        type: "POST",
        success: function(data){
            var details = JSON.parse(data);
            $("#updateMobileID").val(details['MobileID']);
            $("#updateMobileName").val(details['MobileName']);
            $("#updateMobileDescription").val(details['MobileDescription']);
            $("#updateMobileMainIcon").val("");
            $("#updateMobilePrice").val(details['Price']);
            $("#updateQuantity").val(details['Quantity']);
            $("#updatePrice").val(details['Price']);
            $("#updateMobileOS").val(details['SystemID']);
            $("#updateMobileBrand").val(details['BrandID']);
            $("#updateMobileSize").val(details['ScreenSize']);
            $("#updateFrontCamera").val(details['FrontCamera']);
            $("#updateRearCamera").val(details['RearCamera']);
            $("#updateRAM").val(details['RAM']);
            $("#updateInternalMemory").val(details['InternalMemory']);
            $("#updateExternalMemory").val(details['ExtendableMemory']);
            if(details["Bluetooth"]) {
                $("input[name=updateBluetooth][value=" + details["Bluetooth"] + "]").attr('checked', 'checked');
            }
            if(details["GPS"]) {
                $("input[name=updateGPS][value=" + details["GPS"] + "]").attr('checked', 'checked');
            }
            if(details["Infrared"]) {
                $("input[name=updateInfrared][value=" + details["Infrared"] + "]").attr('checked', 'checked');
            }
            $("#updateOtherFeatures").val(details['OtherFeatures']);
            $("#updateMobileIcon").attr("src","../../images/"+details['MainIcon']);
        }
    });
}

function addProduct(){
    if(validateProduct('#addProductForm')) {
        var formData = new FormData($("#addProductForm")[0]);
        formData.append('operation', 'Add');
        $.ajax({
            url: "addUpdateProduct.php",
            data: formData,
            type: "POST",
            async: false,
            success: function (data) {
                alert(data)
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }
}

function updateAddProductList(){
    var osList = getOSList();
    var brandList = getBrandList();

    osList.success(function(data){
        var options = JSON.parse(data);
        var osSelect = $("#MobileOS");
        osSelect.empty();
        for(var i=0; i<options.length; i++){
            osSelect.append($("<option></option>").attr("value", options[i].SystemID).text(options[i].SystemName));
        }
    });

    brandList.success(function(data){
        var options = JSON.parse(data);
        var brandSelect = $("#MobileBrand");
        brandSelect.empty();
        for(var i=0; i<options.length; i++){
            brandSelect.append($("<option></option>").attr("value", options[i].BrandID).text(options[i].BrandName));
        }
    });
}

function getOSList(){
    return($.ajax({
        url:"getOSList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET"
    }));
}

function getBrandList(){
    return($.ajax({
        url:"getBrandsList.php",
        contentType:"application/x-www-form-urlencoded",
        type: "GET"
    }));
}

function showBrandUpdateList(){
    var parent = $("#brandList");
    displayEle(parent);
    hideEle($("#brandUpdateFrom"));
    var dataRows = parent.find(".dataRows");
    dataRows.empty();
    var brandList = getBrandList();
    brandList.success(function(data) {
        var list = JSON.parse(data);
        for (var i = 0; i < list.length; i++) {
            var child = "<div class='row'>";
            child += "<div class='column'>" + list[i].BrandName + "</div>";
            child += "<div class='column'>" + list[i].BrandDescription + "</div>";
            child += "<div class='column'><a class='brandID' href='' data-id=" + list[i].BrandID + ">Update</a></div>";
            child += "</div>";
            dataRows.append(child);
        }

        $("a.brandID").on("click", showUpdateBrandForm);
    });
}

function showBrandDeleteList(){
    var dataRows = $("#deleteBrand").find(".dataRows");
    dataRows.empty();
    var brandList = getBrandList();
    brandList.success(function(data){
        var list = JSON.parse(data);
        for(var i=0; i< list.length; i++) {
            var child = "<div class='row'>";
            child += "<div class='column'><input type='checkbox' name='brandIDs[]' value='" + list[i].BrandID + "'/>"
            child += "<label>" + list[i].BrandID + "</label></div>";
            child += "<div class='column'>" + list[i].BrandName + "</div>";
            child += "<div class='column'>" + list[i].BrandDescription + "</div>";
            child += "</div>";
            dataRows.append(child);
        }
    });
}

function showUpdateBrandForm(){
    hideEle($("#brandList"));
    displayEle($('#brandUpdateFrom'));
    event.preventDefault();
    var id = this.attributes[2].value;
    var data = "id="+id
    $.ajax({
        url: "getBrandDetails.php",
        data : data,
        contentType: "application/x-www-form-urlencoded",
        type: "POST",
        success: function(data){
            var details = JSON.parse(data);
            $("#updateBrandID").val(details['BrandID']);
            $("#updateBrandName").val(details['BrandName']);
            $("#updateBrandDescription").val(details['BrandDescription']);
        }
    });
}

function addBrand(){
    if(validateCategory('#addBrandForm')) {
        var data = $("#addBrandForm").serialize();
        data += "&operation=Add";
        $.ajax({
            url: "addUpdateBrand.php",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            success: function () {
                alert("Brand added successfully!");
            }
        });
    }
}

function updateBrand(){
    if(validateCategory('#updateBrandForm')) {
        var data = $("#updateBrandForm").serialize();
        data += "&operation=Update";
        $.ajax({
            url: "addUpdateBrand.php",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            success: function () {
                alert("Brand updated successfully!");
            }
        });
    }
}

function deleteBrand(){
    var data = $("#deleteBrandFrom").serialize();
    if(data!="") {
        $.ajax({
            url: "deleteBrand.php",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            success: function () {
                alert("Brands deleted successfully!");
                showBrandDeleteList();
            }
        });
    }
}

function addOS(event){
    if(validateCategory('#addForm')) {
        var data = $("#addForm").serialize();
        data += "&operation=Add";
        $.ajax({
            url: "addUpdateOS.php",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            success: function () {
                alert("Operating System added successfully!");
            }
        });
    }
}

function updateOS(){
    if(validateCategory('#updateForm')) {
        var data = $("#updateForm").serialize();
        data += "&operation=Update";
        $.ajax({
            url: "addUpdateOS.php",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            success: function () {
                alert("Operating System updated successfully!");
                showOSList();
            }
        });
    }
}

function deleteOS(){
    var data = $("#deleteForm").serialize();
    if(data!="") {
        $.ajax({
            url: "deleteOS.php",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            success: function () {
                alert("Operating Systems deleted successfully!");
                showDeleteList();
            }
        });
    }
}

function showOSList(event){
    var parent = $("#OSList");
    displayEle(parent);
    hideEle($("#updateOSForm"));
    var dataRows = parent.find(".dataRows");
    dataRows.empty();
    var OSList = getOSList();
    OSList.success(function(data){
        var list = JSON.parse(data);
        for(var i=0; i<list.length; i++){
            var child = "<div class='row'>";
            child +="<div class='column'>"+list[i].SystemName+"</div>";
            child +="<div class='column'>"+list[i].SystemDescription+"</div>";
            child +="<div class='column'><a class='systemID' href='' data-id="+list[i].SystemID+">Update</a></div>";
            child+= "</div>";
            dataRows.append(child);
        }

        $("a.systemID").on("click",showUpdateOSForm);
    });
}

function showUpdateOSForm(event){
    hideEle($("#OSList"));
    displayEle($('#updateOSForm'));
    event.preventDefault();
    var id = this.attributes[2].value;
    var data = "id="+id
    $.ajax({
        url: "getOSDetails.php",
        data : data,
        contentType: "application/x-www-form-urlencoded",
        type: "POST",
        success: function(data){
            var details = JSON.parse(data);
            $("#updateOSID").val(details['SystemID']);
            $("#updateOSName").val(details['SystemName']);
            $("#updateOSDescription").val(details['SystemDescription']);
        }
    });
}

function showDeleteList(){
    var dataRows = $("#deleteOS").find(".dataRows");
    dataRows.empty();
    var OSList = getOSList();
    OSList.success(function(data){
        var list = JSON.parse(data);
        for(var i=0; i< list.length; i++){
            var child = "<div class='row'>";
            child +="<div class='column'><input type='checkbox' name='systemIDs[]' value='" +list[i].SystemID + "'/>"
            child+= "<label>"+list[i].SystemID+"</label></div>";
            child +="<div class='column'>"+list[i].SystemName+"</div>";
            child +="<div class='column'>"+list[i].SystemDescription+"</div>";
            child+= "</div>";
            dataRows.append(child);
        }
    });
}

function displayEle(ele) {
    ele.css({
        display: "block"
    });
}

function hideEle(ele){
    ele.css({
        display:"none"
    });
}