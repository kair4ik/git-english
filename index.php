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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	
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

    // База слов
    // foreach($words as $word) {
    //   $i++;
    //   echo "<form action=\"".$pagename."\" method = \"post\">";
    //   echo "<input type=\"text\"  name=\"word\" value=\"{$word['word']}\">";
    //   echo "<input type=\"text\"  name=\"translate\" value=\"{$word['translate']}\">";
    //   echo "<input type=\"submit\" name=\"add\" value=\"Добавить\">";
    //   echo "</form>";
    // }
    // echo "База слов. Всего: ".$i."<br>";
    

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


        // echo "Слова для изучения. Всего: ".count($user_db['words'])."<br>";
        // foreach($user_db['words'] as $word => $translate) {
        //     echo "<form action=\"".$pagename."\" method = \"post\">";
        //     echo "<input type=\"text\"  name=\"word\" value=\"$word\">";
        //     echo "<input type=\"text\"  name=\"translate\" value=\"$translate\">";
        //     echo "<input type=\"submit\" name=\"add_stage1\" value=\"On stage1\">";
        //     echo "</form>";
        // }

        echo "<br>";
            // echo "Всего слов: ".count($user_db['words'])."<br>";



        $stage1 = $user_db['stage1'];

        if (count($stage1) >=1){
            print_arr($stage1,"Cтадия 1");
            echo "Всего слов: ".count($stage1)."<br>";

            $word_index = $_COOKIE["stage1_index"];
            $a_keys = array_keys($stage1);
            echo $a_keys[$word_index]." - ".$stage1[$a_keys[$word_index]];  
            echo "<form action=\"".$pagename."\" method = \"post\">
                <input type=\"submit\" name=\"from_stage1_to_words\" value=\"On words\">
                <input type=\"text\"  name=\"word\" value=\"{$a_keys[$word_index]}\">
                <input type=\"text\"  name=\"translate\" value=\"{$stage1[$a_keys[$word_index]]}\">
                <input type=\"submit\" name=\"add_stage2\" value=\"On stage2\">
                <input type=\"submit\" name=\"minus1\" value=\"<=\">
                <input type=\"submit\" name=\"plus1\" value=\"=>\">
                </form>";
        } else if (count($stage1) == 0){
            echo "В этой тренировке нет слов, добавьте новые слова для тренировок =)<br><br>";
        }
        echo "<br>";


        $stage2= $user_db['stage2'];

        if (count($stage2) >=1){
            print_arr($stage2,"Cтадия 2");

            $word_index = $_COOKIE["stage2_index"];
            $a_keys = array_keys($stage2);
            // echo $a_keys[$word_index]." - ".$stage2[$a_keys[$word_index]];

            echo "<form action=\"".$pagename."\" method = \"post\">
                <input type=\"submit\" name=\"from_stage2_to_stage1\" value=\"On stage1\">
                <input id=\"word\" type=\"text\"  name=\"word\" value=\"{$a_keys[$word_index]}\">
                <input id=\"translate\" type=\"hidden\"  name=\"translate\" value=\"{$stage2[$a_keys[$word_index]]}\">
                <input type=\"submit\" name=\"add_stage3\" value=\"On stage3\">
                <input type=\"submit\" name=\"minus2\" value=\"<=\">
                <input type=\"submit\" name=\"plus2\" value=\"=>\">
                </form>";

            // $stage2_new = $stage2;
            // shuffle($stage2);
            foreach ($stage2 as $word => $translate){
                // echo "<input type=\"button\" class=\"button2_stage2\" name=\"$word\" value=\"$translate\">";
                echo "<input type=\"hidden\" class=\"button2_stage2\" name=\"$word\" value=\"$translate\">";
                // echo "<input type=\"button\" class=\"button_stage2\" name=\"$translate\" value=\"$word\">";
            }
            // echo "<br>";
            shuffle($stage2);
            foreach ($stage2 as $word => $translate){
                echo "<input type=\"button\" class=\"button_stage2\" name=\"translate\" value=\"$translate\">";
                // echo "<input type=\"button\" class=\"button_stage2\" name=\"word\" value=\"$word\"><br>";
            }

        } else if (count($stage2) == 0){
            echo "В этой тренировке нет слов, добавьте новые слова для тренировок =)<br><br>";
        }

        echo "<br>";
        echo "<br>";
        echo "<form action=\"".$pagename."\" method = \"post\">
            <input type=\"submit\" name=\"exit\" value=\"Выход\">
            </form>";
        }
       
        //    setcookie($i,$new_words[$i],time()+300);
    }


    echo "<br>";

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

