<?php
$title = 'Страница Администраторов';

include_once('block/header.php');
include_once('block/setting.php');
include_once('function/function.php');
include_once('block/connect_db.php');

$d = 7;
$dcs = 7;


echo " NEW D= $d DCS = $dcs";

function getRailway()
{
    global $pdo;
    $html_railways = "<option value=0> Выберите дирекцию</option>";
    $railways_query = $pdo->query('SELECT * FROM `d`');
    while ($row_railway = $railways_query->fetch(PDO::FETCH_OBJ)) {
        $html_railways .= "<option value = {$row_railway->id_d} > {$row_railway->d} </option>";
    };
    return $html_railways;
};

function getDcs()
{
    global $pdo;
    $data = "<option value=0> Выберите центр</option>";
    $code = $_POST['code'];
    $dcs_query = $pdo->query("SELECT * FROM `dcs` WHERE `id_d` = $code");
    while ($row_dcs = $dcs_query->fetch(PDO::FETCH_OBJ)) {
        $data .= "<option value= {$row_dcs->id_dcs}> {$row_dcs->dcs_name}</option>";
    }
    return ($data);
};


$html1 = getRailway();

function getDnch()
{
    global $pdo;
    $data = "<option value=0> Выберите ДНЧ</option>";
    $code_d = $_POST['code_d'];
    $code_dcs = $_POST['code_dcs'];
    $dnch_query = $pdo->query("SELECT * FROM `dnch` WHERE `id_d`= $code_d AND `id_dcs` = $code_dcs");
    while ($row_dnch = $dnch_query->fetch(PDO::FETCH_OBJ)) {
        $data .= "<option value= {$row_dnch->id_dnch}> {$row_dnch->dnch_name}</option>";
    }
    return ($data);
};

if (!empty($_POST['code'])) {
    echo getDcs();
    exit;
}
if (!empty($_POST['code_dcs'])) {
    echo getDnch();
    exit;
}
// инициализация параметров, чтобы в запросах не было пустых значений
$d   = isset($_POST['code_d'])   ? (int)$_POST['code_d']   : 0;
$dcs = isset($_POST['code_dcs']) ? (int)$_POST['code_dcs'] : 0;
// уровень пользователя, подставьте реальное значение из сессии/логики
 $level = 2;

// строим SQL строку отдельно и используем подготовленные выражения
switch ($level) {
    case 1:
        $sql = "SELECT * FROM user_control
                JOIN d   ON user_control.id_d   = d.id_d
                JOIN dcs ON user_control.id_dcs = dcs.id_dcs
                JOIN dnch ON user_control.id_dnch = dnch.id_dnch";
        break;
    case 2:
        $sql = "SELECT * FROM user_control
                JOIN d   ON user_control.id_d   = d.id_d
                JOIN dcs ON user_control.id_dcs = dcs.id_dcs
                JOIN dnch ON user_control.id_dnch = dnch.id_dnch
                WHERE user_control.id_d = :d";
        break;
    case 3:
        $sql = "SELECT * FROM user_control
                JOIN d   ON user_control.id_d   = d.id_d
                JOIN dcs ON user_control.id_dcs = dcs.id_dcs
                JOIN dnch ON user_control.id_dnch = dnch.id_dnch
                WHERE user_control.id_d = :d AND user_control.id_dcs = :dcs";
        break;
    default:
        $sql = "SELECT * FROM user_control
                JOIN d   ON user_control.id_d   = d.id_d
                JOIN dcs ON user_control.id_dcs = dcs.id_dcs
                JOIN dnch ON user_control.id_dnch = dnch.id_dnch";
        break;
}
// выполняем запрос и ловим возможные ошибки
try {
    $stmt = $pdo->prepare($sql);
    if ($level === 2) {
        $stmt->execute([':d' => $d]);
    } elseif ($level === 3) {
        $stmt->execute([':d' => $d, ':dcs' => $dcs]);
    } else {
        $stmt->execute();
    }
    $query_dnch = $stmt;
} catch (PDOException $e) {
    echo '<pre>SQL: ' . $sql . "\nОшибка: " . $e->getMessage() . '</pre>';
    exit;
}



?>
<body class="wrapper">
<div id="preloader">Добро пожаловать!</div>

<main class="main">
    <div class="container">
        
    </div>
</main>

<?php include_once("block/footer.php"); ?>

</body>
</html>