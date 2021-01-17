<!--Header for every page, displays site messages from server, if there are none, it is hidden-->
<div id="header" <?php if(!$site_msg){echo "class='hidden'";} ?>>
        <header >
            <div class="modal_content" id="modal_header">
                <span class="close_button">&times;</span>
                <?php
                    //print site messages
                    foreach($site_msg as $value){
                        echo "<p>".$value."</p>"; //generated by server, dont need htmlspecialchars or entities
                    } 
                ?>
            </div>
        </header>
        <!-- "x" button functionality on header-->
        <script>
            var modal= document.getElementById("header");
            var close= document.getElementsByClassName("close_button")[0];
            close.onclick= function(){
                modal.style.display="none";
            }
        </script>
    </div>