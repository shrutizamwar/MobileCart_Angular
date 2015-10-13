<?php
    require 'verifyAdmin.php';
    $selection = $_POST['submit'];
    $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
    $sql = "select * from Users";
    if(!$conn){
        die("Could not connect". mysql_error());
    }
    mysql_select_db("website",$conn);
    $res = mysql_query($sql);
    echo '<link rel="stylesheet" href="../website.css" type="text/css">';
    echo '<div class="form-container">';
    echo '<a href="admin.php" style="color:white">Back</a>';
    echo '<input id="logout" type="button" class="btn-left logout" value="Logout"/>';
    require 'logout.html';
    if ($selection == "Change User") {
        echo '<h3 class="sub-heading" style="color:white"> Update a User</h3><hr>';
    }
    else{
        echo '<h3 class="sub-heading" style="color:white"> Delete a User</h3><hr>';
    }
    echo "<div id='changeUpdateForm'>";
    echo "<div class='row'>";
    echo "<div class='column' style='width:84px'>User ID</div>";
    echo "<div class='column'>User Type</div>";
    echo "<div class='column'>First Name</div>";
    echo "<div class='column'>Last Name</div>";
    echo "<div class='column'>Gender</div>";
    echo "<div class='column'>Salary</div>";
    echo "</div>";
    if ($selection == "Update User") {

        echo '<form method="post" action="addChange.php">';
        while($row = mysql_fetch_assoc($res)) {
            echo "<div class='row'>";
            echo "<div class='column' style='width:84px'><input type='radio' value='".$row["UserID"]."' name='user' />".$row["UserID"]."</div>";
            echo "<div class='column'>".$row["UserType"]."</div>";
            echo "<div class='column'>".$row["Firstname"]."</div>";
            echo "<div class='column'>".$row["Lastname"]."</div>";
            echo "<div class='column'>".$row["Gender"]."</div>";
            echo "<div class='column'>".$row["Salary"]."</div>";
            echo "</div>";
        }
        echo "<br><br>";
        echo '<input class ="submit-btn" type = "submit" name="submit" value="Update"/>';
        echo '</form>';
    } else {

        echo '<form method="post" action="deleteUsers.php">';
        while($row = mysql_fetch_assoc($res)) {
            echo "<div class='row'>";
            echo "<div class='column' style='width:84px'><input type='checkbox' value='".$row["UserID"]."' name='user[]' />".$row["UserID"]."</div>";
            echo "<div class='column'>".$row["UserType"]."</div>";
            echo "<div class='column'>".$row["Firstname"]."</div>";
            echo "<div class='column'>".$row["Lastname"]."</div>";
            echo "<div class='column'>".$row["Gender"]."</div>";
            echo "<div class='column'>".$row["Salary"]."</div>";
            echo "</div>";
        }
        echo "<br><br>";
        echo '<input class ="submit-btn" type = "submit" name="submit" value="Delete"/>';
        echo '</form>';
    }
    echo '</div>';
    echo "</div>";
    mysql_close($conn);
?>