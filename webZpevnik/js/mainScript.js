function show_result(str){
    //Check if client is writing
    if (str.length==0) {
        document.getElementById("hints").innerHTML="";
        document.getElementById("hints").style.display="none";
        return;
    }
    //Open xmlhttprequest
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        var searchhints=document.getElementById("hints");
        if (this.readyState==4 && this.status==200) {
            searchhints.innerHTML=this.responseText;
            searchhints.style.display="block";
        }
    }
    xmlhttp.open("GET","livesearch.php?search="+str,true);
    xmlhttp.send();
}

function validate(e){
    //Check all elements in song add form
    var name=document.getElementById("input_song_name").value;
    var inter=document.getElementById("input_song_interpret").value;
    var text=document.getElementById("text_song_lyrics").value;
    var regex=RegExp("/[a-zA-ZÀ-ž\s0-9]{1,100}/g");
    var errs=Array();
    if( regex.test(name) || name.length>100 || name.length<1){
        errs.push("Jméno musí být 1-100 znaků dlouhé a obsahovat pouze znaky z rozšířené latinky a číslice");
    }
    if( regex.test(inter)  || inter.length>100 || inter.length<1){
        errs.push("Interpret musí být 1-100 znaků dlouhý a obsahovat pouze znaky z rozšířené latinky a číslice");
    }
    if(text.length<20){
        errs.push("Text musí být alespoň 20 znaků dlouhý");
    }
    if(errs.length>0){
        alert(errs.join("\n"));
        e.preventDefault(); //preventing submition
        return false;
    }
    else{
        return true;
    } 
}


