<?php
    include("login.php");
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/mainStyle.css">
    <script src="js/mainScript.js"></script>
    <title>
        <?php
            //print title based on GET id
            if($_GET){
                $current_page_id=$_GET["current_page_id"];
                $current_page_query=$conn -> query("SELECT * FROM semestrálka_stánky WHERE id=$current_page_id") or die("title query failed");
            }
            else{
                //main page, since only a person with acces to the database can write site pages, then ensuring id=1 always exits isnt a problem
                $current_page_query=$conn -> query("SELECT * FROM semestrálka_stánky WHERE id=1") or die("title query failed");
            }
            $current_page=mysqli_fetch_array($current_page_query);
            $current_page_name=htmlspecialchars($current_page["page_name"],ENT_QUOTES);
            $current_page_id=$current_page["id"];
            echo $current_page_name;
        ?>
    </title>
</head>
<body>
    <div id="container">
    <?php include("header.php")?>
    <div id="body">
        <h1>
            <?php
            echo $current_page_name;
            ?>
        </h1>
            <?php
                //site navigation bar
                include("site_nav.php")
            ?>
        <div class="web_content">
            <div class="overflow">
                <article class="page content">
                    <?php
                        //echo page content based on id from GET
                        $content_query=$conn -> query("SELECT * FROM semestrálka_stánky WHERE id=$current_page_id") or die("content query failed");
                        $content= mysqli_fetch_array($content_query);
                        echo $content["content"];
                    ?>
                </article>
            </div>
        </div>
    </div>
    <?php
    include("footer.php");
    ?>