<?php
include_once '../block/connect_db.php';

$error = '';

$id_d = filter_input(INPUT_POST, 'id_d_m2', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]);
$id_dcs = filter_input(INPUT_POST, 'id_dcs_m2', FILTER_VALIDATE_INT, ['options' => ['default' => 0]]);
$dnch_name = trim(filter_input(INPUT_POST, 'dnch_name', FILTER_SANITIZE_STRING));
$dnch_name_shot = trim(filter_input(INPUT_POST, 'dnch_name_shot', FILTER_SANITIZE_STRING));

if ($id_d <= 0) {
    $error = 'Выберите дирекцию';
} elseif ($id_dcs <= 0) {
    $error = 'Выберите ДЦС';
} elseif (empty($dnch_name)) {
    $error = 'Введите наименование ДНЧ';
} elseif (empty($dnch_name_shot)) {
    $error = 'Введите номер ДНЧ';
} elseif (mb_strlen($dnch_name, 'UTF-8') > 128 || mb_strlen($dnch_name_shot, 'UTF-8') > 64) {
    $error = 'Слишком длинные данные';
}

if ($error !== '') {
    echo $error;
    exit;
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $stmt = $pdo->prepare('SELECT COUNT(*) AS cnt FROM dnch WHERE id_d = :id_d AND id_dcs = :id_dcs AND dnch_name = :dnch_name');
    $stmt->execute([':id_d' => $id_d, ':id_dcs' => $id_dcs, ':dnch_name' => $dnch_name]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)['cnt'] > 0) {
        echo 'Такой ДНЧ уже существует';
        exit;
    }

    $insert = $pdo->prepare('INSERT INTO dnch (id_d, id_dcs, dnch_name, dnch_name_shot) VALUES (:id_d, :id_dcs, :dnch_name, :dnch_name_shot)');
    $insert->execute([':id_d' => $id_d, ':id_dcs' => $id_dcs, ':dnch_name' => $dnch_name, ':dnch_name_shot' => $dnch_name_shot]);

    echo 1;
} catch (PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
}
