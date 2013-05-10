<?php

session_start();

include("db.php");
mysql_query("set names 'cp1251'");
$adressaita = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

$client_id = '3630859'; // ID приложения
$client_secret = 'VCXzAWdyY2iFG6nhYOhq'; // Защищённый ключ
$redirect_uri = 'http://test1.ru/'; // Адрес сайта

    $url = 'http://oauth.vk.com/authorize';
    $params = array(
        'client_id'     => $client_id,
        'redirect_uri'  => $redirect_uri,
        'response_type' => 'code'
    );
	
if (isset($_GET['code'])) {
    $result = false;
    $params = array(
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $_GET['code'],
        'redirect_uri' => $redirect_uri
    );
    $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
    if (isset($token['access_token'])) {
        $params = array(
            'uids'         => $token['user_id'],
            'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
            'access_token' => $token['access_token']
        );
        $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
        if (isset($userInfo['response'][0]['uid'])) {
            $userInfo = $userInfo['response'][0];
            $result = true;
			$_SESSION['result']= 1;
            $imya = iconv('UTF-8', 'windows-1251', $userInfo['first_name']);
			$familiya = iconv('UTF-8', 'windows-1251', $userInfo['last_name']);
			$_SESSION['imyas']=$imya;
			$_SESSION['familiya']=$familiya;
            $_SESSION['vkid']=$userInfo['uid'];
            $_SESSION['polvk']=$userInfo['sex'];
            $_SESSION['ava']=$userInfo['photo_big'];
            $_SESSION['bdate']=$userInfo['bdate'];
        }
    }
}



$idr = mysql_query("SELECT id FROM first ORDER BY id DESC LIMIT 1"); 
$id = mysql_result($idr, 0);

if ($_SERVER['REQUEST_URI'] == "/") {
	if (empty($_GET["id"]))
	{
		header ('Location: /index.php?id='.$id);
	}
}
if (isset($_GET['code'])) {
    header ('Location: /index.php?id='.$id);
}

