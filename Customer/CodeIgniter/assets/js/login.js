//mobileDevice = navigator.userAgent.match(/iPhone/i);

$(document).ready(start);

function start(){
    $( "#loginModal" ).dialog({
        autoOpen: false,
        modal:true,
        draggable: false,
        show: {
            effect: "fade",
            duration: 100
        },
        hide: {
            effect: "fade",
            duration: 100
        },
        open: function(){
            var errMessages = $("#customer-login").find(".errorMessage");
            for(var i=0;i<errMessages.length;i++){
                errMessages[i].innerHTML = "";
            }
            $("#loginModal").find(".errorMessage").empty();
            document.getElementById("customer-login").reset();
            document.getElementById("customer-registration").reset();
        }
    });


    $("#signUpModal").dialog({
       autoOpen:false,
        modal:true,
        draggable:false,
        show:{
            effect:"fade",
            duration:100
        },
        hide:{
            effect:"fade",
            duration:100
        },
        open: function(){
            $("#personal-information").show();
            $("#credit-card-information").hide();
            $("#contact-information").hide();
            var errMessages = $("#customer-registration").find(".errorMessage");
            for(var i=0;i<errMessages.length;i++){
                errMessages[i].innerHTML = "";
            }
            $('#message').html('');
            document.getElementById("customer-login").reset();
            document.getElementById("customer-registration").reset();
        }
    });

    $( window ).resize(function() {
        var width = window.innerWidth;
        if(!(width >0 && width <= 500)){
            $("#signUpModal").dialog("option", "width", 500);
            $("#loginModal").dialog("option", "width", 350);
        }

        $("#signUpModal").dialog('close');
        $("#loginModal").dialog( 'close' );
    });

    var width = window.innerWidth;
    if(!(width >0 && width <= 500)){
        $("#signUpModal").dialog("option", "width", 500);
        $("#loginModal").dialog("option", "width", 350);
    }

    $( "#login" ).on("click",function() {
        $("#loginModal").dialog( 'open' );
    });

    $("#signup").on("click", function(){
        $("#signUpModal").dialog('open');
    });

    $(".sign-up-link span").on("click", function(){
        $("#loginModal").dialog( 'close' );
        $("#signUpModal").dialog('open');
    });

    $(".login-link span").on("click", function(){
        $("#loginModal").dialog( 'open' );
        $("#signUpModal").dialog('close');
    });

    $("#personal-information-btn").on("click", function(){
        if(validateForm('#personal-information')) {
            checkUsernameAvailibility();
        }
    });

    $("#contact-information-btn").on("click", function(){
        if(validateForm('#contact-information')) {
            $("#personal-information").hide();
            $("#contact-information").hide();
            $("#credit-card-information").show("fade", 100);
            $("input[name='creditCardNumber']").focus();
        }
    });

    $(".ui-widget-overlay").on("click", function(){
        $("#loginModal").dialog( 'close' );
        $("#signUpModal").dialog('close');
    });

    $("input").change( function(){
        $(this).next().empty();
    });

    $("textarea").change(function(){
        $(this).next().empty();
    });

    var password = $("#password");
    var confirmPassword = $("#confirm-password");

    password.on("keyup",validatePassword);
    confirmPassword.on("keyup",validatePassword);

    $("#login-btn").on("click",login);

    $("#credit-card-information-btn").on("click",addCustomer);

    $(document).keypress(function (e) {
        var key = e.which;
        if(key == 13){
            login();
        }
    });

    var today = new Date().toISOString().split('T')[0];
    var splitEntry = today.split('-');
    var minMonth = splitEntry[0]+"-"+splitEntry[1];
    $("input[name=creditCardExpiry]").attr('min',minMonth);
}

function checkUsernameAvailibility(){
    var uname = $("input[name='username']");
    var data="username="+uname.val();
    $.ajax({
        url:"../index.php/customer/checkUsername",
        data: data,
        contentType: "application/x-www-form-urlencoded",
        type: "POST",
        success: function () {
            $("#personal-information").hide();
            $("#credit-card-information").hide();
            $("#contact-information").show("fade", 100);
            $("input[name='emailAddress']").focus();
        },
        error: function( jqXHR,  textStatus, errorThrown ){
            uname.next().text(JSON.parse(jqXHR.responseText).message);
        }
    });
}

function addCustomer(){
    if(validateForm('#credit-card-information')) {
        $("#signUpModal").dialog('close');
        var data = $("#customer-registration").serialize();
        $.ajax({
            url: "../index.php/customer/addCustomer",
            data: data,
            contentType: "application/x-www-form-urlencoded",
            type: "POST",
            success: function () {
                $("#loginModal").dialog('open');
            },
            error: function(jqXHR,  textStatus, errorThrown ){
                var response = JSON.parse(jqXHR.responseText);
                if(jqXHR.status == 400){
                    alert(response.message);
                }
            }
        });
    }
}


function login(event){
    var data = $("#customer-login").serialize();
    $.ajax({
        url: "../index.php/auth/login",
        data: data,
        contentType: "application/x-www-form-urlencoded",
        type: "POST",
        success: function (data) {
            window.location.assign("../assets/html/customerHome.html");
        },
        error: function( jqXHR,  textStatus, errorThrown ){
            $("#loginModal").find(".errorMessage").text(JSON.parse(jqXHR.responseText).message);
        }
    });
}

function validatePassword(){
    var password = $("#password");
    var confirmPassword = $("#confirm-password");
    if(password.val() == '' && confirmPassword.val()==''){
        $('#message').html('');
    }
    else if(password.val() != confirmPassword.val()){
        $('#message').html('\u2718').css('color', 'red');
    }
    else{
        $('#message').html('\u2713').css('color', 'green');
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
            $(validityCheck[i]).parent().find(".errorMessage").text( validityCheck[i].validationMessage );
        }
        else {
            valid = true;
            $(validityCheck[i]).parent().find(".errorMessage").innerHTML = "";
        }
    }

    if(divID == "#contact-information"){
        var addressEle = $("textarea[name=address]");
        var address = addressEle.val();
        if(address.length == 0){
            valid = false;
            addressEle.parent().find(".errorMessage").text("Please fill out this field");
        }

        if(!(/^[a-zA-Z0-9,;]*\S.*$/.test(address))){
            valid = false;
            addressEle.parent().find(".errorMessage").text("Please match the requested format");
        }
    }
    return valid;
}