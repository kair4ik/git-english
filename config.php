<?
if ($_SERVER["SERVER_NAME"] == 'git-english'){
    $db['host'] = "localhost";  
    $db['username'] = "root";
    $db['password'] = "";
    $db['name'] = "kair";

    $redirect_uri = 'http://git-english/index.php'; // Адрес сайта
}
if ($_SERVER["SERVER_NAME"] == 'englishforall.xyz') {
    $db['host'] = "mongodb0.locum.ru";
    $db['username'] = "kair4ik_kair45";
    $db['password'] = "5Kvjp4E0e";
    $db['name'] = "kair4ik_kair45";

    $redirect_uri = 'http://englishforall.xyz/index.php'; // Адрес сайта
}
if ($_SERVER["SERVER_NAME"] == 'kair.itruba.com.ua') {
    $db['host'] = "localhost";
    $db['username'] = "musie120_kair";
    $db['password'] = "123456Ka";
    $db['name'] = "musie120_kair";

    $redirect_uri = 'http://kair.itruba.com.ua/index.php'; // Адрес сайта
}

?>