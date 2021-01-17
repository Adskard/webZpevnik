<?php
// conneting to mysql database and returning handler
function connect()
{
    $host="localhost";
    $user="";
    $password="";
    $database="";
    $connection = new mysqli($host,$user,$password,$database) or die('Connect Error (' . $connection->connect_errno . ') '.$connection->connect_error);
    if ($connection->connect_error) {
        die('Connect Error (' . $connection->connect_errno . ') '
                . $connection->connect_error);
    }
    return $connection;
}
// disconnecting from database
function disconnect($connection){
    $connection->close();
}
// auto fill form, remember posted data, if wrong input, not used on passwords
function fill_form($filling){
    if( isset($_POST["$filling"])){
        return htmlspecialchars($_POST["$filling"]); //XSS prevention
    }
    return "";
}

?>