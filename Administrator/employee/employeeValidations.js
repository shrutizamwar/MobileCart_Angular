/**
 * Created by shruti on 22/6/15.
 */


function validateCategory(formID){
    var valid = true;

    var errMessages = $(formID).find(".errMessage");
    var validityCheck = $(formID+" input:invalid");
    for(i=0;i<errMessages.length;i++){
        errMessages[i].innerHTML = "";
    }
    for(i=0;i<validityCheck.length;i++){
        if(validityCheck[i].checkValidity() == false){
            valid = false;
            $(validityCheck[i]).parents(formID).find(".errMessage").text( validityCheck[i].validationMessage );
        }
        else {
            valid = true;
            $(validityCheck[i]).parents(formID).find(".errMessage").innerHTML = "";
        }
    }
    return valid;
}

function validateProduct(formID){
    var valid = true;
    var errMessages = $(formID).find(".errMessage");
    var validityCheck = $(formID+" input:invalid");
    for(i=0;i<errMessages.length;i++){
        errMessages[i].innerHTML = "";
    }
    for(i=0;i<validityCheck.length;i++){
        if(validityCheck[i].checkValidity() == false){
            valid = false;
            $(validityCheck[i]).parents(".form-section").find(".errMessage").text( validityCheck[i].validationMessage );
        }
        else {
            valid = true;
            $(validityCheck[i]).parents(".form-section").find(".errMessage").innerHTML = "";
        }
    }
    return valid;
}

function validateSale(formID){
    var valid = true;
    var errMessages = $(formID).find(".errMessage");
    var validityCheck = $(formID+" input:invalid");
    for(i=0;i<errMessages.length;i++){
        errMessages[i].innerHTML = "";
    }
    for(i=0;i<validityCheck.length;i++){
        if(validityCheck[i].checkValidity() == false){
            valid = false;
            $(validityCheck[i]).parents(".form-section").find(".errMessage").text( validityCheck[i].validationMessage );
        }
        else {
            valid = true;
            $(validityCheck[i]).parents(".form-section").find(".errMessage").innerHTML = "";
        }
    }

    var saleStartDate = $(formID).find("input[name='saleStartDate']");
    var saleEndDate = $(formID).find("input[name='saleEndDate']");
    if(saleStartDate.val() > saleEndDate.val()){
        valid = false;
        saleStartDate.parents(".form-section").find(".errMessage").text("Start date should be previous than end date");
    }
    return valid;
}