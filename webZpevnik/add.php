<?php
    include("login.php");
    // check if token is available
    if(isset($_POST["token_add"])){
        // token saved and in form differ => resubmition on client => send client away
        if(isset($_SESSION["tokens"]["add_form"]) && $_POST["token_add"]!=$_SESSION["tokens"]["add_form"]){
            $where=$_SERVER["PHP_SELF"];
            $_POST=array();
            header("location: $where");
        }
        // tokens match
        else{
            if(isset($_POST["add_song"])){
                // Check if user is logged in
                if( isset($_SESSION["username"])){
                    //Check data validity
                    if( !empty(trim($_POST["song_name"])) && !empty(trim($_POST["song_lyrics"])) && !empty(trim($_POST["song_interpret"]))){
                        $author=$_SESSION["username"];
                        $now=date("Y-m-d H:i:s");
                        $song_interpret=trim($_POST["song_interpret"]); // saving data without htmlspecial, using it with output from database
                        $song_name=trim($_POST["song_name"]) ;
                        $song_lyrics=trim($_POST["song_lyrics"]);
                        $sql_insert="INSERT INTO songs (song_name, lyrics, interpret, author, date_added)
                        VALUES ('$song_name', '$song_lyrics', '$song_interpret', '$author','$now')";
                        $conn ->query($sql_insert) or die("nepovedlo se přidat $sql_insert"); //adding to database
                        $site_msg["add_result"]="Píseň úspěšně přidána";
                        $user_insert="UPDATE users SET number_of_songs= number_of_songs + 1 WHERE username='$author'";
                        $conn ->query($user_insert) or die("Couldnt update user songs");
                        unset($_SESSION["tokens"]["add_form"]); //unsetting token
                    }
                    else{
                        $site_msg["add_result"]="Všechna pole musí být vyplněná";
                    }  
                }
                else{
                    $site_msg["add_result"]="Nejste přihlášeni";
                } 
            }
        }
    }
    if(! isset($_SESSION["tokens"]["add_form"])){
        $_SESSION["tokens"]["add_form"]=md5(rand()); //generating random token for add_form
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/mainStyle.css">
    <script src="js/mainScript.js"></script>
    <title>
        Přidat píseň
    </title>
</head>
<body>
    <div id="container">
    <?php include("header.php")?>
    <div id="body">
        <h1>
            Přidat píseň
        </h1>
        <?php
        // including navigation bars
            include("site_nav.php");
        ?>
        <div class="song" id="add_form">
            <div class="overflow">
                <form name="song_submit" onsubmit="validate(event)" method="post" <?php if(isset($site_msg["add_result"]) && $site_msg["add_result"]=="Píseň úspěšně přidána"){echo "class='hidden'";} ?>>
                    <input type="hidden" name="token_add" value="<?php if(isset($_SESSION["tokens"]["add_form"])){echo $_SESSION["tokens"]["add_form"];}?>">
                    <label for="input_song_name">Jméno písničky:<input placeholder="Nutné" title="Jen písmena a čísla" pattern="[a-zA-Z0-9À-ž\s]{1,}" required type="text" name="song_name" id="input_song_name" value="<?php  echo fill_form("song_name")?>"></label>
                    <label for="input_song_interpret">Interpret písničky:<input placeholder="Nutné" title="Jen písmena a čísla" pattern="[a-zA-Z0-9À-ž\s]{1,}" required type="text" name="song_interpret" id="input_song_interpret" value="<?php  echo fill_form("song_interpret")?>"></label><br>
                    <!-- pattern for the text area is not needed due to how lyrics with chords are notated spaces matter, text can be long -->
                    <label for="text_song_lyrics">Text písničky: <br>
                        <textarea placeholder="Nutné" required name="song_lyrics" id="text_song_lyrics" cols="30" rows="10" ><?php echo fill_form("song_lyrics")?></textarea> 
                    </label>
                    <input type="submit" id="submit_song" value="Přidat písničku" name="add_song">
                </form>
                <?php if(isset($site_msg["add_result"]) && $site_msg["add_result"]=="Píseň úspěšně přidána"){echo "<h2>".$site_msg["add_result"]."</h2>";} //display on succesful add?>
                <?php if(isset($site_msg["add_result"]) && $site_msg["add_result"]=="Píseň úspěšně přidána"){ echo "<a href='songs.php'>Zpět na zpěvník</a>";} ?>
            </div>
        </div>     
    </div>
    <?php
    include("footer.php");
    ?>