if (isset($_POST["linkd"])){
	if (empty($_POST["linkd"]) || empty($_POST["named"]))
	{
		//echo "Вы не ввели инфу";
	}
	else 
	{
		mysql_query("INSERT INTO `first` SET
		`link` = '{$_POST['linkd']}',
		`name` = '{$_POST['named']}',
		`description` = '{$_POST['descriptiond']}'");
	}
}
	$a = $_GET["id"];

	//$query = mysql_query("SELECT DISTINCT `id`, `link`, `name`, `description` FROM `first`");
	$query1 = mysql_query("SELECT DISTINCT `link`, `name`, `description` FROM `first` WHERE `id` = $a");
	
	$query12 = mysql_query("SELECT DISTINCT `id` FROM `first` WHERE `id` = (SELECT MAX(`id`) FROM `first` WHERE `id` < $a)");
	$query13 = mysql_query("SELECT DISTINCT `id` FROM `first` WHERE `id` = (SELECT MIN(`id`) FROM `first` WHERE `id` > $a)");
	
	$row2  = mysql_fetch_assoc($query12);
	$nextvid = $row2["id"];
	$row3  = mysql_fetch_assoc($query13);
	$prevvid = $row3["id"];
	
	if (false !== strpos($adressaita,'id=')) {
    
		while ( $row1  = mysql_fetch_assoc($query1) ) {
			preg_match("/[\w]{11}/", $row1["link"], $matches);
			$namevid = $row1["name"];
			$descr = $row1["description"];
			//echo $matches[0];
		}
	}
	



















	

	
	
	
	
	
	
	
	
	
?>



<html>
<head>
<meta name="description" content="video">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>video</title>
<link rel="stylesheet" type="text/css" href="/template.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script> 
<link rel="stylesheet" href="/js/jquery.fancybox.css" type="text/css" media="screen" /> 
<script type="text/javascript" src="/js/jquery.fancybox.pack.js"></script> 
 







<script type="text/javascript">
$(document).ready(function() {
   $(".open").fancybox();
});
</script>
    
<script>
   $('.open_box').fancybox();
</script>
<!--    
<script type="text/javascript"> 
function onShow(id){var obj=$('#'+id); 
if(obj.width()=='0'){obj.animate({width:'270px',opacity:'1'},200)} 
else{obj.animate({width:'0px',opacity:'0'},100)}}; 
</script>
<script type="text/javascript"> 
function hidek()

</script>
<script type="text/javascript"> 
function onShow1(id){var obj=$('#'+id); 
if(obj.width()!='0'){obj.animate({width:'0px',opacity:'0'},100); obj.style.overflow = hidden;}} 
</script>
-->





</head>


<body>
	<div id="info">
		<div id="menu">
			<?php
            if (isset($_SESSION['result'])) {
            //echo "Социальный ID пользователя: " . $userInfo['uid'] . '<br />';
            //echo "Имя пользователя: " . $imya . '<br />';
            //echo "Ссылка на профиль пользователя: " . $userInfo['screen_name'] . '<br />';
            //echo "Пол пользователя: " . $userInfo['sex'] . '<br />';
            //echo "День Рождения: " . $userInfo['bdate'] . '<br />';
            //echo '<img src="' . $userInfo['photo_big'] . '" />'; echo "<br />";
            ?>
            <div style="margin:10 0 0 10; height:80; width:80; background-size: 80px;   border-radius:10px; background-image: url(<?php echo $_SESSION['ava'] ?>)" id="userinfo">
            </div>
            
            <div id="userinfoname">
            	<?php echo $_SESSION['imyas'].' '.$_SESSION['familiya']; ?><br>
                <span style=" color:#999999; font-size:10px"><?php echo "@id".$_SESSION['vkid']; ?></span><br>
                <a style=" position:absolute; top:65; color:#CC6600; font-size:13;" href="http://test1.ru/logout.php">Выход</a>
            </div>
            
            <div id="addvid" >
            <a href="#hide_box" id="modal" class="open_box" onClick="fancybox1()" ><img src="images/addvid.png"></img></a>
			</div>        
            <?php }
			else {
			echo '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '"><img style="margin:0 0 0 50" src="/images/vkontakte-logo.png"></a></p>';
			echo '<div style=" margin:0 0 0 10; width: 230;"><span style="color:#BABABA; font:13px Arial, Helvetica, sans-serif;">Чтобы полноценно воспользоваться сайтом, авторизируйтесь через ВКонтакте. <br>Вы автоматически зарегистрируетесь и сможете соревноваться с другими пользователями за первое место в ТОПе. Кроме этого вы сможете оставлять комментарии и делиться своими видео с другими пользователями.</span></div>';
			} ?>
            <br>
             
            
            
        
        
        
        <img style="position:absolute; left:226; top:50%;" id="clickme2" src="/images/close.png"></img>
        
		</div>
	</div>

	<img style="position:absolute; top:50%; z-index:1; left:-17;" id="clickme" src="/images/open.png"></img>
	
	<div id="logo"></div>
    
	
    <div id="videogeneral">
        <div id="videoname">
            
            <div id="transblock"></div>
            <span style="position:relative; font: bold 17pt Arial"><?php echo $namevid; ?></span>
            
            <!-- <div id="link">
                ЗДЕСЬ БЫЛ КОД ОРМЫ ДОБАВЛЕНИЯ ВИДЕО
            </div> -->
    
            <div id="arrowleft">
                <?php if ($id > $_GET["id"]) {?>
                <a href="http://test1.ru/index.php?id=<?php echo $prevvid; ?>" ><img src="/images/left.png" ></a>
                <?php } ?>
            </div>
            <div id="arrowright">
                <?php if (1 < $_GET["id"]) {?>
                <a href="http://test1.ru/index.php?id=<?php echo $nextvid; ?>" ><img src="/images/right.png" ></a>
                <?php } ?>
            </div>
        
            <div id="videopos">
                <iframe width="560" height="315" src="http://www.youtube.com/embed/<?php echo $matches[0]; ?>" frameborder="0" allowfullscreen></iframe>
            </div>
            
    		
            
            <div id="description">
            	<div id="likebtn" style="position:absolute; z-index:9; top:-45; left:480">
				<?php 
                include("view.php");
                include("rate.php");
                ?>
            	</div>
            	<br><br>
                <span style="font: bold 11pt Arial">Описание:</span><br>
                <span><?php echo $descr; ?></span>
                <?php if (empty($descr))
                { ?>
                    <span>Описание отсутствует.</span>
                <?php } ?>
            </div>
    

    		
    
    
   
    
    
    
    
        </div>
	</div>











<script type="text/javascript">
$('#clickme').click(function() {
$('#info').animate({left: '+=250'}, 300);
});
$('#clickme2').click(function() {
$('#info').animate({left: '-=250'}, 300);
});
</script>





 
 
 
<div id="hide_box" style="width:500px;display: none;">
   <form method="post" action="">
            <span style="position:relative; font: 11pt Arial">Ссылка на видео: </span><br><input style="width:500" class="design5" name="linkd" type="text" value=""><br>
            <span style="position:relative; font: 11pt Arial">Название: </span><br><input style="width:500" class="design5" name="named" type="text" value=""><br>
            <span style="position:relative; font: 11pt Arial">Описание: </span><br><textarea class="design5" style="width: 500px; max-width:500; height: 100px;" name="descriptiond" type="text" value=""></textarea><br><br>
            <input style="margin: 0 0 0 0" class="classname" name="ok" type="submit" value="Добавить">
    </form>
</div>



</body>
</html>