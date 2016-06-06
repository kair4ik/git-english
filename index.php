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
<form action="<?=$pagename?>" method="post">
    <input type="text" name="input_word" placeholder="Слово">
    <input type="text" name="input_translate" placeholder="Перевод" >
    <input type="submit" name="new_word" value="Записать новое слово">
</form>
<?
// print_arr($_SESSION);
    // $i=0;
    $con = new MongoClient('localhost');
    $db = $con->kair;
    $words = $db->words_base;
    $words = $words->find();
    
    foreach($words as $word) {
      // echo $word['word']." - ".$word['translate']."<br>";
      $i++;
      echo "<form action=\"".$pagename."\" method = \"post\">";
      echo "<input type=\"text\" size=1 name=\"word\" value=\"{$word['word']}\">";
      echo "<input type=\"text\" size=5 name=\"translate\" value=\"{$word['translate']}\">";
      // echo "<input type=\"text\"  name=\"_id\" value=\"{$word['_id']}\">";
      echo "<input type=\"submit\" name=\"add\" value=\"Добавить\">";
      echo "</form>";
      // echo "<br>";
    }
    
    echo "<br>Всего слов: ".$i."<br>";

    $con->close();

    if ($_SESSION['VKid'] == ""){

        $user = get_info_vk($redirect_uri);
        // print_arr($user,"Юзер");

        $con = new MongoClient('localhost');
        $db = $con->kair;
        $users = $db->users;
        $user_db = $users->findOne(array('uid' => new MongoInt32($user['uid'])));
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

        $con = new MongoClient('localhost');
        $db = $con->kair;
        $users = $db->users;
        // $user = $users->find();
        $user_db = $users->findOne(array('uid' => new MongoInt32($_SESSION['VKid'])));
        if (!empty($user_db)){
            print_arr($user_db,$user_db['first_name']);
            echo "
                 <form action=\"".$pagename."\" method = \"post\">
                <input type=\"submit\" name=\"exit\" value=\"Выход\">
            </form>
             ";
        }
       

        // $words = get_arr_from_arrays(select_db($db,"words"),'word');
        // $translate = get_arr_from_arrays(select_db($db,"words"),'translate');

        // print_arr($words,"Слово");
        // print_arr($translate,"Перевод");

        // $new_word = array($words,$translate);
        // print_arr($new_word,"Новый");

        // $new_words = $new_word[0];
        // $new_translates = $new_word[1];
        // for($i=0;$i<count($new_words);$i++){
        //    // echo  $i.".".$new_words[$i]." - ".$new_translates[$i]."<br>";
        //     if (empty($_COOKIE[$i])){
        //    setcookie($i,$new_words[$i],time()+300);
        //    setcookie($new_words[$i],$new_translates[$i],time()+300);
        //    setcookie($new_translates[$i],$new_words[$i],time()+300);
        //     }
        // }

        // print_arr($_COOKIE,"Куки");

        // for ($i=1;$i<=21;$i++){
        // $i = rand(1, 21);
        //     echo $i." => ".$_COOKIE[$i]." => ".$_COOKIE[$_COOKIE[$i]]."<br>";
        // }
        // echo "Радном: ".
    }


    echo "<br>";


    // запись
    // $post = array(
    //      'word'     => 'one',
    //      'translate'   => 'один'
    //   );
    // $posts->insert($post);

    // $id = '57556ccb55e3f2681a00002b';

    // $con = new MongoClient('localhost');
    // $db = $con->kair;
    // $words1 = $db->words_base;
    // $words2 = $words1->findOne(array('_id' => new MongoId($id)));
    // $words2['word'] = "mouse";
    // $words2['translate'] = "мышь";
    // print_arr($words2);

    // $words3 = $words2;
    // // обновление
    // $words1->update(
    //      array('_id'     => new MongoId($id)),
    //      $words2
    // );
    // // update(
    //     // array("title"=>"MongoDB"), 
    //   // array('$set'=>array("title"=>"MongoDB Tutorial")));
    // $con->close();

    

?>

<a href="users.php"> Далее</a>
</body>
</html>
<?
    if (isset($_POST["exit"])){
        unset($_SESSION['VKid']);
        setcookie("tokenVK",$userVK["access_token"],time()-1);
        header("Location:$pagename");
    }

    if (isset($_POST["new_word"])){
        $word = $_POST["input_word"];
        $translate = $_POST["input_translate"];

        $con = new MongoClient('localhost');
        $db = $con->kair;
        $words = $db->words_base;
        $new_word = array('word' => $word,'translate' => $translate);
        $words->insert($new_word);

        header("Location:test.php");
    }

    if (isset($_POST["add"])){
        $word = $_POST["word"];
        $translate = $_POST["translate"];

        $_id = $_POST["_id"];
        $user_db['words'][$word] = $translate;

        $con = new MongoClient('localhost');
        $db = $con->kair;
        $users = $db->users;
        $users->update(
            array('uid'     => new MongoInt32($_SESSION['VKid'])),
            $user_db
        );
        header("Location:$pagename");
    }


?>



