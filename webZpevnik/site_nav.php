<nav class="site_nav">
    <div id="nav_list">
        <a href="songs.php">Zpěvník</a>
        <a href="users.php">Uživatelé</a>
        <?php
            //List all site names with correct links in list - site navigation
            $site_query=$conn -> query("SELECT * FROM semestrálka_stánky") or die("site_nav query failed");
            while($rows= mysqli_fetch_array($site_query)){
                echo "<a href=\"index.php?current_page_id=".$rows["id"]."\">".htmlspecialchars($rows["page_name"],ENT_QUOTES)."</a>";
            }
        ?>
        <!--Login form-->
        <div class="right" id="login_list">
            <form method="post" id="login_form" class="left <?php if( isset($_SESSION["username"])){echo "hidden";}?>"> 
                <input type="hidden" name="token" value="<?php echo $_SESSION["tokens"]["login_form"]?>">
                <label for="nav_username">Jméno: <input title="3-20 znaků písmena a čísla bez mezer" pattern="[a-zA-Z0-9À-ž]{3,20}" required type="text" name="username" id="nav_username" value="<?php  echo fill_form("username")?>"></label>
                <label for="nav_password">Heslo: <input title="6-20 znaků" pattern=".{6,20}" required type="password" name="password" id="nav_password"></label>
                <button id="site_nav_submit" type="submit" name="login"> Přihlásit</button>
            </form>
            <a class='right <?php if( isset($_SESSION["username"])){echo "hidden";}?>'  href="reg.php">Registrace</a>
            <p class="left <?php if(! isset($_SESSION["username"])){echo "hidden";}?>">Vítej <?php if(isset($_SESSION["username"])) echo htmlspecialchars($_SESSION["username"],ENT_QUOTES);?> !</p>
            <form class='left <?php if(! isset($_SESSION["username"])){echo "hidden";}?>' method="post"><button type="submit" name="logout">Odhlásit</button></form>
        </div>
    </div>
</nav>