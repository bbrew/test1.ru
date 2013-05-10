<?php

$id = $_POST["id"];
$rating = $_POST["rating"];
$rating_type = array("like", "dislike");

if(in_array($rating, $rating_type)){
 
include("db.php");
    
$content = 'first';
$ratings = 'ratings';

$ip = $_SERVER["REMOTE_ADDR"]; //IP ADDRESS
    
    //CHECKS IF $id EXISTS
    $q = mysql_query("SELECT * FROM $content WHERE id='$id'");
    $r = mysql_fetch_assoc($q);
    $id = $r["id"]; //NEW ID VARIABLE, USED TO CHECK IF IT'S IN THE DATABASE
    
    //COUNTS LIKES & DISLIKES IF $id EXISTS
    if($id)
    {
        //CHECKS IF USER HAS ALREADY RATED CONTENT
        $q = mysql_query("SELECT * FROM $ratings WHERE id='$id' AND ip='$ip'");
        $r = mysql_fetch_assoc($q); //CHECKS IF USER HAS ALREADY RATED THIS ITEM
        
        //IF USER HAS ALREADY RATED
        if($r["rating"]){
            if($r["rating"]==$rating){
                mysql_query("DELETE FROM $ratings WHERE id='$id' AND ip='$ip'"); //DELETES RATING
            } else {
                mysql_query("UPDATE $ratings SET rating='$rating' WHERE id='$id' AND ip='$ip'"); //CHANGES RATING
            }
        } else {
            mysql_query("INSERT INTO $ratings VALUES('$rating','$id','$ip')"); //INSERTS INITIAL RATING
        }
        
        //COUNT LIKES & DISLIKES
        $q = mysql_query("SELECT * FROM $ratings WHERE id='$id' AND rating='like'");
        $likes = mysql_num_rows($q);
        $q = mysql_query("SELECT * FROM $ratings WHERE id='$id' AND rating='dislike'");
        $dislikes = mysql_num_rows($q);
        
        //LIKE & DISLIKE IMAGES
        $l = '/images/like.png';
        $d = '/images/dislike.png';
        
        //CHECKS IF USER HAS ALREADY RATED CONTENT
        $q = mysql_query("SELECT * FROM $ratings WHERE id='$id' AND ip='$ip'");
        $r = mysql_fetch_assoc($q); //CHECKS IF USER HAS ALREADY RATED THIS ITEM
        
        //IF SO, THE RATING WILL HAVE A SHADOW
        if($r["rating"]=="like"){
            $l = '/images/liked.png';
        }
        if($r["rating"]=="dislike"){
            $d = '/images/disliked.png';
        }
        
		
		mysql_query("UPDATE first SET likes = '$likes', dislikes = '$dislikes' WHERE id = $id");
		
		
        //FORM & THE NUMBER OF LIKES & DISLIKES
        $m = '<img id="like" onClick="rate($(this).attr(\'id\'))" src="'.$l.'"> &nbsp;&nbsp; <img id="dislike" onClick="rate($(this).attr(\'id\'))" src="'.$d.'"> <br>'.$likes . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $dislikes;
    
        //EVERYTHING HERE DISPLAYED IN HTML AND THE "ratings" ELEMENT FOR AJAX
        echo $m;
    }
    else
    {
    echo "Invalid ID";
    }
}

?>