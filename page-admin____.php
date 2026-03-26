<?php
$title = 'Страница администрирования';

include_once('block/header.php');
include_once('block/setting.php');
include_once('function/function.php');
include_once('block/connect_db.php');

// обработчик ajax-запросов для динамических селектов
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ajax'])) {
    header('Content-Type: text/html; charset=utf-8');
    switch ($_POST['ajax']) {
        case 'dcs':  echo getDcs();  break;
        case 'dnch': echo getDnch(); break;
        case 'table': echo getUserTable(
            isset($_POST['id_d']) ? (int)$_POST['id_d'] : 0,
            isset($_POST['id_dcs']) ? (int)$_POST['id_dcs'] : 0,
            isset($_SESSION['level']) ? (int)$_SESSION['level'] : 2
        ); break;
    }
    exit;
}

$error = '';

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
    // POST may contain 'code' (old clients) or 'id_d' (new bindCascade) or modal names like 'id_d_m3', 'id_d_m4', etc.
    $code = isset($_POST['code']) ? (int)$_POST['code'] : 
            (isset($_POST['id_d']) ? (int)$_POST['id_d'] : 
            (isset($_POST['id_d_m']) ? (int)$_POST['id_d_m'] : 
            (isset($_POST['id_d_m2']) ? (int)$_POST['id_d_m2'] : 
            (isset($_POST['id_d_m3']) ? (int)$_POST['id_d_m3'] : 
            (isset($_POST['id_d_m4']) ? (int)$_POST['id_d_m4'] : 0)))));
    $stmt = $pdo->prepare('SELECT id_dcs,dcs_name FROM dcs WHERE id_d = :code');
    $stmt->execute([':code' => $code]);
    while ($row_dcs = $stmt->fetch(PDO::FETCH_OBJ)) {
        $data .= "<option value= {$row_dcs->id_dcs}> {$row_dcs->dcs_name}</option>";
    }
    return $data;
};

$html1 = getRailway();

function getDnch()
{
    global $pdo;
    $data = "<option value=0> Выберите ДНЧ</option>";
    // support both old param names and new ones
    $code_d   = isset($_POST['code_d'])   ? (int)$_POST['code_d']   : 
                (isset($_POST['id_d']) ? (int)$_POST['id_d'] : 
                (isset($_POST['id_d_m']) ? (int)$_POST['id_d_m'] : 
                (isset($_POST['id_d_m2']) ? (int)$_POST['id_d_m2'] : 
                (isset($_POST['id_d_m3']) ? (int)$_POST['id_d_m3'] : 
                (isset($_POST['id_d_m4']) ? (int)$_POST['id_d_m4'] : 0)))));
    $code_dcs = isset($_POST['code_dcs']) ? (int)$_POST['code_dcs'] : 
                (isset($_POST['id_dcs']) ? (int)$_POST['id_dcs'] : 
                (isset($_POST['id_dcs_m']) ? (int)$_POST['id_dcs_m'] : 
                (isset($_POST['id_dcs_m2']) ? (int)$_POST['id_dcs_m2'] : 
                (isset($_POST['id_dcs_m3']) ? (int)$_POST['id_dcs_m3'] : 
                (isset($_POST['id_dcs_m4']) ? (int)$_POST['id_dcs_m4'] : 0)))));
    $stmt = $pdo->prepare('SELECT id_dnch,dnch_name FROM dnch WHERE id_d = :d AND id_dcs = :dcs');
    $stmt->execute([':d' => $code_d, ':dcs' => $code_dcs]);
    while ($row_dnch = $stmt->fetch(PDO::FETCH_OBJ)) {
        $data .= "<option value= {$row_dnch->id_dnch}> {$row_dnch->dnch_name}</option>";
    }
    return $data;
};

// удалён старый двоичный обработчик POST

