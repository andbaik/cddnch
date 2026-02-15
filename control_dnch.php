<?php
$title = 'Недельное планирование';

//берем неделю из адресной строки
$week_set = $_GET['week_set'] ? $_GET['week_set'] : 'now';

if ($id_user == false) {
    header('Location:' . $site);
}

include_once('block/header.php');
include_once('block/setting.php');
include_once('function/function.php');
include_once('block/connect_db.php');


//Запрос ДНЧ
$query_user = $pdo->query("SELECT * FROM `user_control` JOIN dnch ON user_control.id_dnch = dnch.id_dnch WHERE `id_user` = $id_user");
$row_user = $query_user->fetch(PDO::FETCH_OBJ);
$id_dnch = $row_user->id_dnch;

echo "IDDNCH = $id_dnch";

//Выборка станций
$query_station = $pdo->query("SELECT `id_station`, `station` FROM `stations` WHERE `id_dnch` = $row_user->id_dnch");
$query_list = $pdo->query('SELECT * FROM `lists`');

//выбираем данные за неделю по ДНЧ
$query_control = $pdo->query("SELECT * FROM controls JOIN dnch ON controls.id_dnch = dnch.id_dnch WHERE dnch.id_dnch=$id_dnch");

$month_item = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];
$error = '';

switch ($week_set) {
    case 'last':
        /*Предыдущая неделя */
        $today = new DateTime();
        $lastMonday = clone $today;
        $lastMonday->modify('monday this week');
        $lastMonday->modify('-9 day');

        $monday = $lastMonday->format('Y-m-d');  //получаем номер недели
        $weekNumber = getWeekNumber($monday);

        $days_arr = [];
        for ($i = 0; $i < 7; $i++) {
            $days_arr[] = [$lastMonday->format('d.m.Y')];
            $lastMonday->modify('+1 day');
        }
        /*------*/
        break;
    case 'next':
        /*следующая неделя */
        $today = new DateTime();
        $nextMonday = clone $today;
        $nextMonday->modify('next monday');

        $monday = $nextMonday->format('Y-m-d');  //получаем номер недели
        $weekNumber = getWeekNumber($monday);

        $days_arr = [];
        for ($i = 0; $i < 7; $i++) {
            $days_arr[] = [$nextMonday->format('d.m.Y')];
            $nextMonday->modify('+1 day');
        }
        /*------*/
        break;

    case 'now':
        /*текущая неделя */
        $today = new DateTime();
        $start = clone $today;
        $start->modify('monday this week');

        // Пример использования:
        $monday = $start->format('Y-m-d');
        $weekNumber = getWeekNumber($monday);

        $days_arr = [];
        for ($i = 0; $i < 7; $i++) {
            $days_arr[] = [$start->format('d.m.Y')];
            $start->modify('+1 day');
        }
        /*------*/
        break;
    default:
        $error = 'Что то пошло не так';
        break;
}

if (empty($weekNumber)) {
    echo 'Ошибка выбора недели';
    exit();
}

