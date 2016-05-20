<?
header('Content-Type: text/html; charset=utf-8');

function my_curl($url, $connect_timeout=10, $timeout=120)
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

if ($_SERVER["SERVER_NAME"] == 'git-english'){
     $redirect_uri = 'http://git-english/test.php'; // Адрес сайта
}
if ($_SERVER["SERVER_NAME"] == 'englishforall.xyz') {

    $redirect_uri = 'http://englishforall.xyz/test.php'; // Адрес сайта
}
if ($_SERVER["SERVER_NAME"] == 'kair.itruba.com.ua') {

    $redirect_uri = 'http://kair.itruba.com.ua/test.php'; // Адрес сайта
}

        $client_id = '5373836'; // ID приложения
        $client_secret = 'QMmTTWSImHZwoCPEKIlE'; // Защищённый ключ
                
        $params = array(
            'client_id'     => $client_id,
            'redirect_uri'  => $redirect_uri,
            'page' => 'popup',
            'response_type' => 'code',
            'scope'         => 'offline,email',
            'v'  => '5.52'
        );
        // friends,photos,audio,video,docs,notes,pages,status,offers,questions,wall,groups,email,notifications,stats,market
       echo  "ссылка авторизации: ".$link = "http://oauth.vk.com/authorize?" . urldecode(http_build_query($params));

        echo "<br><a href=\"".$link."\">Авторизоваться</a>";

        $params = array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'code' => $_GET['code'],
            'redirect_uri' => $redirect_uri
        );
        echo "<br>юрл1: ".$url1 = "https://oauth.vk.com/access_token?".urldecode(http_build_query($params));
        echo "<br>токен: ".$token = my_curl($url1);
        $token = json_decode($token, true);
        echo "<br>ответ: ";
        print_r($token);
                
                
        $params = array(
            'uids'         => $token['user_id'],
            'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
            'access_token' => $token['access_token']
        );
        echo "<br>УРЛ2: ".$url2 = "https://api.vk.com/method/users.get?".urldecode(http_build_query($params));
        //инфа о юзере
        $userInfo1 = my_curl($url2);
        $userInfo = json_decode($userInfo1, true);

        echo "<pre>";
        print_r($userInfo);
        echo "</pre>";
       

?>