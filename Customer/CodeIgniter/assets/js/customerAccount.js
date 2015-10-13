/**
 * Created by shruti on 29/6/15.
 */

$(document).ready(start);

function start() {
    $("#my-account").on("click", showAccountPage);
    $("#myAccountTabs").tabs({
        activate: function(){
            showAccountDetails();
        }
    }).addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $("#myAccountTabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
    $("#updatePersonalInfo").on("click",updatePersonalInfo);
    $("#updateContactInfo").on("click",updateContactInfo);
    $("#updateCreditCardInfo").on("click",updateCreditCardInfo);
    $("#myAccountTabs li").on("click",clearErrorMessages);
    $("#updatePasswordInfo").on("click",updatePassword);
}

function updatePassword(){
    if(validateForm("#passwordInformation") && validatePasswords()){
        var data = $("#updatePasswordForm").serialize();
        $.ajax({
            url:"../../index.php/customer/updatePassword",
            type:"POST",
            contentType: "application/x-www-form-urlencoded",
            data:data,
            success: function(){
                showSilentMessage("Password changed successfully!");
                $("#oldPassword").val("");
                $("#newPassword").val("");
                $("#confirmNewPassword").val("");
            },
            error: function( jqXHR,  textStatus, errorThrown ){
                $("#updatePasswordForm").find(".mainErrorMessage").text(JSON.parse(jqXHR.responseText)['message']);
            }
        })
    }
}

function validatePasswords(){
    var oldPassword = $("#oldPassword");
    var newPassword = $("#newPassword");
    var confirmNewPassword = $("#confirmNewPassword");
    $("#updatePasswordForm").find(".mainErrorMessage").text("");
    var valid = true;
    if(newPassword.val() != confirmNewPassword.val()){
        valid = false;
        $("#updatePasswordForm").find(".mainErrorMessage").text("New Passwords do no match!");
    }
    else if(newPassword.val() == oldPassword.val()){
        valid = false;
        $("#updatePasswordForm").find(".mainErrorMessage").text("Old and new Passwords are same!");
    }
    return valid;
}

function clearErrorMessages(){
    var errMessages = $("#myAccountTabs").find(".errorMessage");
    for(var i=0;i<errMessages.length;i++){
        errMessages[i].innerHTML = "";
    }
}
function updatePersonalInfo(event){
    if(validateForm("#personalInformation")){
        updateCustomer();
    }
}

function updateContactInfo(event){
    if(validateForm("#contactInformation")){
        updateCustomer();
    }
}

function updateCreditCardInfo(event){
    if(validateForm("#creditCardInformation")){
        updateCustomer();
    }
}

function validateForm(divID){
    var valid = true;

    var errMessages = $(divID).find(".errorMessage");
    var validityCheck = $(divID+" input:invalid");
    for(var i=0;i<errMessages.length;i++){
        errMessages[i].innerHTML = "";
    }
    for(i=0;i<validityCheck.length;i++){
        if(validityCheck[i].checkValidity() == false){
            valid = false;
            $(validityCheck[i]).next().text( validityCheck[i].validationMessage );
        }
    }
    var addressEle = $("#address");
    var address = addressEle.val();
    if(address.length == 0){
        valid = false;
        addressEle.parent().find(".errorMessage").text("Please fill out this field");
    }

    if(!(/^[a-zA-Z0-9,;]*\S.*$/.test(address))){
        valid = false;
        addressEle.parent().find(".errorMessage").text("Please match the requested format");
    }
    return valid;
}

function updateCustomer(data){
    var data = $("#updateCustomerForm").serialize();
    $.ajax({
        url:"../../index.php/customer/updateCustomer",
        contentType: "application/x-www-form-urlencoded",
        type:"POST",
        data:data,
        success:function(){
            showSilentMessage("Account has been updated!");
            showAccountDetails();
        },
        error: function( jqXHR,  textStatus, errorThrown ){
            $("#username").next().text(JSON.parse(jqXHR.responseText)['message']);
        }
    })
}
function showAccountPage(event){
    hideNavigationBar();
    hideCategoryBtn();
    var tabID = $(this).data("tab-id");
    $(tabID).addClass("active").removeClass("not-active").siblings().removeClass("active").addClass("not-active");
    var today = new Date().toISOString().split('T')[0];
    var splitEntry = today.split('-');
    var minMonth = splitEntry[0]+"-"+splitEntry[1];
    $("#creditCardExpiry").attr('min', minMonth);
    $("#updateCustomerForm").find(".errorMessage").text("");
    showAccountDetails();
}

function showAccountDetails(){

    $.ajax({
        url:"../../index.php/customer/getDetails",
        contentType: "application/x-www-form-urlencoded",
        type:"GET",
        success: function(data){
            var details = JSON.parse(data);
            $("#customerName").val(details['CustomerName']);
            $("#username").val(details['Username']);
            $("#gender").val(details['Gender']);
            $("#address").val(details['Address']);
            $("#zipcode").val(details['Zipcode'])
            $("#emailAddress").val(details['EmailAddress']);
            $("#contactNumber").val(details['ContactNumber']);
            $("#creditCardNumber").val(details['CreditCardNumber']);
            $("#creditCardName").val(details['CreditCardName']);
            $("#creditCardExpiry").val(details['CreditCardExpiry'].substring(0,7));
        }
    });
}