$query_day_week = $pdo->query("SELECT controls.*, 
    s1.station AS station_1_mon,
    s2.station AS station_2_mon,
    s3.station AS station_1_tue,
    s4.station AS station_2_tue,
    s5.station AS station_1_wed,
    s6.station AS station_2_wed,
    s7.station AS station_1_thu,
    s8.station AS station_2_thu,
    s9.station AS station_1_fri,
    s10.station AS station_2_fri,
    l1.event AS event_1_mon,
    l2.event AS event_2_mon,
    l3.event AS event_1_tue,
    l4.event AS event_2_tue,
    l5.event AS event_1_wed,
    l6.event AS event_2_wed,
    l7.event AS event_1_thu,
    l8.event AS event_2_thu,
    l9.event AS event_1_fri,
    l10.event AS event_2_fri,
    r.dnch_name AS dnch


FROM controls
LEFT JOIN dnch AS r ON controls.id_dnch = r.id_dnch
LEFT JOIN stations AS s1 ON controls.object_1_mon = s1.id_station
LEFT JOIN stations AS s2 ON controls.object_2_mon = s2.id_station
LEFT JOIN stations AS s3 ON controls.object_1_tue = s3.id_station
LEFT JOIN stations AS s4 ON controls.object_2_tue = s4.id_station
LEFT JOIN stations AS s5 ON controls.object_1_wed = s5.id_station
LEFT JOIN stations AS s6 ON controls.object_2_wed = s6.id_station
LEFT JOIN stations AS s7 ON controls.object_1_thu = s7.id_station
LEFT JOIN stations AS s8 ON controls.object_2_thu = s8.id_station
LEFT JOIN stations AS s9 ON controls.object_1_fri = s9.id_station
LEFT JOIN stations AS s10 ON controls.object_2_fri = s10.id_station
LEFT JOIN lists AS l1 ON controls.object_1_mon_type = l1.id_list
LEFT JOIN lists AS l2 ON controls.object_2_mon_type = l2.id_list
LEFT JOIN lists AS l3 ON controls.object_1_tue_type = l3.id_list
LEFT JOIN lists AS l4 ON controls.object_2_tue_type = l4.id_list
LEFT JOIN lists AS l5 ON controls.object_1_wed_type = l5.id_list
LEFT JOIN lists AS l6 ON controls.object_2_wed_type = l6.id_list
LEFT JOIN lists AS l7 ON controls.object_1_thu_type = l7.id_list
LEFT JOIN lists AS l8 ON controls.object_2_thu_type = l8.id_list
LEFT JOIN lists AS l9 ON controls.object_1_fri_type = l9.id_list
LEFT JOIN lists AS l10 ON controls.object_2_fri_type = l10.id_list


WHERE controls.id_dnch = $id_dnch AND `weekNumber` = $weekNumber");

$error = '';

if ($query_day_week->rowCount() == 0) {
    $error = 'Нет данных за выбранную неделю!';
}

echo $error;

?>

<body class="wrapper">
    <div id="preloader">Добро пожаловать!</div>

    <main class="main">
        <div class="container">
            <header class="header">
                <?php include_once('block/nav_dnch.php'); ?>
            </header>
            <div class="main container">
                <div class="heading">
                    <h1 class="text-center">Управление процессом деятельности </br> Инструктора по безопасности <?= $row_user->dnch_name ?></h1>
                </div>


                <div class="workspace">
                    <div class="workspace_header d-flex justify-content-lg-around">
                        <div class="workspace_header_choice">
                            <div class="header_choice d-flex flex-md-column py-5">

                                <div class="choice_week my-5">
                                    <span>Выберите неделю для просмотра: </span>
                                    <input type="week" name="numberWeek" id="numberWeek">
                                </div>

                            </div>
                        </div>
                        <div class="workspace_header_calendar">
                            <?= Calendar::getMonth(date('n'), date('Y')); ?>
                        </div>
                    </div>
                </div>

                <div class="table_week">
                    <table class="table table-bordered table-striped">
                        <colgroup>
                            <col style="width: 70px">
                            <col style="width: 150px">
                            <col style="width: 150px">
                            <col style="width: 150px">
                            <col style="width: 150px">
                            <col style="width: 150px">
                        </colgroup>
                        <thead>
                            <tr class="text-center">
                                <th>ДНЧ</th>
                                <th>пн <?= $days_arr[0][0] ?></th>
                                <th>вт <?= $days_arr[1][0] ?></th>
                                <th>ср <?= $days_arr[2][0] ?></th>
                                <th>чт <?= $days_arr[3][0] ?></th>
                                <th>пт <?= $days_arr[4][0] ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $results = $query_day_week->fetchAll(PDO::FETCH_OBJ);

                            if (empty($results)) {
                                echo "<tr><th colspan='6'>За выбранную неделю данные отсутствуют!</th></tr>";
                            } else {
                                foreach ($results as $row) {
                                    echo "<tr>";
                                    echo "<th class='text-center align-content-center'>{$row->dnch}</th>";

                                    // Понедельник
                                    echo "<th><div class='events d-flex flex-column text-center'>";
                                    echo "<div class='b_under row_change d-flex row'><div class='text-one-day station_1'>{$row->station_1_mon}</div></div>";
                                    echo "<div class='b_under text-one-day event_1'>{$row->event_1_mon}</div>";
                                    echo "<div class='b_under row_change d-flex row'><div class='text-secondary station_2'>{$row->station_2_mon}</div></div>";
                                    echo "<div class='b_under text-secondary event_2'>{$row->event_2_mon}</div>";
                                    echo ($row->checkbox_mon == 1) ? "<div class='b_under bg-green'>по плану</div>" : "<div class='b_under bg-red'>не по плану</div>";
                                    echo "<div class='fw-lighter fs-6'>{$row->coments_mon}</div>";
                                    echo "</div></th>";

                                    // Вторник
                                    echo "<th><div class='events d-flex flex-column text-center'>";
                                    echo "<div class='b_under text-one-day station_1 d-flex row'><div class='text-secondary station_1'>{$row->station_1_tue}</div></div>";
                                    echo "<div class='b_under text-one-day event_1'>{$row->event_1_tue}</div>";
                                    echo "<div class='b_under text-secondary station_2 d-flex row'><div class='text-secondary station_2'>{$row->station_2_tue}</div></div>";
                                    echo "<div class='b_under text-secondary event_2'>{$row->event_2_tue}</div>";
                                    echo ($row->checkbox_tue == 1) ? "<div class='b_under bg-green'>по плану</div>" : "<div class='b_under bg-red'>не по плану</div>";
                                    echo "<div class='fw-lighter fs-6'>{$row->coments_tue}</div>";
                                    echo "</div></th>";

                                    // Среда
                                    echo "<th><div class='events d-flex flex-column text-center'>";
                                    echo "<div class='b_under text-primary station_1 d-flex row'><div class='text-secondary station_1'>{$row->station_1_wed}</div></div>";
                                    echo "<div class='b_under text-primary event_1'>{$row->event_1_wed}</div>";
                                    echo "<div class='b_under text-secondary station_2 d-flex row'><div class='text-secondary station_2'>{$row->station_2_wed}</div></div>";
                                    echo "<div class='b_under text-secondary event_2'>{$row->event_2_wed}</div>";
                                    echo ($row->checkbox_wed == 1) ? "<div class='b_under bg-green'>по плану</div>" : "<div class='b_under bg-red'>не по плану</div>";
                                    echo "<div class='fw-lighter fs-6'>{$row->coments_wed}</div>";
                                    echo "</div></th>";

                                    // Четверг
                                    echo "<th><div class='events d-flex flex-column text-center'>";
                                    echo "<div class='b_under text-primary station_1 d-flex row'><div class='text-secondary station_1'>{$row->station_1_thu}</div></div>";
                                    echo "<div class='b_under text-primary event_1'>{$row->event_1_thu}</div>";
                                    echo "<div class='b_under text-secondary station_2 d-flex row'><div class='text-secondary station_2'>{$row->station_2_thu}</div></div>";
                                    echo "<div class='b_under text-secondary event_2'>{$row->event_2_thu}</div>";
                                    echo ($row->checkbox_thu == 1) ? "<div class='b_under bg-green'>по плану</div>" : "<div class='b_under bg-red'>не по плану</div>";
                                    echo "<div class='fw-lighter fs-6'>{$row->coments_thu}</div>";
                                    echo "</div></th>";

                                    // Пятница
                                    echo "<th><div class='events d-flex flex-column text-center'>";
                                    echo "<div class='b_under text-primary station_1 d-flex row'><div class='text-secondary station_1'>{$row->station_1_fri}</div></div>";
                                    echo "<div class='b_under text-primary event_1'>{$row->event_1_fri}</div>";
                                    echo "<div class='b_under text-secondary station_2 d-flex row'><div class='text-secondary station_2'>{$row->station_2_fri}</div></div>";
                                    echo "<div class='b_under text-secondary event_2'>{$row->event_2_fri}</div>";
                                    echo ($row->checkbox_fri == 1) ? "<div class='b_under bg-green'>по плану</div>" : "<div class='b_under bg-red'>не по плану</div>";
                                    echo "<div class='fw-lighter fs-6'>{$row->coments_fri}</div>";
                                    echo "</div></th>";

                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


            </div>

    </main>

    <?php include_once('block/footer.php'); ?>

    <link rel="stylesheet" href="css/chosen.min.css">
    <?php include_once("block/footer.php"); ?>

    <script>
        $(function() {
            $('#numberWeek').change(function() {
                var week = $(this).val();
                var hrf = 'control_edit.php?week_set=now&weekNumber=' + week;
                location.replace(hrf);
            });

        });
    </script>

</body>

</html>