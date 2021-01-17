<?php
include("connect.php");
$conn=connect();
// Get information sent by xmlhttprequest sent by searchbar
if(isset($_GET["search"])){
    $searchstr=$_GET["search"]."%";
    $sql = "SELECT * FROM songs WHERE song_name LIKE ?"; //find similar terms in database
    $stmt=$conn ->prepare($sql);
    $stmt->bind_param("s",$searchstr);
    $stmt->execute() or die("Could not execute $sql".mysqli_error($conn));
    $result=$stmt->get_result();
    if(mysqli_num_rows($result)>0){ //Check if we got a match
        while($row=mysqli_fetch_array($result)){
            echo "<a href='song_singular.php?current_song_id=".$row["id"]."'>".$row["song_name"]."</a>"; //send bacck
        } 
    }
    else{
        echo "<p>Žádné výsledy</p>";
    }
}
disconnect($conn);
?>