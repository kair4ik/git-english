<?
ob_start();
include_once("php_lib.php");
$pagename = "index.php";

?>
<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Главная</title>
    <link rel="stylesheet" href="main.css">
    <script type="text/javascript" src="http://scriptjava.net/source/scriptjava/scriptjava.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	
</head>
<body>
<form action="<?=$pagename?>" method="post">
    <input type="text" name="input_word" placeholder="Слово">
    <input type="text" name="input_translate" placeholder="Перевод" >
    <input type="submit" name="new_word" value="Записать новое слово">
</form>
<?

    if ($_SESSION['VKid'] == ""){

        $user = get_info_vk($redirect_uri);
        // print_arr($user,"Юзер");

        $con = new MongoClient($db['host'],$db['username'],$db['password']);
        $db = $con->$db['name'];
        $users = $db->users;
        $user_db = $users->findOne(array('uid' => new MongoInt64($user['uid'])));
        if (empty($user_db)){
            if (count($user)>5){
                    $users->insert($user);
                    $_SESSION['VKid'] = $user['uid'];
                }
        }
        if (!empty($user_db)){
                $_SESSION['VKid'] = $user['uid'];
        }
        $con->close();
    }
    if ($_SESSION['VKid'] != ""){
        // show_audio_vk();

        $con = new MongoClient($db['host'],$db['username'],$db['password']);
        $db = $con->$db['name'];
        $users = $db->users;
        // $user = $users->find();
        $user_db = $users->findOne(array('uid' => new MongoInt64($_SESSION['VKid'])));
        if (!empty($user_db)){

        $stage1 = $user_db['stage1'];

        echo "<form action=\"".$pagename."\" method = \"post\">
            <input type=\"submit\" name=\"exit\" value=\"Выход\">
            </form>";
        }
    }


?>

</body>
</html>
<?
    if (isset($_POST["exit"])){
        unset($_SESSION['VKid']);
        setcookie("tokenVK",$userVK["access_token"],time()-1);
        header("Location:$pagename");
    }

?>



