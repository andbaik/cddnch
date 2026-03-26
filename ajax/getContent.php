<?php
include_once '../block/connect_db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Неверный ID пользователя";
    exit;
}

$id_user = (int)$_GET['id'];

// Получаем данные пользователя
$query = $pdo->prepare("
    SELECT user_control.*, d.d, dcs.dcs_name, dnch.dnch_name
    FROM user_control
    JOIN d ON user_control.id_d = d.id_d
    JOIN dcs ON user_control.id_dcs = dcs.id_dcs
    JOIN dnch ON user_control.id_dnch = dnch.id_dnch
    WHERE user_control.id_user = ?
");
$query->execute([$id_user]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Пользователь не найден";
    exit;
}

// Функция для получения списка дирекций
function getRailway()
{
    global $pdo;
    $html_railways = "<option value=0> Выберите дирекцию</option>";
    $railways_query = $pdo->query('SELECT * FROM `d`');
    while ($row_railway = $railways_query->fetch(PDO::FETCH_OBJ)) {
        $selected = ($row_railway->id_d == $GLOBALS['user']['id_d']) ? 'selected' : '';
        $html_railways .= "<option value='{$row_railway->id_d}' {$selected}> {$row_railway->d} </option>";
    }
    return $html_railways;
}

// Функция для получения списка ДЦС для выбранной дирекции
function getDcs($id_d)
{
    global $pdo;
    $data = "<option value=0> Выберите центр</option>";
    $stmt = $pdo->prepare('SELECT id_dcs, dcs_name FROM dcs WHERE id_d = :code');
    $stmt->execute([':code' => $id_d]);
    while ($row_dcs = $stmt->fetch(PDO::FETCH_OBJ)) {
        $selected = ($row_dcs->id_dcs == $GLOBALS['user']['id_dcs']) ? 'selected' : '';
        $data .= "<option value='{$row_dcs->id_dcs}' {$selected}> {$row_dcs->dcs_name}</option>";
    }
    return $data;
}

// Функция для получения списка ДНЧ для выбранной дирекции и ДЦС
function getDnch($id_d, $id_dcs)
{
    global $pdo;
    $data = "<option value=0> Выберите ДНЧ</option>";
    $stmt = $pdo->prepare('SELECT id_dnch, dnch_name FROM dnch WHERE id_d = :d AND id_dcs = :dcs');
    $stmt->execute([':d' => $id_d, ':dcs' => $id_dcs]);
    while ($row_dnch = $stmt->fetch(PDO::FETCH_OBJ)) {
        $selected = ($row_dnch->id_dnch == $GLOBALS['user']['id_dnch']) ? 'selected' : '';
        $data .= "<option value='{$row_dnch->id_dnch}' {$selected}> {$row_dnch->dnch_name}</option>";
    }
    return $data;
}

$html1 = getRailway();
$dcs_options = getDcs($user['id_d']);
$dnch_options = getDnch($user['id_d'], $user['id_dcs']);

$checked = $user['active'] ? 'checked' : '';

$level_options = [
    1 => 'Администратор ЦД',
    2 => 'Администратор Д',
    3 => 'Администратор ДЦС',
    4 => 'Инструктор ДНЧ'
];

$level_select = "<option value='0'>Выберите уровень доступа</option>";
foreach ($level_options as $value => $label) {
    $selected = ($value == $user['level']) ? 'selected' : '';
    $level_select .= "<option value='{$value}' {$selected}>{$label}</option>";
}

echo "
<form action='#' method='POST' id='edit_user' enctype='multipart/form-data'>
    <input type='hidden' name='id_user' value='{$user['id_user']}'>
    <label for='id_d_m4'>Выберите дирекцию</label>
    <select name='id_d_m4' id='id_d_m4' class='form-control'>{$html1}</select>

    <div class='row'>
        <div class='dcs-select col-6'>
            <label for='id_dcs_m4'>Выберите ДЦС</label>
            <select name='id_dcs_m4' id='id_dcs_m4' class='form-control'>{$dcs_options}</select>
        </div>
        <div class='dnch-select col-6'>
            <label for='id_dnch_m4'>* Выберите ДНЧ:</label>
            <select name='id_dnch_m4' id='id_dnch_m4' class='form-control'>{$dnch_options}</select>
        </div>
    </div>
    <div class='row'>
        <div class='famname col-4'>
            <label for='surname'>Фамилия</label>
            <input type='text' class='form-control' id='surname' name='surname' value='{$user['surname']}'>
        </div>
        <div class='famname col-4'>
            <label for='name'>Имя</label>
            <input type='text' class='form-control' id='name' name='name' value='{$user['name']}' autocomplete='off'>
        </div>
        <div class='famname col-4'>
            <label for='midl_name'>Отчество</label>
            <input type='text' class='form-control' id='midl_name' name='midl_name' value='{$user['midl_name']}'>
        </div>
    </div>
    <div class='row'>
        <div class='login col-4'>
            <label for='login'>Логин</label>
            <input type='text' class='form-control' id='login' name='login' value='{$user['login']}'>
        </div>
        <div class='login col-4'>
            <label for='password'>Пароль</label>
            <input type='text' class='form-control' id='password' name='password' value='{$user['password']}'>
        </div>
        <div class='login col-4'>
            <label for='email'>email</label>
            <input type='email' class='form-control' id='email' name='email' value='{$user['email']}' autocomplete='off'>
        </div>
    </div>
    <div class='row my-3'>
        <div class='active col-4 mt-2'>
            <label class='toggle'>
                <span class='toggle-lable'>Активировать</span>
                <input class='toggle-checkbox' type='checkbox' name='checkbox_active' id='checkbox_active' {$checked}>
                <div class='toggle-switch'></div>
            </label>
        </div>
        <div class='type col-8'>
            <select name='level' id='level' class='form-control'>
                {$level_select}
            </select>
        </div>
    </div>
    <div class='info'>
        <div class='error-mess text-center' id='error-block_edit'></div>
    </div>
</form>
";
?>