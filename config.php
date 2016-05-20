<?
if ($_SERVER["SERVER_NAME"] == 'git-english'){
    $db['host'] = "localhost";  
    $db['username'] = "root";
    $db['password'] = "";
    $db['name'] = "kair";

    $redirect_uri = 'http://git-english/index.php'; // Адрес сайта
}
if ($_SERVER["SERVER_NAME"] == 'englishforall.xyz') {
    $db['host'] = "mysql.hostinger.ru";
    $db['username'] = "u697075938_12345";
    $db['password'] = "123456";
    $db['name'] = "u697075938_12345";

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