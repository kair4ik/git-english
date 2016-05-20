<?

    function get_info_vk($redirect_uri){
    	$client_id = '5373836'; // ID приложения
        $client_secret = 'QMmTTWSImHZwoCPEKIlE'; // Защищённый ключ
    	$url = 'http://oauth.vk.com/authorize';
        $params = array(
            'client_id'     => $client_id,
            'redirect_uri'  => $redirect_uri,
            'response_type' => 'code',
            'scope'         => 'wall,offline,email,audio' 
        );
        // friends,photos,audio,video,docs,notes,pages,status,offers,questions,wall,groups,email,notifications,stats,market
        $link =  $url . '?' . urldecode(http_build_query($params));

        echo "<a href=\"".$link."\"><img src=\"vk.png\"></a>";

        if (isset($_GET['code'])) {
        $result = false; 
        $params = array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'code' => $_GET['code'],
            'redirect_uri' => $redirect_uri
        );
        $url1 = "https://oauth.vk.com/access_token?".urldecode(http_build_query($params));

        // $token = json_decode(my_curl($url1), true);
        $token = my_big_curl($url1,$params);

        setcookie("tokenVK",$token["access_token"],time()+3600);
        setcookie("email",$token["email"],time()+3600);
        setcookie("user_id",$token["user_id"],time()+3600);

        $params = array(
            'uids'         => $token['user_id'],
            'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
            'access_token' => $token['access_token']
        );
        $url2 = "https://api.vk.com/method/users.get?".urldecode(http_build_query($params));
        //инфа о юзере
        $userInfo = my_big_curl($url2,$params);
        $userInfo = $userInfo['response'][0];
        $userInfo['email'] = $token['email'];

        $user[0] = "";
        $user[1] = $userInfo['first_name']." ".$userInfo['last_name'];
        $user[2] = $userInfo['uid'];
        $user[3] = $userInfo['sex'];
        $user[4] = $userInfo['photo_big'];
        $user[5] = bday_to_timeVK($userInfo['bdate']);
        $user[6] = $userInfo['email'];
        $user[7] = "http://vk.com/".$userInfo['screen_name'];

        return $user;
    	}
    }

    $redirect_uri = 'http://englishforall.xyz/index.php'; 
    $user = get_info_vk($redirect_uri);
    print_arr($user,"Юзер");

?>