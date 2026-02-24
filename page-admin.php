<?php
session_start();

$user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : false;
$id_u = $_SESSION['user']['id_user'];


$title = 'Страница администраторов';
include_once('block/header.php');
include_once('block/setting.php');
include_once('block/connect_db.php');

$query_admin = $pdo->query("SELECT `id_d`, `id_dcs`, `level`, `status` FROM `user_control` WHERE `id_user` = $id_u");
$row_admin = $query_admin->fetch(PDO::FETCH_OBJ);
$d = $row_admin->id_d;
$dcs = $row_admin->id_dcs;
$admin = $row_admin->level;


echo "ID = $id_u, D=$d, DCS=$dcs, LEVEL=$admin";


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

$admin = 1;
switch ($admin) {
    case '1':
        $query_dnch = $pdo->query("SELECT * FROM `user_control` JOIN d ON user_control.id_d = d.id_d JOIN dcs ON user_control.id_dcs = dcs.id_dcs JOIN dnch ON user_control.id_dnch = dnch.id_dnch");
        break;
    case '2':
        $query_dnch = $pdo->query("SELECT * FROM `user_control` JOIN d ON user_control.id_d = d.id_d JOIN dcs ON user_control.id_dcs = dcs.id_dcs JOIN dnch ON user_control.id_dnch = dnch.id_dnch WHERE id_d=$d");
        break;
    case '3':
        $query_dnch = $pdo->query("SELECT * FROM `user_control` JOIN d ON user_control.id_d = d.id_d JOIN dcs ON user_control.id_dcs = dcs.id_dcs JOIN dnch ON user_control.id_dnch = dnch.id_dnch WHERE id_d=$d AND id_dcs=$dcs");
        break;
    default:
        $query_dnch = $pdo->query("SELECT * FROM `user_control` JOIN d ON user_control.id_d = d.id_d JOIN dcs ON user_control.id_dcs = dcs.id_dcs JOIN dnch ON user_control.id_dnch = dnch.id_dnch");
        break;
}


?>
</head>

<body>
    <!--Модальное окно добавления станции-->
    <div class="modal fade " id="modalStation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Добавить станцию</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="add_station" action="multipart/form-data">
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

    <div class="modal fade " id="modalDnch" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalLabel">Добавить Инструктора</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="add_dnch" action="multipart/form-data">
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

    <!--Модальное окно добавления Пользователя-->

    <div class="modal fade " id="modalUser" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalLabel">Добавить Пользователя</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="add_user" action="multipart/form-data">
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
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="famname col-4">
                                <label for="midl_name">Отчество</label>
                                <input type="text" class="form-control" id="mmidl_name" name="midl_name">
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
                                <input type="email" class="form-control" id="email" name="email">
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



    <div id="preloader">Добро пожаловать!</div>
    <div class="wrapper">
        <header class="header">
            <?php include_once('block/nav.php'); ?>
        </header>
        <main class="main">
            <div class="container">
                <h1 class="text-center">Страница для администраторов</h1>


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


                                <table class="iksweb" style="table-layout: fixed; width: 100%">
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
                                        <?php
                                        $html2 = "";
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
                                        while($row_station = $query_stations_dnch->fetch(PDO::FETCH_OBJ)){
                                            $html_station .= $row_station->station . ', ';
                                        };
                                        $html2 .= "<td>$html_station</td>";

                                            if ($admin == 1) {
                                                $html2 .= "
            <td><a href='../edit_dnch.php?edit=1&id={$row->id_user}'><i class='fa-solid fa-pencil'></i></td>
            <td><a href='../edit_dnch.php?edit=2&id={$row->id_user}'><i class='fa-solid fa-trash-can'></i></td>
            </tr>";
                                            } else {
                                                $html2 = "
                                            <td></td>
                                            <td></td>
                                            </tr>";
                                            }
                                            $k++;
                                        }

                                        echo $html2;
                                        ?>
                                    </tbody>
                                </table>


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include_once('block/footer.php'); ?>

        <script>
            /*--выбор дирекции и ДЦС--*/
            $(function() {
                $('#id_d').change(function() {
                    var code = $(this).val();
                    $('#id_dcs').load('page-admin.php', {
                        code: code
                    }, function() {
                        $('.dcs-select').fadeIn('slow');
                    });
                });
                $('#id_dcs').change(function() {
                    var code_dcs = $(this).val();
                    var code_d = $('#id_d').val();
                    $('#id_dnch').load('page-admin.php', {
                        code_dcs: code_dcs,
                        code_d: code_d
                    }, function() {
                        $('.dnch-select').fadeIn('slow');
                    });
                });
            });

            /*--выбор дирекции и ДЦС--  в МОДАЛЬНОМ окне*/
            $(function() {
                $('#id_d_m').change(function() {
                    var code = $(this).val();
                    $('#id_dcs_m').load('page-admin.php', {
                        code: code
                    }, function() {
                        $('.dcs-select_m').fadeIn('slow');
                    });
                });
                $('#id_dcs_m').change(function() {
                    var code_dcs = $(this).val();
                    var code_d = $('#id_d_m').val();
                    $('#id_dnch_m').load('page-admin.php', {
                        code_dcs: code_dcs,
                        code_d: code_d
                    }, function() {
                        $('.dnch-select_m').fadeIn('slow');
                    });
                });
            });

            /*--выбор дирекции и ДЦС--  во ВТОРОМ МОДАЛЬНОМ окне*/
            $(function() {
                $('#id_d_m2').change(function() {
                    var code = $(this).val();
                    $('#id_dcs_m2').load('page-admin.php', {
                        code: code
                    }, function() {
                        $('.dcs-select_m2').fadeIn('slow');
                    });
                });
            });


            /*--выбор дирекции и ДЦС--  в ТРЕТЬЕМ МОДАЛЬНОМ окне*/
            $(function() {
                $('#id_d_m3').change(function() {
                    var code = $(this).val();
                    $('#id_dcs_m3').load('page-admin.php', {
                        code: code
                    }, function() {
                        $('.dcs-select_m3').fadeIn('slow');
                    });
                });
                $('#id_dcs_m3').change(function() {
                    var code_dcs = $(this).val();
                    var code_d = $('#id_d_m3').val();
                    $('#id_dnch_m3').load('page-admin.php', {
                        code_dcs: code_dcs,
                        code_d: code_d
                    }, function() {
                        $('.dnch-select_m3').fadeIn('slow');
                    });
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
                            exit;
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
                            exit;
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
                            $("#error-block_m2").hide();
                            alert('Пользователь добавлен!');
                            //window.location.replace("http://dnch.loc/control_dcsrb.php");
                            document.location.reload(true);
                            exit;
                        } else {
                            $("#error-block_m3").show();
                            $("#error-block_m3").text(data);
                        }
                    }
                });
            });
        </script>
    </div>
</body>

</html>