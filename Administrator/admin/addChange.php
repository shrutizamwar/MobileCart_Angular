<?php
    require 'verifyAdmin.php';

    $uid = "";
    $conn ="";
    $sql="";
    $res;
    $row;
    if(isset($_POST['user'])){
        $uid = $_POST['user'];
    }

    echo '<link rel="stylesheet" href="../website.css">';
    echo '<div class="form-container">';
    echo '<a href="admin.php" style="color:white">Back</a>';
    echo '<input id="logout" type="button" class="btn-left logout" value="Logout"/>';
    require 'logout.html';
    echo '<script type="text/javascript" src="validateAdmin.js"></script>';
    if(!$uid){
        echo '<h3 class="sub-heading" style="color:white"> Add a User</h3><hr>';
    }
    else{
        echo '<h3 class="sub-heading" style="color:white">Update a User</h3><hr>';
        $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
        $sql = "select * from Users where UserID = '".$uid."'";
        if(!$conn){
            die("Could not connect". mysql_error());
        }
        mysql_select_db("website",$conn);
        $res = mysql_query($sql);
        $row = mysql_fetch_assoc($res);
        mysql_close($conn);
    }


    echo '<form id="addUserForm" method="post" action="addUpdateUser.php" onsubmit="return validateAdmin()" novalidate>';
    if(uid){
        echo '<input type="hidden" name="UserID" value="'.$uid.'"/>';
    }
    echo '<div class="form-section">';
    echo '<div class="inline-form-element-wrapper">';
    echo '<label class="form-label">Username<span class="required-field"> *</span></label><input placeholder="Username" required class="inline-form-element" type="text" name="Username" value="';
    if($uid){
        echo $row['Username'];
    }
    echo '"/>';
    echo '</div>';


    echo '<div class="inline-form-element-wrapper">';
    echo '<label class="form-label">Password<span class="required-field"> *</span></label><input placeholder="Password" required class="inline-form-element" type="password" name="Password" value=""/>';
    echo '</div>';
    echo '<span class="errMessage"></span>';
    echo '</div>';

    echo '<div class="form-section">';
    echo '<div class="inline-form-element-wrapper">';
    echo '<label class="form-label">Usertype<span class="required-field"> *</span></label>';
    echo '<select required name="Usertype" class="inline-form-element">';

    if($uid){
        $usertype = $row["UserType"];
        if( $usertype == "Administrator"){
            echo '<option value="Administrator" selected>Administrator</option>';
            echo '<option value="Manager">Manager</option>';
            echo '<option value="Employee">Employee</option>';
        }
        else if($usertype == "Manager"){
            echo '<option value="Administrator">Administrator</option>';
            echo '<option value="Manager" selected>Manager</option>';
            echo '<option value="Employee">Employee</option>';
        }
        else{
            echo '<option value="Administrator">Administrator</option>';
            echo '<option value="Manager">Manager</option>';
            echo '<option value="Employee" selected>Employee</option>';
        }
    }
    else{
        echo '<option value="Administrator">Administrator</option>';
        echo '<option value="Manager">Manager</option>';
        echo '<option value="Employee">Employee</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '<span class="errMessage"></span>';
    echo '</div>';

    echo '<div class="form-section">';
    echo '<div class="inline-form-element-wrapper">';
    echo '<label class="form-label">First Name<span class="required-field"> *</span></label><input placeholder="First Name" required class="inline-form-element" type="text" name="Firstname" value="';
    if($uid){
        echo $row['Firstname'];
    }
    echo '"/>';
    echo '</div>';

    echo '<div class="inline-form-element-wrapper">';
    echo '<label class="form-label">Last Name<span class="required-field"> *</span></label><input placeholder="Last Name" required class="inline-form-element" type="text" name="Lastname" value="';
    if($uid){
        echo $row['Lastname'];
    }
    echo '"/>';
    echo '</div>';
    echo '<span class="errMessage"></span>';
    echo '</div>';

    echo '<div class="form-section">';
    echo '<div class="inline-form-element-wrapper">';
    echo '<label class="form-label">Gender<span class="required-field"> *</span></label>';
    if($uid){
        if($row['Gender'] == "Male"){
            echo '<input required class="inline-form-element" type="radio" name="Gender" value="Male" id="male" checked/><label for="male">Male</label>';
            echo '<input class="inline-form-element" type="radio" name="Gender" value="Female" id="female"/><label for="female">Female</label>';
        }
        else{
            echo '<input required class="inline-form-element" type="radio" name="Gender" value="Male" id="male"/><label for="male">Male</label>';
            echo '<input class="inline-form-element" type="radio" name="Gender" value="Female" id="female" checked/><label for="female">Female</label>';
        }
    }
    else{
        echo '<input required class="inline-form-element" type="radio" name="Gender" value="Male" id="male"/><label for="male">Male</label>';
        echo '<input class="inline-form-element" type="radio" name="Gender" value="Female" id="female"/><label for="female">Female</label>';
    }
    echo '</div>';

    echo '<div class="inline-form-element-wrapper">';
    echo '<label class="form-label">Date of Birth<span class="required-field"> *</span></label><input required class="inline-form-element" type="date" name="DOB" value="';
    if($uid){
        echo $row['DOB'];
    }
    echo '"/>';
    echo '</div>';
    echo '<span class="errMessage"></span>';
    echo '</div>';

    echo '<div class="form-section">';
    echo '<div class="inline-form-element-wrapper">';
    echo '<label class="form-label">Address</label><textarea placeholder="Address" class="inline-form-element" name="Address" >';
    if($uid){
        echo $row['Address'];
    }
    echo '</textarea>';
    echo '</div>';
    echo '</div>';

    echo '<div class="form-section">';
    echo '<div class="inline-form-element-wrapper">';
    echo '<label class="form-label">Contact number<span class="required-field"> *</span></label><input required placeholder="XXX-XXX-XXXX" maxlength="12" pattern="^\d{3}-\d{3}-\d{4}$" class="inline-form-element" type="tel" name="ContactNumber" value="';
    if($uid){
        echo $row['ContactNo'];
    }
    echo '"/>';
    echo '</div>';
    echo '<span class="errMessage"></span>';
    echo '</div>';

    echo '<div class="form-section">';
    echo '<div class="inline-form-element-wrapper">';
    echo '<label class="form-label">Email<span class="required-field"> *</span></label><input placeholder="abc@gmail.com" required class="inline-form-element" type="email" name="Email" value="';
    if($uid){
        echo $row['EmailAddress'];
    }
    echo '"/>';
    echo '</div>';
    echo '<div class="inline-form-element-wrapper">';
    echo '<label class="form-label">Salary<span class="required-field"> *</span></label><input placeholder="Salary" class="inline-form-element" required min="0" max="9999999" step="any" type="number" name="Salary" value="';
    if($uid){
        echo $row['Salary'];
    }
    echo '"/>';
    echo '</div>';
    echo '<span class="errMessage"></span>';
    echo '</div>';

    echo '<br><br>';
    echo '<input class ="submit-btn" type="submit"  name ="submit" value="';
    if($uid) {
        echo 'Update';
    }
    else{
        echo 'Add';
    }
    echo ' User"';
    echo '"/>';
    echo '</form>';
    echo '</div>';
?>