function getUserTable($id_d, $id_dcs, $level) {
    global $pdo;
    $debug = "Level: $level, id_d: $id_d, id_dcs: $id_dcs<br>";
    $baseSql = "SELECT *
                FROM user_control
                JOIN d   ON user_control.id_d   = d.id_d
                JOIN dcs ON user_control.id_dcs = dcs.id_dcs
                JOIN dnch ON user_control.id_dnch = dnch.id_dnch";
    $where = '';
    $params = [];
    switch ($level) {
        case 2:
            // фильтр по дирекции только если задан реальный идентификатор
            if ($id_d > 0) {
                $where = 'WHERE user_control.id_d = :id_d';
                $params[':id_d'] = $id_d;
            }
            break;
        case 3:
            // фильтр по дирекции и центру; необходимо, чтобы оба были ненулевыми
            if ($id_d > 0 && $id_dcs > 0) {
                $where = 'WHERE user_control.id_d = :id_d AND user_control.id_dcs = :id_dcs';
                $params[':id_d'] = $id_d;
                $params[':id_dcs'] = $id_dcs;
            }
            break;
        case 1:
        default:
            // без ограничений
            break;
    }

    $sql = trim("$baseSql $where");
    $debug .= "SQL: $sql<br>Params: " . json_encode($params) . "<br>";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $query_dnch = $stmt;
        $debug .= "Row count: " . $stmt->rowCount() . "<br>";
    } catch (PDOException $e) {
        return $debug . 'Ошибка SQL: ' . $e->getMessage();
    }

    $html2 = $debug;
    $k = 1;
    while ($row = $query_dnch->fetch(PDO::FETCH_OBJ)) {
        $html2 .= "
            <tr>
                <td>$k </td>
                <td>$row->d</td>
                <td>$row->dcs_name</td>
                <td>$row->dnch_name</td>
                <td>$row->surname $row->name $row->midl_name</td>                                     
        ";

        $html_station = "";
        $query_stations_dnch = $pdo->query("SELECT * FROM `stations` WHERE `id_dnch` = $row->id_dnch");
        while ($row_station = $query_stations_dnch->fetch(PDO::FETCH_OBJ)) {
            $html_station .= $row_station->station . ', ';
        };
        $html2 .= "<td>$html_station</td>";

        $html2 .= "
                <td><a class='openModalEdit'  data-id_user=$row->id_user  href='#' ><i class='fa-solid fa-pencil'></i></td>
                <td><a href='../edit_dnch.php?edit=2&id={$row->id_user}'><i class='fa-solid fa-trash-can'></i></td>
                </tr>";
        $k++;
    }

    if ($k == 1) {
        $html2 .= '<tr><td colspan="8">Нет данных</td></tr>';
    }
    return $html2;
}

// сессия для уровня доступа/фильтров
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// значения из POST/сессии
$id_d   = isset($_POST['code_d'])   ? (int)$_POST['code_d']   : (isset($_SESSION['id_d']) ? (int)$_SESSION['id_d'] : 0);
$id_dcs = isset($_POST['code_dcs']) ? (int)$_POST['code_dcs'] : (isset($_SESSION['id_dcs']) ? (int)$_SESSION['id_dcs'] : 0);
$level  = isset($_SESSION['level'])  ? (int)$_SESSION['level']  : 2;


?>

