<?php
session_start();

include_once '../block/connect_db.php';
$error = '';



$id = trim(filter_var($_GET['id'], FILTER_SANITIZE_SPECIAL_CHARS));
$object = trim(filter_var($_GET['object'], FILTER_SANITIZE_SPECIAL_CHARS));

$query_week_set = $pdo->query("SELECT `weekNumber` FROM `controls` WHERE id_control = $id");
$row_week_set = $query_week_set->fetch(PDO::FETCH_OBJ);
$week_set = $row_week_set->weekNumber;

$weekNumber = $_SESSION['weekNumber'];


switch ($object) {
    case '1_obj_mon':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_mon_1`=1, `coment_dcsrb_mon_1`= NULL WHERE `id_control` = ?');
        $query->execute([(int)$id]);
        break;
    case '2_obj_mon':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_mon_2`=1, `coment_dcsrb_mon_2`= NULL WHERE `id_control` = ?');
        $query->execute([(int)$id]);
        break;
    case '1_obj_tue':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_tue_1`=1, `coment_dcsrb_tue_1`= NULL WHERE `id_control` = ?');
        $query->execute([(int)$id]);
        break;
    case '2_obj_tue':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_tue_2`=1, `coment_dcsrb_tue_2`= NULL WHERE `id_control` = ?');
        $query->execute([(int)$id]);
        break;
    case '1_obj_wed':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_wed_1`=1, `coment_dcsrb_wed_1`= NULL WHERE `id_control` = ?');
        $query->execute([(int)$id]);
        break;
    case '2_obj_wed':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_wed_2`=1, `coment_dcsrb_wed_2`= NULL WHERE `id_control` = ?');
        $query->execute([(int)$id]);
        break;
    case '1_obj_thu':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_thu_1`=1, `coment_dcsrb_thu_1`= NULL WHERE `id_control` = ?');
        $query->execute([(int)$id]);
        break;
    case '2_obj_thu':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_thu_2`=1, `coment_dcsrb_thu_2`= NULL WHERE `id_control` = ?');
        $query->execute([(int)$id]);
        break;
    case '1_obj_fri':
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_fri_1`=1, `coment_dcsrb_fri_1`= NULL WHERE `id_control` = ?');
        $query->execute([(int)$id]);
        break;
    case '2_obj_fri':
        // исправлена опечатка в имени столбца комментария
        $query = $pdo->prepare('UPDATE `controls` SET `check_dcsrb_fri_2`=1, `coment_dcsrb_fri_2`= NULL WHERE `id_control` = ?');
        $query->execute([(int)$id]);
        break;
    default:
        echo "Неподдерживаемый формат id";
        exit();
}

if (isset($query)) {
    if ($query->rowCount() > 0) {
        header('Location:' . $site . '/control_dcsrb.php?week_set=user&weekNumber='. $weekNumber);
        unset($_SESSION["weekNumber"]);
    } else {
        echo "Запись не найдена или данные не изменились";
    }
} else {
    echo "Ошибка выполнения запроса";
}

