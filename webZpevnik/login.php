<?php
session_start(); //session for loging in
include "connect.php"; //file with functions for database connection
$conn=connect(); //database handler
$site_msg=array(); //site messages to display
//logout -> that means unsetting session username, not whole session, throughout the site we are using it for different things
if(isset($_POST["logout"]) && isset($_SESSION["username"])){
    unset($_SESSION["username"]);
    $_POST=array();
    $site_msg["logout_msg"]="Nashledanou";
}
//Check if token has been generated
if(isset($_POST["token"])){
    //if tokens saved and submitted dont match
    if(isset($_SESSION["tokens"]["login_form"]) && $_POST["token"]!=$_SESSION["tokens"]["login_form"]){
        $where=$_SERVER["PHP_SELF"];
        header("location: $where");
    }
    else{
        //tokens match
        if(isset($_POST["login"]) && ! isset($_SESSION["username"])){ //check if client isnt logged in
            if( isset($_POST["username"]) && isset($_POST["password"])){ //check if name and password are set, no use validating
                $sql_find=$conn ->prepare("SELECT * FROM users WHERE username = ?");
                $sql_find->bind_param("s",$_POST["username"]);
                $sql_find->execute() or die("Could not execute query ".mysqli_error($conn));
                $user_info=$sql_find->get_result();
                $row=$user_info->fetch_array();
                if ($row){ //if we find correct username
                    //check password
                    if(password_verify($_POST["password"].$row["salt"], $row["password"])){
                        $_SESSION["username"]=$row["username"];
                        $site_msg["login_msg"]="Úspěšné prihlášení";
                        unset($_SESSION["tokens"]["login_form"]);
                    }
                    else{
                        $site_msg["login_msg"]="Nesprávné heslo";
                    }
                }
                else{
                    $site_msg["login_msg"]="Nesprávné jméno";
                }
            }
            else{
                $site_msg["login_msg"]="Vyplňte jméno i helso";
            }
        }
        else{
            $site_msg["login_msg"]="Už jste přihlášeni!";
        }
    }
}
if(! isset($_SESSION["tokens"]["login_form"])){
    $_SESSION["tokens"]["login_form"]=md5(rand()); //generating random token
}
?>