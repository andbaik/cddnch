<?php
include_once '../block/connect_db.php';

$error = '';

$id_d = filter_input(INPUT_POST, 'id_d_m', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]);
$id_dcs = filter_input(INPUT_POST, 'id_dcs_m', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]);
$id_dnch = filter_input(INPUT_POST, 'id_dnch_m', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]);
$name_station = trim(filter_input(INPUT_POST, 'name_station', FILTER_SANITIZE_STRING));

if ($id_d <= 0) {
    $error = 'Выберите дирекцию';
} elseif ($id_dcs <= 0) {
    $error = 'Выберите ДЦС';
} elseif ($id_dnch <= 0) {
    $error = 'Выберите ДНЧ';
} elseif (empty($name_station)) {
    $error = 'Введите название станции';
} elseif (mb_strlen($name_station, 'UTF-8') > 255) {
    $error = 'Название станции слишком длинное';
}

if ($error !== '') {
    echo $error;
    exit;
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $stmt = $pdo->prepare('SELECT COUNT(*) AS cnt FROM stations WHERE id_dnch = :id_dnch AND station = :station');
    $stmt->execute([':id_dnch' => $id_dnch, ':station' => $name_station]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)['cnt'] > 0) {
        echo 'Такая станция уже существует';
        exit;
    }

    $insert = $pdo->prepare('INSERT INTO stations (id_dnch, station) VALUES (:id_dnch, :station)');
    $insert->execute([':id_dnch' => $id_dnch, ':station' => $name_station]);

    echo 1;
} catch (PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
}
