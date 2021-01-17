<?php
    include("login.php");
    //pagination
    $total_users_res = $conn->query('SELECT COUNT(*) FROM songs');
    $total_users=mysqli_fetch_array($total_users_res)[0];
    $page_limit = 100;// song limit on page
    $total_pages = ceil($total_users/$page_limit);
    if(isset($_GET["page"])){
        $page = $_GET["page"];
    }
    else{
        $page = 1; //first time visiting
    }
    $offset = ($page -1)  * $page_limit;
    $sql_page="SELECT * FROM songs ORDER BY song_name  LIMIT $offset,$page_limit "; //alphabetical order
    $page_data= $conn ->query($sql_page) or die("page_data query error query: ".$sql_page." ".print_r($_SESSION).print_r($_POST));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/mainStyle.css">
    <script src="js/mainScript.js"></script>
    <title>
        Zpěvník
    </title>
</head>
<body>
    <div id="container">
    <?php include("header.php")?>
    <div id="body">
        <h1>
            Zpěvník
        </h1>
            <?php
                include("site_nav.php");
            ?>
        <div class="web_content">
            <div class="overflow" id="songslist_all">
                <!--Searchbox for AJAX search through songs in database-->
                <div id="searchbox">
                    <form >
                    <label for="searchbar">Vyhledávání: </label>
                    <input name="search_enter" id="searchbar" type="search" size="25" onkeyup="show_result(this.value)">
                    </form>
                    <div id="hints"></div>  
                </div>
                <!--pagination navigation-->
                <ul class="pagination">
                    <li><a href="?page=1">První</a></li>
                    <li class="<?php if($page <= 1){ echo 'disabled'; } ?>">
                        <a href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1); } ?>">Předcházející</a>
                    </li>
                    <li class="<?php if($page >= $total_pages){ echo 'disabled'; } ?>">
                        <a href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1); } ?>">Další</a>
                    </li>
                    <li><a href="?page=<?php echo $total_pages; ?>">Poslední</a></li>
                </ul>  
                <hr>
                    <div id="songs_list">
                        <a id='add' href="add.php"> Přídat píseň</a>
                        <?php
                            //echo page content based on id from GET
                            while($songs=mysqli_fetch_array($page_data)){
                                echo "<a href=\"song_singular.php?current_song_id=".htmlspecialchars($songs["id"],ENT_QUOTES)."\">".htmlspecialchars($songs["song_name"],ENT_QUOTES)."</a>";
                            }
                        ?>
                    </div>
            </div>
        </div>
    </div>
    <?php
    include("footer.php");
    ?>