<body>
    <div id="preloader">Добро пожаловать!</div>

    <!-- Модальное окно редактирования пользователя -->

    <div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true" data-bs-focus="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalLabel">Редактировать Пользователя</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary" id="update-user" form="edit_user">Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>

    <!--Модальное окно добавления станции-->
    <div class="modal fade " id="modalStation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-focus="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Добавить станцию</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="ajax/add_station.php" method="POST" id="add_station" enctype="multipart/form-data">
                        <label for="id_d_m">Выберите дирекцию</label>
                        <select name="id_d_m" id="id_d_m" class="form-control"> <?php echo $html1; ?></select>
                        <div class="dcs-select" disabled>
                            <label for="id_dcs_m">Выберите ДЦС</label>
                            <select name="id_dcs_m" id="id_dcs_m" class="form-control"></select>
                        </div>
                        <div class="dnch-select" disabled>
                            <label for="id_dnch_m">* Выберите ДНЧ:</label>
                            <select name="id_dnch_m" id="id_dnch_m" class="form-control">
                            </select>
                        </div>
                        <label for="name_station">Введите название станции</label>
                        <input type="text" class="form-control" id="name_station" name="name_station">
                </div>
                <div class="info">
                    <div class="error-mess text-center" id="error-block_m"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary" id="save-station">Сохранить изменения</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!--Модальное окно добавления ДНЧ-->

    <div class="modal fade " id="modalDnch" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true" data-bs-focus="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalLabel">Добавить Инструктора</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="ajax/add_dnch.php" method="POST" id="add_dnch" enctype="multipart/form-data">
                        <label for="id_d_m2">Выберите дирекцию</label>
                        <select name="id_d_m2" id="id_d_m2" class="form-control"> <?php echo $html1; ?></select>
                        <div class="dcs-select" disabled>
                            <label for="id_dcs_m2">Выберите ДЦС</label>
                            <select name="id_dcs_m2" id="id_dcs_m2" class="form-control"></select>
                        </div>
                        <div class="row">
                            <div class="dnch_block col-6">
                                <label for="dnch_name">Введите наименование ДНЧ</label>
                                <input type="text" class="form-control" id="dnch_name" name="dnch_name">
                            </div>
                            <div class="dnch_block_shot col-6">
                                <label for="dnch_name_shot">Введите номер ДНЧ</label>
                                <input type="text" class="form-control" id="dnch_name_shot" name="dnch_name_shot">
                            </div>
                        </div>

                </div>
                <div class="info">
                    <div class="error-mess text-center" id="error-block_m2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary" id="save-dnch">Сохранить изменения</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!--Модальное окно добавления Пользователя -->

    <div class="modal fade " id="modalUser" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true" data-bs-focus="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalLabel">Добавить Пользователя</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="ajax/add_user.php" method="POST" id="add_user" enctype="multipart/form-data">
                        <label for="id_d_m3">Выберите дирекцию</label>
                        <select name="id_d_m3" id="id_d_m3" class="form-control"> <?php echo $html1; ?></select>

                        <div class="row">
                            <div class="dcs-select col-6" disabled>
                                <label for="id_dcs_m3">Выберите ДЦС</label>
                                <select name="id_dcs_m3" id="id_dcs_m3" class="form-control"></select>
                            </div>
                            <div class="dnch-select col-6" disabled>
                                <label for="id_dnch_m3">* Выберите ДНЧ:</label>
                                <select name="id_dnch_m3" id="id_dnch_m3" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="famname col-4">
                                <label for="surname">Фамилия</label>
                                <input type="text" class="form-control" id="surname" name="surname">
                            </div>
                            <div class="famname col-4">
                                <label for="name">Имя</label>
                                <input type="text" class="form-control" id="name" name="name" autocomplete="off">
                            </div>
                            <div class="famname col-4">
                                <label for="midl_name">Отчество</label>
                                <input type="text" class="form-control" id="midl_name" name="midl_name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="login col-4">
                                <label for="login">Логин</label>
                                <input type="text" class="form-control" id="login" name="login">
                            </div>
                            <div class="login col-4">
                                <label for="password">Пароль</label>
                                <input type="text" class="form-control" id="password" name="password">
                            </div>
                            <div class="login col-4">
                                <label for="email">email</label>
                                <input type="email" class="form-control" id="email" name="email" autocomplete="off">
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="active col-4 mt-2">
                                <label class="toggle">
                                    <span class="toggle-lable">Активировать</span>
                                    <input class="toggle-checkbox" type="checkbox" name="checkbox_active"
                                        id="checkbox_active">
                                    <div class="toggle-switch"></div>
                                </label>
                            </div>
                            <div class="type col-8">
                                <select name="level" id="level" class="form-control">
                                    <option value="0">Выберите уровень доступа</option>
                                    <option value="1">Администратор ЦД</option>
                                    <option value="2">Администратор Д</option>
                                    <option value="3">Администратор ДЦС</option>
                                    <option value="4">Инструктор ДНЧ</option>
                                </select>
                            </div>
                        </div>

                </div>
                <div class="info">
                    <div class="error-mess text-center" id="error-block_m3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary" id="save-user">Сохранить изменения</button>
                </div>
                </form>
            </div>
        </div>
    </div>







    <div class="wrapper">

        <main class="main">
            <div class="container">
                <header class="header">
                    <?php include_once('block/nav_dcsrb.php'); ?>
                </header>
                <div class="heading">
                    <h1 class="text-center">Страница для администраторов</h1>
                </div>

                <div class="page-change d-flex  flex-md-column text-center my-3">
                    <div class="row">
                        <div class="change_d col">
                            <h4>Дирекция управления движением </h4>
                            <div class="select_d">
                                <label for="id_d">* Выберите дирекцию:</label>
                                <select name="id_d" id="id_d" class="form-control">
                                    <?php echo $html1; ?>
                                </select>
                            </div>
                            <div class="add_user my-3">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUser">Добавить пользователя</button>
                            </div>
                        </div>
                        <div class="change_dcs col">
                            <h4>Центр организации работы станций </h4>
                            <div class="select_dcs">
                                <div class="dcs-select" disabled>
                                    <label for="id_dcs">* Выберите центр:</label>
                                    <select name="id_dcs" id="id_dcs" class="form-control">
                                    </select>
                                </div>
                                <div class="add_stations my-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalStation">Добавить станцию</button>
                                </div>
                            </div>
                        </div>
                        <div class="change_dnch col">
                            <h4>Инструктор по безопасности</h4>
                            <div class="select_dnch md-col">
                                <div class="dnch-select" disabled>
                                    <label for="id_dnch">* Выберите ДНЧ:</label>
                                    <select name="id_dnch" id="id_dnch" class="form-control">
                                    </select>
                                </div>
                            </div>
                            <div class="add_dnch my-3">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalDnch">Добавить ДНЧ</button>
                            </div>
                        </div>

                        <div class="info">
                            <div class="error-mess text-center" id="error-block"></div>
                        </div>

                        <div class="frame_dnch">
                            <h5>Список ДНЧ</h5>
                            <div class="frame_edit">

                                <table id="user-table" class="iksweb" style="table-layout: fixed; width: 100%">
                                    <colgroup>
                                        <col style="width: 45px">
                                        <col style="width: 100px">
                                        <col style="width: 100px">
                                        <col style="width: 100px">
                                        <col style="width: 250px">
                                        <col style="width: auto">
                                        <col style="width: 34px">
                                        <col style="width: 34px">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th class="tg-lboi">№</th>
                                            <th class="tg-lboi">Дирекция</th>
                                            <th class="tg-lboi">Центр</th>
                                            <th class="tg-cly1">ДНЧ</th>
                                            <th class="tg-nrix">ФИО</th>
                                            <th class="tg-cly1">Станции обслуживания</th>
                                            <th class="tg-0lax">кор</th>
                                            <th class="tg-0lax">удл</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo getUserTable($id_d, $id_dcs, $level); ?>
                                    </tbody>
                                </table>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </main>

        <?php include_once("block/footer.php"); ?>
        <script>
            // Универсальный обработчик "каскадных" селектов
            function bindCascade(parentSel, childSel, ajaxType, extraSelArray) {
                $(document).on('change', parentSel, function() {
                    var val = $(this).val();
                    var data = { ajax: ajaxType };
                    // передаём имя поля как ключ
                    data[$(this).attr('name')] = val;
                    // добавляем дополнительные параметры, если указаны
                    if (Array.isArray(extraSelArray)) {
                        extraSelArray.forEach(function(sel) {
                            var $el = $(sel);
                            if ($el.length) {
                                data[$el.attr('name')] = $el.val();
                            }
                        });
                    }
                    $(childSel).load('page-admin.php', data, function() {
                        $(childSel).closest('div').fadeIn('slow');
                        // Если загружен #id_dcs, устанавливаем значение и триггерим change
                        if (childSel === '#id_dcs') {
                            $(childSel).val(<?php echo $id_dcs; ?>);
                            $(childSel).trigger('change');
                        }
                    });
                });
            }

            $(function() {
                bindCascade('#id_d',        '#id_dcs',       'dcs');
                bindCascade('#id_dcs',      '#id_dnch',      'dnch', ['#id_d']);
                bindCascade('#id_d_m',      '#id_dcs_m',     'dcs');
                bindCascade('#id_dcs_m',    '#id_dnch_m',    'dnch', ['#id_d_m']);
                bindCascade('#id_d_m2',     '#id_dcs_m2',    'dcs');
                bindCascade('#id_dcs_m2',   '#id_dnch_m2',   'dnch', ['#id_d_m2']);
                bindCascade('#id_d_m3',     '#id_dcs_m3',    'dcs');
                bindCascade('#id_dcs_m3',   '#id_dnch_m3',   'dnch', ['#id_d_m3']);
                bindCascade('#id_d_m4',     '#id_dcs_m4',    'dcs');
                bindCascade('#id_dcs_m4',   '#id_dnch_m4',   'dnch', ['#id_d_m4']);

                // Инициализация селектов при загрузке
                if ($('#id_d').val() > 0) {
                    $('#id_d').trigger('change');
                }
            });
        </script>

        <script>
            // Обновление таблицы при изменении фильтров
            $(function() {
                $('#id_d, #id_dcs').on('change', function() {
                    var id_d = $('#id_d').val();
                    var id_dcs = $('#id_dcs').val();
                    console.log('Updating table with id_d:', id_d, 'id_dcs:', id_dcs);
                    $.post('page-admin.php', { ajax: 'table', id_d: id_d, id_dcs: id_dcs }, function(data) {
                        console.log('Received data:', data);
                        $('#user-table tbody').html(data);
                    });
                });
            });
        </script>

        <script>
            // Делегированная обработка клика по кнопкам редактирования
            $(document).on('click', '.openModalEdit', function(e) {
                e.preventDefault();
                var id_user = $(this).data('id_user') || $(this).attr('data-id_user');
                // Загружаем содержимое в тело модального окна и показываем модал
                $('#modalEditUser .modal-body').load('ajax/getContent.php?id=' + id_user, function() {
                    $('#modalEditUser').modal('show');
                    // Инициализируем каскадные селекты после загрузки
                    bindCascade('#id_d_m4', '#id_dcs_m4', 'dcs');
                    bindCascade('#id_dcs_m4', '#id_dnch_m4', 'dnch', ['#id_d_m4']);
                });
            });
        </script>

        <script>
            //Обработка модального окна добавления станцции
            $("form#add_station").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: 'ajax/add_station.php',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data == 1) {
                            $("#save-station").text("Выполнено");
                            $("#error-block_m").hide();
                            alert('Станция была добавлена!');
                            //window.location.replace("http://dnch.loc/control_dcsrb.php");
                            document.location.reload(true);
                        } else {
                            $("#error-block_m").show();
                            $("#error-block_m").text(data);
                        }
                    }
                });
            });

            //Обработка модального окна добавления ДНЧ
            $("form#add_dnch").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: 'ajax/add_dnch.php',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data == 1) {
                            $("#save-dnch").text("Выполнено");
                            $("#error-block_m2").hide();
                            alert('ДНЧ добавлен!');
                            //window.location.replace("http://dnch.loc/control_dcsrb.php");
                            document.location.reload(true);
                        } else {
                            $("#error-block_m2").show();
                            $("#error-block_m2").text(data);
                        }
                    }
                });
            });

            //Обработка модального окна добавления Пользователя
            $("form#add_user").submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: 'ajax/add_user.php',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data == 1) {
                            $("#save-user").text("Выполнено");
                            $("#error-block_m3").hide();
                            alert('Пользователь добавлен!');
                            //window.location.replace("http://dnch.loc/control_dcsrb.php");
                            document.location.reload(true);
                        } else {
                            $("#error-block_m3").show();
                            $("#error-block_m3").text(data);
                        }
                    }
                });
            });

            //Обработка модального окна редактирования Пользователя
            $(document).on('submit', 'form#edit_user', function(e) {
                e.preventDefault();
                console.log('Worked');
                var formData = new FormData(this);
                $.ajax({
                    url: 'ajax/update_user.php',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data == 1) {
                            $("#update-user").text("Выполнено");
                            $("#error-block_edit").hide();
                            alert('Пользователь обновлен!');
                            $('#modalEditUser').modal('hide');
                            document.location.reload(true);
                        } else {
                            $("#error-block_edit").show();
                            $("#error-block_edit").text(data);
                        }
                    }
                });
            });
        </script>

        <script>
            // Управление фокусом для модальных окон, чтобы избежать ошибки aria-hidden
            $('.modal').on('hidden.bs.modal', function () {
                // Убираем фокус с любых кнопок внутри модального окна
                $(this).find('button:focus').blur();
            });
        </script>

    </div>

</body>
</html>
