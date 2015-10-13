<?php
    require 'verifyEmployee.php';
    echo"<h3 class='welcome-msg'> Welcome, ".$_SESSION["Username"]."</h3>";
    echo '<input id="logout" type="button" class="btn-left logout" value="Logout"/>';
    require 'employeeHome.html';
?>