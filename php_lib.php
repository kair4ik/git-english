<?php
        
if ($_SERVER["SERVER_NAME"] == 'kair.project'){
    $db['host'] = "localhost";  
    $db['username'] = "root";
    $db['password'] = "";
    $db['name'] = "kair";

    $redirect_uri = 'http://kair.project/index.php'; // Адрес сайта
}
if ($_SERVER["SERVER_NAME"] == 'englishforall.xyz') {
    $db['host'] = "mysql.hostinger.ru";
    $db['username'] = "u697075938_12345";
    $db['password'] = "123456";
    $db['name'] = "u697075938_12345";

    $redirect_uri = 'http://englishforall.xyz/index.php'; // Адрес сайта
}

include_once("lib_vk.php");

session_start();    


function my_curl($url, $connect_timeout=1, $timeout=1)
{
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, $connect_timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function my_big_curl($url,$params){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);
    curl_close($curl);
    $tokenInfo = json_decode($result, true);
    return $tokenInfo;
}

    function add_db_users($db,$arr){
        $mysqli = new mysqli($db['host'], $db['username'],$db['password'], $db['name']);
        $sql = $mysqli->query("INSERT INTO users VALUES ('".$arr[0]."','".$arr[1]."','".$arr[2]."','".$arr[3]."','".$arr[4]."','".$arr[5]."','".$arr[6]."','".$arr[7]."')");
        if ($sql) {
            header("Location: $pagename");
        } else {
            echo "Произошла ошибка.";
        }
        $mysqli->close();
        return $sql;
    }

    function select_db($db,$table,$option1="",$option2=""){
        $mysqli = new mysqli($db['host'], $db['username'],$db['password'], $db['name']);
        if ($option1==""){
            $query = $mysqli->query("SELECT * FROM $table");
        }
        if ($option1!=""){
            $query = $mysqli->query("SELECT * FROM $table WHERE {$option1} = {$option2}");
        }
        while (@$result = $query->fetch_array(MYSQL_ASSOC)){
            $row[] = $result;
        }
        $mysqli->close();
        return $row;
    }
    function get_arr_from_arrays($array1, $param){
        for($i=0;$i<count($array1);$i++){
            $finish_arr[] = $array1[$i][$param]; 
        }
        return $finish_arr; 
    }

    function get_friends_game($db){
         $users = select_db($db,"users");
            for($i=0;$i<count($users);$i++){
                $usersID[$i] = $users[$i]['uid'];
            }
        return $usersID;     
        }


    function getIP(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function getBrowser(){
        $user_agent = $_SERVER["HTTP_USER_AGENT"];
        if (strpos($user_agent, "Firefox") !== false) $browser = "Firefox";
        elseif (strpos($user_agent, "Opera") !== false) $browser = "Opera";
        elseif (strpos($user_agent, "Chrome") !== false) $browser = "Chrome";
        elseif (strpos($user_agent, "MSIE") !== false) $browser = "Internet Explorer";
        elseif (strpos($user_agent, "Safari") !== false) $browser = "Safari";
        else $browser = "Неизвестный";
        return $browser;   
    }

    function intersect_of_array($array1, $array2){

        return array_intersect($array1, $array2); 

    }


    function get_info_fb(){
        $client_id = '209899126039499'; // Client ID
        $client_secret = 'bfd027e21f459a4c125e90e92a12fb51'; // Client secret
        $redirect_uri = 'http://kair.project/index.php'; // Redirect URIs

        $url = 'https://www.facebook.com/dialog/oauth';
        $params = array(
            'client_id'     => $client_id,
            'redirect_uri'  => $redirect_uri,
            'response_type' => 'code',
            'scope'         => 'public_profile,email,user_birthday, user_friends'
        );

        $link =  $url . '?' . urldecode(http_build_query($params));

        echo "<a href=\"".$link."\"><img src=\"fb.png\"></a>";

        if (isset($_GET['code'])) {
            $result = false;

            $params = array(
                'client_id'     => $client_id,
                'redirect_uri'  => $redirect_uri,
                'client_secret' => $client_secret,
                'code'          => $_GET['code']
            );

            $url = 'https://graph.facebook.com/oauth/access_token';

            $tokenInfo = null;
            parse_str(@file_get_contents($url . '?' . http_build_query($params)), $tokenInfo);
           
            if (count($tokenInfo) > 0 && isset($tokenInfo['access_token'])) {

                $params = array(
                    'fields'       => 'id,name,email,birthday,first_name,last_name,age_range,link,gender,locale,picture,timezone,updated_time,verified',
                    'access_token' => $tokenInfo['access_token']);

                $userInfo = json_decode(file_get_contents('https://graph.facebook.com/v2.6/me' . '?' . urldecode(http_build_query($params))),true);

                $params = array(
                    // 'fields'       => 'uid',
                    'options' => 'uid',
                    'access_token' => $tokenInfo['access_token']);

                $userFriends = json_decode(file_get_contents("https://graph.facebook.com/v2.5/me/friends" . '?' . urldecode(http_build_query($params))),true);

                $user[0] = "";
                $user[1] = $userInfo['name'];
                $user[2] = $userInfo['id'];
                $user[3] = $userInfo['gender'];
                $user[4] = $userInfo['picture']['data']['url'];
                $user[5] = bday_to_timeFB($userInfo['birthday']);
                $user[6] = $userInfo['email'];
                $user[7] = $userInfo['link'];

                return $user;
                // return $userFriends;

            }
        }
    }

   

    function bday_to_timeFB($date){
        $birthday = explode("/", $date);
        $birthday = $birthday[2]."-".$birthday[0]."-".$birthday[1];
        return $birthday;
    }

    function print_arr($arr,$arr_name = "Массив"){
        echo $arr_name."<pre>";
        print_r($arr);
        echo "</pre>";
    }

?>