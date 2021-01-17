<?php
session_start(); //session for loging in
include "connect.php";
$conn=connect(); //database handler
$site_msg=array();
//validation function which saves error messeges
function validate_reg($name,$password,$conn){ //validate name and password, check if set and length
    $error_msg=array();
    $pswd_min_len=6;
    $name_min_len=3;
    $max_len=20;
    if(empty($_POST["$password"])){
        $error_msg["password_exists"]="Heslo musí být vyplňeno";
    }
    if(!empty($_POST["$password"]) && strlen(trim($_POST["$password"]))<$pswd_min_len){
        $error_msg["password_len"]="Heslo musí být alespoň $pswd_min_len znaků dlouhé";
    }
    if(!empty($_POST["$password"]) && strlen(trim($_POST["$password"]))>$max_len){
        $error_msg["password_len"]="Heslo nesmí být více než $max_len znaků dlouhé";
    }
    if(empty($_POST["$name"])){
        $error_msg["username_exists"]="Jméno musí být vyplňeno";
    }
    if(!empty($_POST["$name"]) && strlen(trim($_POST["$name"]))<$name_min_len){
        $error_msg["name_len"]="Jméno musí být alespoň $name_min_len znaků dlouhé, mezery se nepočítají";
    }
    if(!empty($_POST["$name"]) && strlen(trim($_POST["$name"]))>$max_len){
        $error_msg["name_len"]="Jméno nesmí být více než $max_len znaků dlouhé, mezery se nepočítají";
    }
    if(!empty($_POST["$name"])){ //check if name is unique
        $sql_name=$_POST["$name"];
        $reg_find=$conn ->prepare("SELECT * FROM users WHERE username = ?");
        $reg_find->bind_param("s",$sql_name);
        $reg_find->execute();
        $reg_info=$reg_find->get_result();
        $info_row=$reg_info->fetch_array();
        if ($info_row){
            $error_msg["name_unique"]="Jméno už existuje, zvolte si jiné";
        }
    }
    return $error_msg;
}
if(isset($_POST["token"])){//check form token
    //if they dont match -> form resubmition
    if(isset($_SESSION["tokens"]["registration_form"]) && $_POST["token"]!=$_SESSION["tokens"]["registration_form"]){ //if tokens dont match -> re-submit
        header("location: index.php");
    }
    // if the match
    else{
        if(isset($_POST["register"])){
            $site_msg=validate_reg("username_reg","password_reg",$conn);
            if(empty($site_msg)){
                $salt=md5(rand()); //generating some dynamic salt
                $username=$_POST["username_reg"];
                $password=password_hash($_POST["password_reg"].$salt, PASSWORD_DEFAULT ); //hashing salty password, not storing plain text, double salted 1st -> password_hash 2nd -> my own
                $sql_reg="INSERT INTO users (username, password, salt, number_of_songs)
                VALUES  ('$username','$password','$salt', 0)";
                $conn -> query($sql_reg) or die("Registrace se nezdařila");
                $site_msg["result"]="Registrace proběhla v pořádku ";
                //avoiding maliciousness, backtracking, resubmitions
                unset($_SESSION["tokens"]["registration_form"]);
                $done=true;
                header("location: thankyou.php");
            }
        }
    }
}
if(! isset($_SESSION["tokens"]["registration_form"])){
    $_SESSION["tokens"]["registration_form"]=md5(rand()); //generating random token
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/mainStyle.css">
    <script src="js/mainScript.js"></script>
    <title>Registrace</title>
</head>
<body>
    <div id="container">
    <?php include("header.php")?>
    <div id="body">
        <h1>Registrace</h1>
        <div id="reg_form" class="web_content">
            <form method="post" <?php if(isset($done)){echo "class='hidden'";}?> id="registration_form">
                <input type="hidden" name="token" value="<?php echo $_SESSION["tokens"]["registration_form"]?>" >
                <label for="reg_form_name">Jméno:<input required title="3-20 znaků" pattern="[a-zA-Z0-9À-ž]{3,20}" placeholder="Nutné" type="text" name="username_reg" id="reg_form_name" value="<?php  echo fill_form("username_reg");?>"></label>
                <br>
                <label for="reg_form_password">Heslo:<input required title="6-20 znaků, alespoň jedno velké, jedno malé písmeno a číslo" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}" placeholder="Nutné" type="password" name="password_reg" id="reg_form_password"></label>
                <br>
                <input type="submit" value="Zaregistrovat" name="register">
            </form>
            <div id="pswd_valid">
                <h4>Heslo musí obsahovat následující:</h4>
                <p id="chars" class="invalid">6-20 Znaků</p>
                <p id="capital" class="invalid">Velké písmeno</p>
                <p id="letter" class="invalid">Malé písmeno</p>
                <p id="number" class="invalid">Číslo</p>
            </div>
            <script>
                //script for password strenght for html pattern used
                var myInput = document.getElementById("reg_form_password");
                var letter = document.getElementById("letter");
                var capital = document.getElementById("capital");
                var number = document.getElementById("number");
                var length = document.getElementById("chars");

                // display on focus
                myInput.onfocus = function() {
                    document.getElementById("pswd_valid").style.display = "block";
                }
                // hide when not in focus
                myInput.onblur = function() {
                    document.getElementById("pswd_valid").style.display = "none";
                }

                myInput.onkeyup = function() {
                    // Check lower case
                    var lowerCaseLetters = /[a-z]/g;
                    if(myInput.value.match(lowerCaseLetters)) {  
                        letter.classList.remove("invalid");
                        letter.classList.add("valid");
                    } else {
                        letter.classList.remove("valid");
                        letter.classList.add("invalid");
                    }
                    
                    // Check capital letters
                    var upperCaseLetters = /[A-Z]/g;
                    if(myInput.value.match(upperCaseLetters)) {  
                        capital.classList.remove("invalid");
                        capital.classList.add("valid");
                    } else {
                        capital.classList.remove("valid");
                        capital.classList.add("invalid");
                    }

                    // Check numbers
                    var numbers = /[0-9]/g;
                    if(myInput.value.match(numbers)) {  
                        number.classList.remove("invalid");
                        number.classList.add("valid");
                    } else {
                        number.classList.remove("valid");
                        number.classList.add("invalid");
                    }
                    
                    // Check length
                    if(myInput.value.length >= 6 && myInput.value.length<=20) {
                        length.classList.remove("invalid");
                        length.classList.add("valid");
                    } else {
                        length.classList.remove("valid");
                        length.classList.add("invalid");
                    }
                }
            </script>
            <a href="index.php" >Zpět na hlavní stránku</a>
        </div>
    </div>
    <?php
    include("footer.php");
    ?>
