<?php
include_once '../block/connect_db.php';

$error = '';

$id_user = trim(filter_var($_POST['id_user'], FILTER_SANITIZE_SPECIAL_CHARS));
$id_d = trim(filter_var($_POST['id_d_m4'], FILTER_SANITIZE_SPECIAL_CHARS));
$id_dcs = trim(filter_var($_POST['id_dcs_m4'], FILTER_SANITIZE_SPECIAL_CHARS));
$id_dnch = trim(filter_var($_POST['id_dnch_m4'], FILTER_SANITIZE_SPECIAL_CHARS));
$surname = trim(filter_var($_POST['surname'], FILTER_SANITIZE_SPECIAL_CHARS));
$name = trim(filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS));
$midl_name = trim(filter_var($_POST['midl_name'], FILTER_SANITIZE_SPECIAL_CHARS));
$login = trim(filter_var($_POST['login'], FILTER_SANITIZE_SPECIAL_CHARS));
$password = trim(filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS));
$email = trim(filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS));
$level = trim(filter_var($_POST['level'], FILTER_SANITIZE_SPECIAL_CHARS));
$active = isset($_POST['checkbox_active']) ? 1 : 0;

if (empty($id_user)) {
    $error = "ID пользователя не указан";
} elseif (empty($id_d) || $id_d == 0) {
    $error = "Выберите дирекцию";
} elseif (empty($id_dcs) || $id_dcs == 0) {
    $error = "Выберите ДЦС";
} elseif (empty($id_dnch) || $id_dnch == 0) {
    $error = "Выберите ДНЧ";
} elseif (empty($surname)) {
    $error = "Введите фамилию";
} elseif (empty($name)) {
    $error = "Введите имя";
} elseif (empty($login)) {
    $error = "Введите логин";
} elseif (empty($password)) {
    $error = "Введите пароль";
} elseif (empty($level) || $level == 0) {
    $error = "Выберите уровень доступа";
}

if ($error != '') {
    echo $error;
    exit();
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $query = $pdo->prepare("UPDATE user_control SET id_d=?, id_dcs=?, id_dnch=?, surname=?, name=?, midl_name=?, login=?, password=?, email=?, level=?, status=? WHERE id_user=?");
    $query->execute([$id_d, $id_dcs, $id_dnch, $surname, $name, $midl_name, $login, $password, $email, $level, $active, $id_user]);

    echo 1;
} catch (Exception $e) {
    echo 'Ошибка: ' . $e->getMessage();
}
?>