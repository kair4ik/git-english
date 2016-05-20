<?
ob_start();
include_once("php_lib.php");
$pagename = "index.php";
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Главная</title>
    <link rel="stylesheet" href="main.css">
    <script type="text/javascript" src="http://scriptjava.net/source/scriptjava/scriptjava.js"></script>
	
</head>
<body>
<?

    if ($_SESSION['VKid'] == ""){

        $user = get_info_vk($redirect_uri);
        // print_arr($user,"Юзер");
        $result = select_db($db,"users","uid",$user[2]);

        if (empty($result)) {
            if (!empty($user[2])){
                //добавление юзера в таблицу юзерс
                add_db_users($db, $user);
                //добавление юзера в таблицу друзей, (для соц сети)
                $mysqli = new mysqli($db['host'], $db['username'],$db['password'], $db['name']);
                $sql = $mysqli->query("INSERT INTO friends VALUES ('',{$user[2]},'','','')");
                if ($sql) { } else { echo "Произошла ошибка.";}
                $mysqli->close();
                $_SESSION['VKid'] = "$user[2]";
            }

        } else if (!empty($result)){

            $mysqli = new mysqli($db['host'], $db['username'],$db['password'], $db['name']);
            $sql = $mysqli->query("UPDATE users SET foto = '$user[4]' , email = '$user[6]' , link = '$user[7]'  WHERE uid = '$user[2]'");
            if ($sql) { 
            } else { 
                echo "Произошла ошибка.";
                echo "UPDATE users SET foto = '".$user[4]."' , email = '".$user[6]."' , link = '".$user[7]."'  WHERE uid = '".$user[2]."'";
            }
            $mysqli->close();
            $_SESSION['VKid'] = "$user[2]";
        }

    }
    if ($_SESSION['VKid'] != ""){
        show_audio_vk();

        $result = select_db($db,"users","uid",$_SESSION['VKid'])[0];

        show_user($result,$pagename); 
    }
    if (isset($_POST["exit"])){
        unset($_SESSION['VKid']);
        setcookie("tokenVK",$userVK["access_token"],time()-1);
        header("Location:$pagename");
    }

?>




<a href="users.php"> Далее</a>
</body>
</html>