<script>
$(".button_stage2").click(function(){
    var arr_words = [];
    var arr_translate = [];
    var person = {};
    var word = $(this).attr("value");
    var our_word = $("#word").attr("value");

    var amout_img = document.getElementsByClassName("button2_stage2").length;

    for(var i=0;i<amout_img;i++){
        arr_words[arr_words.length] = document.getElementsByClassName("button2_stage2")[i].getAttribute("name");
        arr_translate[arr_translate.length] = document.getElementsByClassName("button2_stage2")[i].getAttribute("value");
        person[arr_translate[i]] = arr_words[i]; 
        if (arr_translate[i] == word){
            $(this).attr("value",person[arr_translate[i]]);
            $(this).attr("name",arr_translate[i]);
        }
    }
    var word2 = $(this).attr("value");
    if (word2 == our_word){
        $(this).css({"background":"green"});
    }else {
        $(this).css({"background":"orange"});
    }
});

    
</script>
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

        // $_id = $_POST["_id"];
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

    if (isset($_POST["add_stage1"])){
        $word = $_POST["word"];
        $translate = $_POST["translate"];

        $user_db['stage1'][$word] = $translate;

        $con = new MongoClient('localhost');
        $db = $con->kair;
        $users = $db->users;
        $users->update(
            array('uid'     => new MongoInt32($_SESSION['VKid'])),
            $user_db
        );
        header("Location:$pagename");
    }

    if (isset($_POST["add_stage2"])){
        $word = $_POST["word"];
        $translate = $_POST["translate"];

        unset($user_db['stage1'][$word]);
        $user_db['stage2'][$word] = $translate;

        $con = new MongoClient('localhost');
        $db = $con->kair;
        $users = $db->users;
        $users->update(
            array('uid'     => new MongoInt32($_SESSION['VKid'])),
            $user_db
        );
        header("Location:$pagename");
    }

    if (isset($_POST["from_stage1_to_words"])){
        $word = $_POST["word"];
        $translate = $_POST["translate"];

        unset($user_db['stage1'][$word]);

        $con = new MongoClient('localhost');
        $db = $con->kair;
        $users = $db->users;
        $users->update(
            array('uid'     => new MongoInt32($_SESSION['VKid'])),
            $user_db
        );
        header("Location:$pagename");
    }
    if (isset($_POST["from_stage2_to_stage1"])){
        $word = $_POST["word"];
        $translate = $_POST["translate"];

        unset($user_db['stage2'][$word]);
        $user_db['stage1'][$word] = $translate;

        $con = new MongoClient('localhost');
        $db = $con->kair;
        $users = $db->users;
        $users->update(
            array('uid'     => new MongoInt32($_SESSION['VKid'])),
            $user_db
        );
        header("Location:$pagename");
    }

    // две кнопки, кука и массив, проходит по массиву от начала до конца, не выходя за его пределы
    function back_next($plus,$minus,$cookie,$arr){
        if (isset($_POST[$plus])){
            if ($_COOKIE[$cookie] >= 0 && $_COOKIE[$cookie] <= count($arr)-2){
                $i = $_COOKIE[$cookie] + 1;
                setcookie($cookie,$i);
            }
            header("Location:$pagename");
        }
        if (isset($_POST[$minus])){
            if ($_COOKIE[$cookie] >= 1 && $_COOKIE[$cookie] <= count($arr)){
                $i = $_COOKIE[$cookie] - 1;
                setcookie($cookie,$i);
            }
            header("Location:$pagename");
        }
    }

    back_next("plus1","minus1","stage1_index",$stage1);
    back_next("plus2","minus2","stage2_index",$stage2);


?>



