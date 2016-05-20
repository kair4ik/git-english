<?
	ob_start();
	$pagename = "users.php";
	include_once("php_lib.php");

    if (isset($_POST['add_friendship'])){
    	$myID = $_SESSION['VKid'];
    	$friendID = $_POST["uid_pf"];

    	$mysqli = new mysqli($db['host'], $db['username'],$db['password'], $db['name']);
        echo $sql = $mysqli->query("INSERT INTO friends VALUES ('','$myID','','','$friendID')");
        if ($sql) { 
        } else { echo "Произошла ошибка.".$friendID." my id ".$myID;
        }
        $sql = $mysqli->query("INSERT INTO friends VALUES ('','$friendID','','$myID','')");
        if ($sql) { header("Location: $pagename");
        } else { echo "Произошла ошибка.".$friendID;
        }
        $mysqli->close();
        return $sql;
    }

    if (isset($_POST['unsubscribe'])){
    
    	$myID = $_SESSION['VKid'];
    	$friendID = $_POST["uid_uns"];
    	// поиск строки где он у меня в подписках и её обновление
    	$mysqli = new mysqli($db['host'], $db['username'],$db['password'], $db['name']);
        $sql = $mysqli->query("SELECT id FROM friends  WHERE user = {$myID} AND subscribes = {$friendID}");
        if ($sql) { 
	        $result = $sql->fetch_array(MYSQL_ASSOC);
	       	$id = $result['id'];	
        } else { echo "Произошла ошибка.".$friendID." my id1 ".$myID;
        }
        $sql2 = $mysqli->query("UPDATE friends SET user = {$myID},subscribes='' WHERE id = {$id}");
        if ($sql2) { 
        	// header("Location: $pagename");
        } else { echo "Произошла ошибка.".$friendID." my id2 ".$myID;
        }
        // поиск строки где я у него в подписчиках и её обновление
        $sql3 = $mysqli->query("SELECT id FROM friends  WHERE user = {$friendID} AND followers = {$myID}");
        if ($sql3) { 
	        $result = $sql3->fetch_array(MYSQL_ASSOC);
	        $id2 = $result['id'];	
        } else { echo "Произошла ошибка.".$friendID." my id1 ".$myID;
        }
        $sql4 = $mysqli->query("UPDATE friends SET user = {$friendID}, followers ='' WHERE id = {$id2}");
        if ($sql4) { 
        	// header("Location: $pagename");
        } else { echo "Произошла ошибка.".$friendID." my id3 ".$myID;
        }
        $mysqli->close();
    }

    if (isset($_POST['confirm_friendship'])){
    	$myID = $_SESSION['VKid'];
     	"<br>" .$friendID = $_POST["uid_cf"];

     	// поиск строки где он у меня в подписчиках и её обновление
    	$mysqli = new mysqli($db['host'], $db['username'],$db['password'], $db['name']);
        $sql = $mysqli->query("SELECT id FROM friends  WHERE user = {$myID} AND followers = {$friendID}");
        if ($sql) { 
	        $result = $sql->fetch_array(MYSQL_ASSOC);
	       	$id = $result['id'];	
        } else { echo "Произошла ошибка.".$friendID." my id1 ".$myID;
        }
        $sql2 = $mysqli->query("UPDATE friends SET user = {$myID}, friends ={$friendID}, followers ='' WHERE id = {$id}");
        if ($sql2) { 
        	// header("Location: $pagename");
        } else { echo "Произошла ошибка.".$friendID." my id2 ".$myID;
        }
        // поиск строки где я у него в подписках и её обновление
        $sql3 = $mysqli->query("SELECT id FROM friends  WHERE user = {$friendID} AND subscribes = {$myID}");
        if ($sql3) { 
	        $result = $sql3->fetch_array(MYSQL_ASSOC);
	        $id2 = $result['id'];	
        } else { echo "Произошла ошибка.".$friendID." my id1 ".$myID;
        }
        $sql4 = $mysqli->query("UPDATE friends SET user = {$friendID}, friends = {$myID} , subscribes=''  WHERE id = {$id2}");
        if ($sql4) { 
        	// header("Location: $pagename");
        } else { echo "Произошла ошибка.".$friendID." my id3 ".$myID;
        }
        $mysqli->close();
    }
    
    if (isset($_POST['remove_from_friends'])){
    	$myID = $_SESSION['VKid'];
     	"<br>" .$friendID = $_POST["uid_rf"];

     	// поиск строки где он у меня в друзьях
    	$mysqli = new mysqli($db['host'], $db['username'],$db['password'], $db['name']);
        $sql = $mysqli->query("SELECT id FROM friends  WHERE user = {$myID} AND friends = {$friendID}");
        if ($sql) { 
	        $result = $sql->fetch_array(MYSQL_ASSOC);
	       	$id = $result['id'];	
        } else { echo "Произошла ошибка.".$friendID." my id1 ".$myID;
        }
        $sql2 = $mysqli->query("UPDATE friends SET user = {$myID}, friends ='', followers ={$friendID} WHERE id = {$id}");
        if ($sql2) { 
        } else { echo "Произошла ошибка.".$friendID." my id2 ".$myID;
        }
        // поиск строки где я у него в друзьях
        $sql3 = $mysqli->query("SELECT id FROM friends  WHERE user = {$friendID} AND friends = {$myID}");
        if ($sql3) { 
	        $result = $sql3->fetch_array(MYSQL_ASSOC);
	        $id2 = $result['id'];	
        } else { echo "Произошла ошибка.".$friendID." my id1 ".$myID;
        }
        $sql4 = $mysqli->query("UPDATE friends SET user = {$friendID}, friends = '' , subscribes = {$myID}  WHERE id = {$id2}");
        if ($sql4) { 
        } else { echo "Произошла ошибка.".$friendID." my id3 ".$myID;
        }
        $mysqli->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Все юзеры</title>
		<link rel="stylesheet" href="main.css">
</head>
<body>
<a href="index.php">Назад</a>
<?
	$arr_userVK = get_arr_from_arrays(select_db($db,"users"),'uid'); // все пользователи

	$arrfriends = get_arr_from_arrays(select_db($db,"friends","user",$_SESSION['VKid']),'friends'); // друзья
	$arrfollowers = get_arr_from_arrays(select_db($db,"friends","user",$_SESSION['VKid']),'followers'); // подписчики
	$arrsubscribes = get_arr_from_arrays(select_db($db,"friends","user",$_SESSION['VKid']),'subscribes'); // подписки

	$arr_step1 = array_diff($arr_userVK, $arrfriends); // не мои друзья
	$arr_step2 = array_diff($arr_step1, $arrfollowers); // и не мои подписчики
	$arr_my_potential_friend = array_diff($arr_step2, $arrsubscribes); // и не мои подписки


	echo "<div id=\"my_friends\">";
	echo "Мои друзья ВК:";
	foreach ($arr_my_potential_friend as $user) {
		if (!empty($user) && ($user !=$_SESSION['VKid'])){
			$result = select_db($db,"users","uid",$user)[0];
			show_user_min("add",$pagename,$result,$user);
			}
		}
	echo "</div>";

	echo "<div id=\"my_friends\">";
	echo "Мои подписки:";
	foreach ($arrsubscribes as $user){
		if (!empty($user)){
			$result = select_db($db,"users","uid",$user)[0];
			show_user_min("unsub",$pagename,$result,$user);
		}
	}
	echo "</div>";


	echo "<div id=\"my_friends\">";
	echo "Мои друзья:";
	foreach ($arrfriends as $user){
		if (!empty($user)){
			$result = select_db($db,"users","uid",$user)[0];
			show_user_min("delete",$pagename,$result,$user);
		}
	}
	echo "</div>";

	echo "<div id=\"my_friends\">";
	echo "Мои подписчики:";
	foreach ($arrfollowers as $user){
		if (!empty($user)){
			$result = select_db($db,"users","uid",$user)[0];
			show_user_min("confirm",$pagename,$result,$user);
		}
	}
	echo "</div>";

?>
<a href="user.php">Далее</a>

</body>
</html>