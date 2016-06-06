<?
	function bday_to_timeVK($date){
        $birthday = explode(".", $date);
        $birthday = $birthday[2]."-".$birthday[1]."-".$birthday[0];
        return $birthday;
    }

    function get_info_vk($redirect_uri,$flag=true){
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
        if (flag == false){
        setcookie("tokenVK",$token["access_token"],time()+3600);
        setcookie("email",$token["email"],time()+3600);
        setcookie("user_id",$token["user_id"],time()+3600);
        }
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

        // $user[0] = "";
        // $user[1] = $userInfo['first_name']." ".$userInfo['last_name'];
        // $user[2] = $userInfo['uid'];
        // // $user[3] = $userInfo['sex'];
        // $user[4] = $userInfo['photo_big'];
        // $user[5] = bday_to_timeVK($userInfo['bdate']);
        // $user[6] = $userInfo['email'];
        $userInfo['link'] = "http://vk.com/".$userInfo['screen_name'];

        return $userInfo;
    	}
    }

    function get_friends_vk(){

        $token['user_id'] = $_COOKIE["VKid"];
        $token['access_token'] = $_COOKIE["tokenVK"];

        $params = array(
            'uids'         => $token['user_id'],
            'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
            'access_token' => $token['access_token']
        );

        //инфа о юзере
        $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);

        $userInfo = $userInfo['response'][0];

        // друзья
        $params = array(
            'user_id'      => $userInfo['uid'],
            'fields'       => 'sex,bdate,has_mobile,contacts,online',
        );

        $friends = json_decode(file_get_contents("https://api.vk.com/method/friends.get". "?" . urldecode(http_build_query($params))),true);
        $userVK =  $friends['response'];

        for($i=0;$i<count($userVK);$i++){
            $usersID[$i] = $userVK[$i]['uid'];
        }

        return $usersID; 
          
    }

    function get_audio_vk(){

        // $token = get_token_vk(); 
        $token['user_id'] = $_COOKIE["VKid"];
        $token['access_token'] = $_COOKIE["tokenVK"];

        $params = array(
            'user_id'      => $token['user_id'],
            'access_token' => $token['access_token'],
            'fields'       => 'audio_ids'  
        );

        $audio = json_decode(file_get_contents("https://api.vk.com/method/audio.get". "?" . urldecode(http_build_query($params))),true);
        $audioVK =  $audio['response'];

        for($i=0;$i<count($audioVK);$i++){
            $audios_artist[$i] = $audioVK[$i]['artist'];
            $audios_title[$i] = $audioVK[$i]['title'];
            $audios_url[$i] = $audioVK[$i]['url'];
        }

        $audio = array($audios_artist,$audios_title,$audios_url);
        return $audio; 
          
    }

    function show_audio_vk(){
        echo "<div id=\"audio\">";
        $audio = get_audio_vk();
        $audios_artist = $audio[0];
        $audios_title = $audio[1];
        $audios_url = $audio[2];
        for($i=0;$i<count($audios_artist);$i++){
           echo  $audios_artist[$i]." - ".$audios_title[$i]."<br>";
           echo "<audio id=\"player\" src=\"$audios_url[$i]\" controls></audio><br>";
        }
        echo "</div>";

    }

    function show_user($result,$pagename){
    	echo "
            <div id=\"form\" class=\"form-login\">
            <a href=\"user.php?uid=".$result['uid']."\" target=\"_blank\"> <img class=\"avatar\" src=\"".$result['foto']."\" alt=\"".$result['name']."\"> </a>
            Имя:".$result['name']."<br>
            ID:".$result['uid']."<br>
            Пол:".$result['sex']."<br>
            ДР:".$result['birthday']."<br>
            E-mail:".$result['email']."<br>
            <a href=\"".$result['link']."\"> Ссылка на профиль</a>
            <form action=\"".$pagename."\" method = \"post\">
                <input type=\"submit\" name=\"exit\" value=\"Выход\">
            </form>
            </div>
        "; 

    }

    function show_user_min($param="add",$pagename,$result,$user){
        if ($param == "add"){
        echo "
            <div id=\"form\">
            <a href=\"user.php?uid=".$user."\" target=\"_blank\"> <img class=\"avatar\" src=\"".$result['foto']."\" alt=\"".$result['name']."\"> </a>
            <br>".$result['name']."<br> 
        	<form action=\"".$pagename."\" method = \"post\">
            	<input type=\"hidden\" name=\"uid_pf\" value=\"".$user."\">
                <input type=\"submit\" name=\"add_friendship\" value=\"Добавить в друзья\">
            </form>
            </div>";
        }

        if ($param == "unsub"){
        echo "
            <div id=\"form\">
            <a href=\"user.php?uid=".$user."\" target=\"_blank\"> <img class=\"avatar\" src=\"".$result['foto']."\" alt=\"".$result['name']."\"> </a>
            <br>".$result['name']."<br> 
        	<form action=\"".$pagename."\" method = \"post\">
            	<input type=\"hidden\" name=\"uid_uns\" value=\"".$user."\">
                <input type=\"submit\" name=\"unsubscribe\"  value=\"Отписаться\">
            </form>
            </div>";
        }
      	
      	if ($param == "delete"){
        echo "
            <div id=\"form\">
            <a href=\"user.php?uid=".$user."\" target=\"_blank\"> <img class=\"avatar\" src=\"".$result['foto']."\" alt=\"".$result['name']."\"> </a>
            <br>".$result['name']."<br> 
        	<form action=\"".$pagename."\" method = \"post\">
            	<input type=\"hidden\" name=\"uid_rf\" value=\"".$user."\">
                <input type=\"submit\" name=\"remove_from_friends\" value=\"Удалить из друзей\">
            </form>
            </div>";
        }

        if ($param == "confirm"){
        echo "
            <div id=\"form\">
            <a href=\"user.php?uid=".$user."\" target=\"_blank\"><img class=\"avatar\" src=\"".$result['foto']."\" alt=\"".$result['name']."\"> </a>
            <br> ".$result['name']."<br> 
        	<form action=\"".$pagename."\" method = \"post\">
            	<input type=\"hidden\" name=\"uid_cf\" value=\"".$user."\">
                <input type=\"submit\" name=\"confirm_friendship\" value=\"Принять дружбу\">
            </form>
            </div>";
        }

    }


?>