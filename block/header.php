<?php
//Запускаем сессию
session_start();

include_once('block/setting.php');
//Устанавливаем кодировку и вывод всех ошибок
header('Content-Type: text/html; charset=UTF8');
error_reporting(E_ALL);

//Включаем буферизацию содержимого
ob_start();


//Определяем переменную для переключателя
$user = isset($_SESSION['user']) ? $_SESSION['user'] : false;
$id_user = isset($user['id_user']) ? $user['id_user'] : false;
$level = isset($user['level']) ? $user['level'] : false;
$id_dcs = isset($user['id_dcs']) ? $user['id_dcs'] : false;
$id_d = isset($user['id_d']) ? $user['id_d'] : false;



if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_user = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_user = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
    $ip_user = $_SERVER['HTTP_X_REAL_IP'];
} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
    $ip_user = $_SERVER['REMOTE_ADDR'];
}

$_SESSION['ip_user'] = $ip_user;



if ($error_print == true) {
    echo "IP_user = $ip_user ID_user = $id_user LEVEL = $level D = $id_d DCS = $id_dcs";
}
?>

<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/preload.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="css/awesome/css/all.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/css_checkbox.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="DnchCD" />
    <link rel="manifest" href="/site.webmanifest" />


    <title><?= $title ?></title>
</head>
