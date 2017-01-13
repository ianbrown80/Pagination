<?php


$connection = new mysqli("localhost", "root", "", "mini_projects");
if (mysqli_connect_error()) {
    
    die(mysqli_connect_error());
    
}

?>