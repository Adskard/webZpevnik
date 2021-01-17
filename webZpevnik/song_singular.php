<?php
include("login.php")
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/mainStyle.css">
    <script src="js/mainScript.js"></script>
    <title>
    <?php
        //getting current song title
            if($_GET){
                $current_song_id=$_GET["current_song_id"];
                $current_song_query=$conn -> query("SELECT * FROM songs WHERE id=$current_song_id") or die("title query failed");
            }
            else{
                //if get not specified, first link, gets alphabeticaly first song, will fail with no songs in database
                $current_song_query=$conn -> query("SELECT * FROM songs ORDER BY song_name ASC LIMIT 1") or die("title query failed");
            }
            $current_song=mysqli_fetch_array($current_song_query);
            $current_song_name=htmlspecialchars($current_song["song_name"], ENT_QUOTES);
            $current_song_id=$current_song["id"];
            echo $current_song_name;
        ?>
    </title>
</head>
<body>
<div id="container">
    <div id="header" <?php if(!$site_msg){echo "class='hidden'";} ?>>
        <header <?php if(!$site_msg){echo "class='hidden'";} ?>>
            <?php
                foreach($site_msg as $value){
                    echo $value;
                    echo "\n";
                } 
            ?>
        </header>
    </div>
    <div id="body">
        <h1>
            Zpěvník
        </h1>
            <?php
                include("site_nav.php");
            ?>
        <div class="song">
            <article class="song">
                <div class="overflow">
                    <h2><?php echo $current_song_name ?></h2>
                    <p>
                        <?php
                        // echo song data on page in htmlentites since they are not saved in htmlentities
                            $song_query=$conn -> query("SELECT * FROM songs WHERE id=$current_song_id") or die("song query failed");
                            $song= mysqli_fetch_array($song_query);
                            echo htmlentities($song["interpret"]);
                        ?>
                    </p>
                    <p>
                        <?php
                            echo htmlentities($song["author"]);
                        ?>
                    </p>
                    <p>
                        <?php
                            echo htmlentities($song["date_added"]);
                        ?>
                    </p>
                        <?php
                            echo "<pre>".htmlentities($song["lyrics"])."</pre>";
                        ?>
                </div>
            </article>
        </div>
    </div>
    <?php
    include("footer.php");
    ?>
</body>
</html>