 <?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpassword = "";
    $dbname = "library";

    $con = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);
    if($con->connect_error){
        die($con->connect_error);
    }
 ?>