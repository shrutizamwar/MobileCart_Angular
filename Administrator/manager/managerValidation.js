/**
 * Created by shruti on 23/6/15.
 */


function validateMobileSearch(divID){
    var valid = true;

    var errMessages = $(divID).find(".errMessage");
    var validityCheck = $(divID+" input:invalid");
    for(i=0;i<errMessages.length;i++){
        errMessages[i].innerHTML = "";
    }
    for(i=0;i<validityCheck.length;i++){
        if(validityCheck[i].checkValidity() == false){
            valid = false;
            $(validityCheck[i]).parents(divID).find(".errMessage").text( validityCheck[i].validationMessage );
        }
        else {
            valid = true;
            $(validityCheck[i]).parents(divID).find(".errMessage").innerHTML = "";
        }
    }
    return valid;
}

function validateUserSearch(divID){
    var valid = true;

    var errMessages = $(divID).find(".errMessage");
    var validityCheck = $(divID+" input:invalid");
    for(i=0;i<errMessages.length;i++){
        errMessages[i].innerHTML = "";
    }
    for(i=0;i<validityCheck.length;i++){
        if(validityCheck[i].checkValidity() == false){
            valid = false;
            $(validityCheck[i]).parents(divID).find(".errMessage").text( validityCheck[i].validationMessage );
        }
        else {
            valid = true;
            $(validityCheck[i]).parents(divID).find(".errMessage").innerHTML = "";
        }
    }
    return valid;
}

function validateSaleOptions(divID){
    var valid = true;

    var errMessages = $(divID).find(".errMessage");
    var validityCheck = $(divID+" input:invalid");
    for(i=0;i<errMessages.length;i++){
        errMessages[i].innerHTML = "";
    }
    for(i=0;i<validityCheck.length;i++){
        if(validityCheck[i].checkValidity() == false){
            valid = false;
            $(validityCheck[i]).parents(divID).find(".errMessage").text( validityCheck[i].validationMessage );
        }
        else {
            valid = true;
            $(validityCheck[i]).parents(divID).find(".errMessage").innerHTML = "";
        }
    }
    return valid;
}
