<?php
    include("login.php");
    //filtering and order saved in session
    if(! empty($_POST["filters"])){
        $_SESSION["order"]="ORDER BY ".$_POST["orders"];
        if(isset($_POST["only_active"])){
            $_SESSION["filter"]="WHERE number_of_songs > 0";
        }
        else{
            $_SESSION["filter"]="";
        }
    }
    else{
        if(empty($_SESSION["order"])){
            $_SESSION["order"]="ORDER BY username";
        }
        if(empty($_SESSION["filter"])){
            $_SESSION["filter"]="";
        }
    }
    $order=$_SESSION["order"];
    $filter=$_SESSION["filter"];
    $total_users_res = $conn->query("SELECT COUNT(*) FROM users $filter");
    $total_users=mysqli_fetch_array($total_users_res)[0];
    $page_limit = 5; //users on page
    $total_pages = ceil($total_users/$page_limit);
    if(isset($_GET["page"])){
        $page = $_GET["page"];
    }
    else{
        $page = 1;
    }
    $offset = ($page -1)  * $page_limit;
    $sql_page="SELECT * FROM users $filter $order  LIMIT $offset,$page_limit ";
    $page_data= $conn ->query($sql_page) or die("page_data query error query: ".$sql_page." ".print_r($_SESSION).print_r($_POST));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/mainStyle.css">
    <script src="js/mainScript.js"></script>
    <title>Users</title>
</head>
<body>
    <div id="container">
    <?php include("header.php")?>
    <div id="body">
        <h1>Seznam uživatelů</h1>
        <?php 
        include("site_nav.php")?>
        <div class="web_content">
            <div class="overflow" id="users_content">
                <!--Selecting options -order and filter-->
                <form method="post">
                    <label for="order_users">Filtrovat uživatele podle:</label>
                    <select name="orders" id="order_users">
                        <option value="username" <?php if(isset($_SESSION["order"]) && $_SESSION["order"]=="ORDER BY username"){ echo "selected";}?>>Jméno</option>
                        <optgroup label="Počet přidaných písní"> 
                            <option value="number_of_songs DESC" <?php if(isset($_SESSION["order"]) && $_SESSION["order"]=="ORDER BY number_of_songs DESC"){ echo "selected";}?>>Od největšího</option>
                            <option value="number_of_songs ASC" <?php if(isset($_SESSION["order"]) && $_SESSION["order"]=="ORDER BY number_of_songs ASC"){ echo "selected";}?>>Od nejmenšího</option>
                        </optgroup>
                    </select>
                    <br>
                    <label for="active_users">Pouze uživatele, kteří přidali píseň</label>
                    <input type="checkbox" name="only_active" id="active_users" <?php if(! empty($_SESSION["filter"])){ echo "checked";}?>>
                    <input type="submit" value="Filtrovat" name="filters">
                </form>
                <!-- Pagination navigation-->
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
                <!-- table of users and number of songs they added-->  
                <table>
                    <tr>
                        <td>Jméno</td>
                        <td>Počet přidaných písní</td>
                    </tr>
                    <?php
                    $index=0;
                        while($row = mysqli_fetch_array($page_data)){
                            echo "<tr>"."<td>".htmlentities($row["username"])."</td>"."<td>".$row["number_of_songs"]."</td>"."</tr>";
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <?php
    include("footer.php");
    ?>