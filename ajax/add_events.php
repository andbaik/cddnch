<?php
include_once '../block/connect_db.php';
$error = '';


$weekNumber = trim(filter_var($_POST['weekNumber'], FILTER_SANITIZE_SPECIAL_CHARS));



//Mon
$object_1_mon = !empty($_POST['object_1_mon']) ? trim(filter_var($_POST['object_1_mon'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_1_mon_type = !empty($_POST['object_1_mon_type']) ? trim(filter_var($_POST['object_1_mon_type'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_2_mon = !empty($_POST['object_2_mon']) ? trim(filter_var($_POST['object_2_mon'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_2_mon_type = !empty($_POST['object_2_mon_type']) ? trim(filter_var($_POST['object_2_mon_type'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$checkbox_mon = !empty($_POST['checkbox_mon']) ? 1 : 0;
$coments_mon = trim(filter_var($_POST['coments_mon'], FILTER_SANITIZE_SPECIAL_CHARS));


//tue
$object_1_tue = !empty($_POST['object_1_tue']) ? trim(filter_var($_POST['object_1_tue'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_1_tue_type = !empty($_POST['object_1_tue_type']) ? trim(filter_var($_POST['object_1_tue_type'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_2_tue = !empty($_POST['object_2_tue']) ? trim(filter_var($_POST['object_2_tue'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_2_tue_type = !empty($_POST['object_2_tue_type']) ? trim(filter_var($_POST['object_2_tue_type'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$checkbox_tue = !empty($_POST['checkbox_tue']) ? 1 : 0;
$coments_tue = !empty($_POST['coments_tue']) ? trim(filter_var($_POST['coments_tue'], FILTER_SANITIZE_SPECIAL_CHARS)) : null;

//wed
$object_1_wed = !empty($_POST['object_1_wed']) ? trim(filter_var($_POST['object_1_wed'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_1_wed_type =!empty($_POST['object_1_wed_type']) ?  trim(filter_var($_POST['object_1_wed_type'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_2_wed = !empty($_POST['object_2_wed']) ? trim(filter_var($_POST['object_2_wed'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_2_wed_type = !empty($_POST['object_2_wed_type']) ? trim(filter_var($_POST['object_2_wed_type'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$checkbox_wed = !empty($_POST['checkbox_wed']) ? 1 : 0;
$coments_wed = !empty($_POST['coments_wed']) ? trim(filter_var($_POST['coments_wed'], FILTER_SANITIZE_SPECIAL_CHARS)) : null;

//thu
$object_1_thu = !empty($_POST['object_1_thu']) ? trim(filter_var($_POST['object_1_thu'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_1_thu_type = !empty($_POST['object_1_thu_type']) ? trim(filter_var($_POST['object_1_thu_type'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_2_thu = !empty($_POST['object_2_thu']) ? trim(filter_var($_POST['object_2_thu'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_2_thu_type = !empty($_POST['object_2_thu_type']) ? trim(filter_var($_POST['object_2_thu_type'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$checkbox_thu = !empty($_POST['checkbox_thu']) ? 1 : 0;
$coments_thu = !empty($_POST['coments_thu']) ? trim(filter_var($_POST['coments_thu'], FILTER_SANITIZE_SPECIAL_CHARS)) : null;

//fri
$object_1_fri = !empty($_POST['object_1_fri']) ? trim(filter_var($_POST['object_1_fri'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_1_fri_type = !empty($_POST['object_1_fri_type']) ? trim(filter_var($_POST['object_1_fri_type'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_2_fri = !empty($_POST['object_2_fri']) ? trim(filter_var($_POST['object_2_fri'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$object_2_fri_type = !empty($_POST['object_2_fri_type']) ? trim(filter_var($_POST['object_2_fri_type'], FILTER_SANITIZE_SPECIAL_CHARS)) : 0;
$checkbox_fri = !empty($_POST['checkbox_fri']) ? 1 : 0;
$coments_fri = !empty($_POST['coments_fri']) ? trim(filter_var($_POST['coments_fri'], FILTER_SANITIZE_SPECIAL_CHARS)) : null;


$id_dnch = trim(filter_var($_POST['id_dnch'], FILTER_SANITIZE_SPECIAL_CHARS));

$query_check = $pdo->query("SELECT COUNT(*) FROM `controls` WHERE `id_dnch`=$id_dnch AND `weekNumber`=$weekNumber");

    $row_count = $query_check->fetch(PDO::FETCH_ASSOC);
    $count = $row_count['COUNT(*)'];

if ($count > 0){
    echo "Вы пытаетесь перезаписать данные";
    exit();
}

if (empty($object_1_mon) && empty($object_1_tue) && empty($object_1_wed) && empty($object_1_thu) && empty($object_1_fri)) {
    $error = "Проверьте выбор станций обязательных!";
} elseif (empty($object_1_mon_type) || empty($object_1_tue_type) || empty($object_1_wed_type) || empty($object_1_thu_type) || empty($object_1_fri_type)) {
    $error = "Проверьте что указан вид проверки у первого объекта";
}
if ($error != '') {
    echo $error;
    exit();
};



try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $query = $pdo->prepare("INSERT INTO controls SET `weekNumber`=?, `id_dnch`=?, `object_1_mon`=?, `object_1_mon_type`=?, `object_2_mon`=?, `object_2_mon_type`=?,   `checkbox_mon`=?, `coments_mon`=?, `object_1_tue`=?, `object_1_tue_type`=?, `object_2_tue`=?, `object_2_tue_type`=?,   `checkbox_tue`=?, `coments_tue`=?, `object_1_wed`=?, `object_1_wed_type`=?, `object_2_wed`=?, `object_2_wed_type`=?,   `checkbox_wed`=?, `coments_wed`=?, `object_1_thu`=?, `object_1_thu_type`=?, `object_2_thu`=?, `object_2_thu_type`=?,   `checkbox_thu`=?, `coments_thu`=?, `object_1_fri`=?, `object_1_fri_type`=?, `object_2_fri`=?, `object_2_fri_type`=?,   `checkbox_fri`=?, `coments_fri`=?");
    $query->execute([$weekNumber, $id_dnch, $object_1_mon, $object_1_mon_type, $object_2_mon, $object_2_mon_type, $checkbox_mon, $coments_mon, $object_1_tue, $object_1_tue_type, $object_2_tue, $object_2_tue_type, $checkbox_tue, $coments_tue, $object_1_wed, $object_1_wed_type, $object_2_wed, $object_2_wed_type, $checkbox_wed, $coments_wed, $object_1_thu, $object_1_thu_type, $object_2_thu, $object_2_thu_type, $checkbox_thu, $coments_thu, $object_1_fri, $object_1_fri_type, $object_2_fri, $object_2_fri_type, $checkbox_fri, $coments_fri]);

    $info = $pdo->errorInfo();
    //print_r($info);
} catch (Exception $e) {
    echo 'Exception -> ';
    var_dump($e->getMessage());
}




echo 1;
