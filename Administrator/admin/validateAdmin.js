/**
 * Created by shruti on 22/6/15.
 */

function validateAdmin(){
    var valid = true;

    var errMessages = $("#addUserForm").find(".errMessage");
    var validityCheck = $("#addUserForm input:invalid");
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

    var dob = $("#addUserForm input[name='DOB']");
    var today = new Date();
    var todayMonth = today.getMonth() + 1;
    var todayDay = today.getDate();
    var todayYear = today.getFullYear();
    var todayDateText = todayYear + "/" + todayMonth + "/" + todayDay;

    var todayDate = Date.parse(todayDateText);
    var dobDate = Date.parse(dob.val());
    if(dobDate > todayDate){
        valid = false;
        dob.parents(".form-section").find(".errMessage").text("Please enter a valid date of birth");
    }
    return valid;
}