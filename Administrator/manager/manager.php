<?php
/**
 * Created by PhpStorm.
 * User: shruti
 * Date: 14/6/15
 * Time: 10:07 AM
 *
 */

    require 'verifyManager.php';
    echo"<h3 class='welcome-msg'> Welcome, ".$_SESSION["Username"]."</h3>";
    echo '<input id="logout" type="button" class="btn-left logout" value="Logout"/>';
    require 'managerHome.html';
?>