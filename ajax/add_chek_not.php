<?php
include_once '../block/connect_db.php';
$error = '';

$not_check = trim(filter_var($_POST['not_check'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
$val_dnch  = trim(filter_var($_POST['val_dnch']  ?? '', FILTER_SANITIZE_NUMBER_INT));
$val_control  = trim(filter_var($_POST['val_control']  ?? '', FILTER_SANITIZE_NUMBER_INT));
$val_day   = trim(filter_var($_POST['val_day']   ?? '', FILTER_SANITIZE_SPECIAL_CHARS));


if ($not_check === '') {
    $error = "Введите информацию об отсутствии";
}

if ($val_dnch === '' || !is_numeric($val_dnch)) {
    $error = "Неверный id DNCH";
}

if ($val_control === '' || !is_numeric($val_control)) {
    $error = "Неверный номер недели";
}

if ($error !== '') {
    echo $error;
    exit();
}

switch ($val_day) {
    case '1_obj_mon':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_mon_1`=0, `coment_dcsrb_mon_1`=? WHERE `id_control` = ?');
        $query->execute([$not_check, (int)$val_control]);
        break;
    case '2_obj_mon':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_mon_2`=0, `coment_dcsrb_mon_2`=? WHERE `id_control` = ?');
        $query->execute([$not_check, (int)$val_control]);
        break;
    case '1_obj_tue':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_tue_1`=0, `coment_dcsrb_tue_1`=? WHERE `id_control` = ?');
        $query->execute([$not_check, (int)$val_control]);
        break;
    case '2_obj_tue':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_tue_2`=0, `coment_dcsrb_tue_2`=? WHERE `id_control` = ?');
        $query->execute([$not_check, (int)$val_control]);
        break;
    case '1_obj_wed':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_wed_1`=0, `coment_dcsrb_wed_1`=? WHERE `id_control` = ?');
        $query->execute([$not_check, (int)$val_control]);
        break;
    case '2_obj_wed':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_wed_2`=0, `coment_dcsrb_wed_2`=? WHERE `id_control` = ?');
        $query->execute([$not_check, (int)$val_control]);
        break;
    case '1_obj_thu':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_thu_1`=0, `coment_dcsrb_thu_1`=? WHERE `id_control` = ?');
        $query->execute([$not_check, (int)$val_control]);
        break;
    case '2_obj_thu':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_thu_2`=0, `coment_dcsrb_thu_2`=? WHERE `id_control` = ?');
        $query->execute([$not_check, (int)$val_control]);
        break;
    case '1_obj_fri':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_fri_1`=0, `coment_dcsrb_fri_1`=? WHERE `id_control` = ?');
        $query->execute([$not_check, (int)$val_control]);
        break;
    case '2_obj_fri':
        // исправлена опечатка в имени столбца комментария
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_fri_2`=0, `coment_dcsrb_fri_2`=? WHERE `id_control` = ?');
        $query->execute([$not_check, (int)$val_control]);
        break;
    default:
        echo "Неподдерживаемый формат val_day";
        exit();
}

if (isset($query)) {
    if ($query->rowCount() > 0) {
        echo 1;
    } else {
        echo "Запись не найдена или данные не изменились";
    }
} else {
    echo "Ошибка выполнения запроса";
}
