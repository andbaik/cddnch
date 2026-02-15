<?php
$title = 'Недельное планирование';
$week_set = !empty($_GET['week_set']) ? $_GET['week_set'] : 'now';

include_once('block/header.php');
include_once('block/setting.php');
include_once('function/function.php');
include_once('block/connect_db.php');

if ($id_user == false) {
    header('Location:' . $site);
} elseif ($level !== 3) {
    header('Location:' . $site);
    exit;
}

switch ($level) {
    case '3':
        $name_admin = 'Администратор ДЦС';
        break;
    case '2':
        $name_admin = 'Администратор Д';
        break;
    case '1':
        $name_admin = 'Администратор ЦД';
        break;
    default:
        echo "Error";
        break;
}

switch ($week_set) {
    case 'last':
        /*Предыдущая неделя */
        $today = new DateTime();
        $lastMonday = clone $today;
        $lastMonday->modify('monday this week');
        $lastMonday->modify('-9 day');
        $monday = $lastMonday->format('Y-m-d');  //получаем номер недели
        $weekNumber = getWeekNumber($monday);
        for ($i = 0; $i < 7; $i++) {
            $days_arr[] = [$lastMonday->format('d.m.Y')];
            $lastMonday->modify('+1 day');
        }
        break;
    case 'next':
        /*следующая неделя */
        $today = new DateTime();
        $nextMonday = clone $today;
        $nextMonday->modify('next monday');
        $monday = $nextMonday->format('Y-m-d');  //получаем номер недели
        $weekNumber = getWeekNumber($monday);
        for ($i = 0; $i < 7; $i++) {
            $days_arr[] = [$nextMonday->format('d.m.Y')];
            $nextMonday->modify('+1 day');
        }
        break;

    case 'now':
        /*текущая неделя */
        $today = new DateTime();
        $start = clone $today;
        $start->modify('monday this week');
        $monday = $start->format('Y-m-d');
        $weekNumber = getWeekNumber($monday);
        $days_arr = [];
        for ($i = 0; $i < 7; $i++) {
            $days_arr[] = [$start->format('d.m.Y')];
            $start->modify('+1 day');
        }
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


WHERE `weekNumber` = $weekNumber AND controls.id_dnch IN (SELECT id_dnch FROM dnch WHERE id_dcs= $id_dcs)");


?>


<body class="wrapper">

    <!--Модальное окно отсутствие ДНЧ-->

    <div class="modal fade " id="modalNotCheck" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalLabel">Отметить отсутствие ДНЧ</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="not_chek_save" action="multipart/form-data">
                        <div id='textModal'>
                            <label for="not_check">Введите причину отсутствия:</label>
                            <input type="text" class="form-control mt-3" id="not_check" name="not_check">
                        </div>
                        <div class="textModalHidden" id="textModalHiddenDnch"> ДНЧ</div>
                        <div class="textModalHidden" id="textModalHiddenWeek"> Неделя </div>
                        <div class="textModalHidden" id="textModalHiddenDay"> День недели </div>
                        <div class="info">
                            <div class="error-mess text-center" id="error-block"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                            <button type="submit" class="btn btn-primary" id="not_chek_save">Сохранить изменения</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="preloader">Добро пожаловать!</div>

    <main class="main">
        <div class="container">
            <header class="header">
                <?php include_once('block/nav_dcsrb.php'); ?>
            </header>
            <div class="heading">
                <h1 class="text-center">Страница админитстрирования <?= $name_admin ?></h1>
            </div>

            <div class="workspace">
                <div class="workspace_header d-flex justify-content-lg-around">
                    <div class="workspace_header_choice">
                        <div class="header_choice d-flex flex-md-column py-5">

                            <div class="choice_week my-5">
                                <span>Выберите неделю для редактирования: </span>
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

                    <?php
                    $html = "";
                    if (!$query_day_week) {
                        $html = "
                                <tbody>
                                    <th colspan='6'> За выбранную неделю данные отсутствуют!</th>
                                </tbody>
                            ";
                    } else {
                        while ($row_day_week = $query_day_week->fetch(PDO::FETCH_OBJ)) {
                            $html .= "
                                <th class=\"text-center align-content-center\">
                                    $row_day_week->dnch
                                    '<br>'
                                    $row_day_week->id_control
                                </th>
                                <th>
                                    <div class=\"events d-flex flex-column text-center\">
                                        <div class=\"b_under row_change d-flex row\">
                                            <div class=\"col-9 row_left text-one-day station_1\">
                                                $row_day_week->station_1_mon
                                            </div>";

                            if ($row_day_week->check_dcsrb_mon_1 == NULL) {
                                $html .= "
                                            <a class=\"col-1 row_right text-one-day link\" data-bs-toggle=\"modal\" data-bs-target=\"#modalNotCheck\" data-idweek={$weekNumber} data-iddnch={$row_day_week->id_dnch} data-day=\"1_obj_mon\" href=\"#\"><i class=\"fa-solid fa-xmark\"></i></a>
                                            <div class=\"col-1 row_right text-one-day \"><a href=\"#\"><i class=\"fa-solid fa-check\"></i></a></div>";
                            } elseif ($row_day_week->check_dcsrb_mon_1 == 0) {
                                $html .= "
                                            <div class=\"col-1 row_right text-one-day \"><i style=\"color:red\" class=\"fa-regular fa-circle-xmark\"></i></div>
                                                ";
                            } else {
                                $html .= "
                                            <div class=\"col-1 row_right text-one-day \"><i style=\"color:green\" class=\"fa-regular fa-circle-check\"></i></div>
                                                ";
                            }

                            $html .= "
                                        </div>
                                        <div class=\"b_under text-one-day event_1\">
                                            $row_day_week->event_1_mon
                                        </div>
                                        <div class=\"b_under row_change d-flex row\">
                                            <div class=\"col-9 row_left text-secondary  station_2\">
                                                $row_day_week->station_2_mon
                                            </div>
                                            ";

                            if ($row_day_week->check_dcsrb_mon_2 == NULL) {
                                $html .= "
                                            <a class=\"col-1 row_right text-one-day link\" data-bs-toggle=\"modal\" data-bs-target=\"#modalNotCheck\" data-idweek={$weekNumber} data-iddnch={$row_day_week->id_dnch} data-day=\"2_obj_mon\" href=\"#\"><i class=\"fa-solid fa-xmark\"></i></a>
                                            <div class=\"col-1 row_right text-one-day \"><a href=\"#\"><i class=\"fa-solid fa-check\"></i></a></div>";
                            } elseif ($row_day_week->check_dcsrb_mon_2 == 0) {
                                $html .= "
                                            <div class=\"col-1 row_right text-one-day \">bed</div>
                                                ";
                            } elseif ($row_day_week->check_dcsrb_mon_2 == 1) {
                                $html .= "
                                            <div class=\"col-1 row_right text-one-day \">OK</div>
                                                ";
                            }

                            $html .= "
                                        </div>
                                        <div class=\"b_under text-secondary event_2\">
                                            $row_day_week->event_2_mon
                                        </div>";
                            if ($row_day_week->checkbox_mon == 1) {
                                $html .= "<div class=\"b_under bg-green\">по плану</div>";
                            } else {
                                $html .= "<div class=\"b_under bg-red\">не по плану</div>";
                            };

                            $html .= "
                                        <div class=\"fw-lighter fs-6\">
                                            $row_day_week->coments_mon
                                        </div>
                                    </div>
                                </th>
                            <th>
                                    <div class=\"events d-flex flex-column text-center\">
                                        <div class=\"b_under text-one-day station_1 d-flex row\">
                                            <div class=\"col-9 row_left text-secondary station_1\">
                                                $row_day_week->station_1_tue
                                            </div>
                                            <a class=\"col-1 row_right text-one-day link\" data-bs-toggle=\"modal\" data-bs-target=\"#modalNotCheck\" data-idweek={$weekNumber} data-iddnch={$row_day_week->id_dnch} data-day=\"1_obj_tue\" href=\"#\"><i class=\"fa-solid fa-xmark\"></i></a>
                                            <div class=\"col-1 row_right text-one-day \"><a href=\"#\"><i class=\"fa-solid fa-check\"></i></a></div>
                                        </div>

                                        <div class=\"b_under text-one-day event_1\">
                                            $row_day_week->event_1_tue
                                        </div>
                                        <div class=\"b_under text-secondary station_2 d-flex row\">
                                            <div class=\"col-9 row_left text-secondary station_2\">
                                                $row_day_week->station_2_tue
                                            </div>
                                            <a class=\"col-1 row_right text-one-day link\" data-bs-toggle=\"modal\" data-bs-target=\"#modalNotCheck\" data-idweek={$weekNumber} data-iddnch={$row_day_week->id_dnch} data-day=\"2_obj_tue\" href=\"#\"><i class=\"fa-solid fa-xmark\"></i></a>
                                            <div class=\"col-1 row_right text-one-day \"><a href=\"#\"><i class=\"fa-solid fa-check\"></i></a></div>

                                        </div>
                                        <div class=\"b_under text-secondary event_2\">
                                            $row_day_week->event_2_tue
                                        </div>";
                            if ($row_day_week->checkbox_tue == 1) {
                                $html .= "<div class=\"b_under bg-green\">по плану</div>";
                            } else {
                                $html .= "<div class=\"b_under bg-red\">не по плану</div>";
                            };

                            $html .= "
                                        <div class=\"fw-lighter fs-6\">
                                            $row_day_week->coments_tue
                                        </div>
                                    </div>
                                </th>
                                <th>
                                    <div class=\"events d-flex flex-column text-center\">
                                        <div class=\"b_under text-primary station_1 d-flex row\">
                                            <div class=\"col-9 row_left text-secondary station_1\">
                                                $row_day_week->station_1_wed
                                            </div>
                                            <a class=\"col-1 row_right text-one-day link\" data-bs-toggle=\"modal\" data-bs-target=\"#modalNotCheck\" data-idweek={$weekNumber} data-iddnch={$row_day_week->id_dnch} data-day=\"1_obj_wed\" href=\"#\"><i class=\"fa-solid fa-xmark\"></i></a>
                                            <div class=\"col-1 row_right text-one-day \"><a href=\"#\"><i class=\"fa-solid fa-check\"></i></a></div>    
                                        </div>
                                        <div class=\"b_under text-primary event_1\">
                                            $row_day_week->event_1_wed
                                        </div>
                                        <div class=\"b_under text-secondary station_2 d-flex row\">
                                            <div class=\"col-9 row_left text-secondary station_2\">
                                                $row_day_week->station_2_wed
                                            </div>
                                            <a class=\"col-1 row_right text-one-day link\" data-bs-toggle=\"modal\" data-bs-target=\"#modalNotCheck\" data-idweek={$weekNumber} data-iddnch={$row_day_week->id_dnch} data-day=\"2_obj_wed\" href=\"#\"><i class=\"fa-solid fa-xmark\"></i></a>
                                            <div class=\"col-1 row_right text-one-day \"><a href=\"#\"><i class=\"fa-solid fa-check\"></i></a></div>
                                            
                                        </div>
                                        <div class=\"b_under text-secondary event_2\">
                                            $row_day_week->event_2_wed
                                        </div>";

                            if ($row_day_week->checkbox_wed == 1) {
                                $html .= "<div class=\"b_under bg-green\">по плану</div>";
                            } else {
                                $html .= "<div class=\"b_under bg-red\">не по плану</div>";
                            };

                            $html .= "
                                        <div class=\"fw-lighter fs-6\">
                                            $row_day_week->coments_wed
                                        </div>
                                    </div>
                                </th>
                                <th>
                                    <div class=\"events d-flex flex-column text-center\">
                                        <div class=\"b_under text-primary station_1 d-flex row\">
                                            <div class=\"col-9 row_left text-secondary station_1\">
                                                $row_day_week->station_1_thu
                                            </div>
                                            <a class=\"col-1 row_right text-one-day link\" data-bs-toggle=\"modal\" data-bs-target=\"#modalNotCheck\" data-idweek={$weekNumber} data-iddnch={$row_day_week->id_dnch} data-day=\"1_obj_thu\" href=\"#\"><i class=\"fa-solid fa-xmark\"></i></a>
                                            <div class=\"col-1 row_right text-one-day \"><a href=\"#\"><i class=\"fa-solid fa-check\"></i></a></div>
                                        </div>
                                        <div class=\"b_under text-primary event_1\">
                                            $row_day_week->event_1_thu
                                        </div>
                                        <div class=\"b_under text-secondary station_2 d-flex row\">
                                            <div class=\"col-9 row_left text-secondary station_2\">
                                                $row_day_week->station_2_thu
                                            </div>
                                            <a class=\"col-1 row_right text-one-day link\" data-bs-toggle=\"modal\" data-bs-target=\"#modalNotCheck\" data-idweek={$weekNumber} data-iddnch={$row_day_week->id_dnch} data-day=\"2_obj_thu\" href=\"#\"><i class=\"fa-solid fa-xmark\"></i></a>
                                            <div class=\"col-1 row_right text-one-day \"><a href=\"#\"><i class=\"fa-solid fa-check\"></i></a></div>

                                        </div>
                                        <div class=\"b_under text-secondary event_2\">
                                            $row_day_week->event_2_thu
                                        </div>";
                            if ($row_day_week->checkbox_thu == 1) {
                                $html .= "<div class=\"b_under bg-green\">по плану</div>";
                            } else {
                                $html .= "<div class=\"b_under bg-red\">не по плану</div>";
                            };

                            $html .= "
                                        <div class=\"fw-lighter fs-6\">
                                            $row_day_week->coments_thu
                                        </div>
                                    </div>
                                </th>
                                <th>
                                    <div class=\"events d-flex flex-column text-center\">
                                        <div class=\"b_under text-primary station_1 d-flex row\">
                                            <div class=\"col-9 row_left text-secondary station_1\">
                                                $row_day_week->station_1_fri
                                            </div>
                                            <a class=\"col-1 row_right text-one-day link\" data-bs-toggle=\"modal\" data-bs-target=\"#modalNotCheck\" data-idweek={$weekNumber} data-iddnch={$row_day_week->id_dnch} data-day=\"1_obj_fri\" href=\"#\"><i class=\"fa-solid fa-xmark\"></i></a>
                                            <div class=\"col-1 row_right text-one-day \"><a href=\"#\"><i class=\"fa-solid fa-check\"></i></a></div>
                                        </div>
                                        <div class=\"b_under text-primary event_1\">
                                            $row_day_week->event_1_fri
                                        </div>
                                        <div class=\"b_under text-secondary station_2 d-flex row\">
                                            <div class=\"col-9 row_left text-secondary station_2\">
                                                $row_day_week->station_2_fri
                                            </div>
                                            <a class=\"col-1 row_right text-one-day link\" data-bs-toggle=\"modal\" data-bs-target=\"#modalNotCheck\" data-idweek={$weekNumber} data-iddnch={$row_day_week->id_dnch} data-day=\"2_obj_fri\" href=\"#\"><i class=\"fa-solid fa-xmark\"></i></a>
                                            <div class=\"col-1 row_right text-one-day \"><a href=\"#\"><i class=\"fa-solid fa-check\"></i></a></div>
                                        </div>
                                        <div class=\"b_under text-secondary event_2\">
                                            $row_day_week->event_2_fri
                                        </div>";
                            if ($row_day_week->checkbox_fri == 1) {
                                $html .= "<div class=\"b_under bg-green\">по плану</div>";
                            } else {
                                $html .= "<div class=\"b_under bg-red\">не по плану</div>";
                            };

                            $html .= "
                                        <div class=\"fw-lighter fs-6\">
                                            $row_day_week->coments_fri
                                        </div>
                                    </div>
                                </th>
                            </tr>


                            ";
                        }
                    };

                    echo '<tbody>';
                    echo $html;
                    echo '</tbody>';
                    ?>


                </table>

            </div>

    </main>
    <link rel="stylesheet" href="css/chosen.min.css">
    <?php include_once("block/footer.php"); ?>


    <script>
        //Обработка модального окна не присутствовал
        document.querySelectorAll('.link').forEach(link =>
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const id_dnch = this.dataset.iddnch;
                const id_week = this.dataset.idweek;
                const day = this.dataset.day;
                document.getElementById('textModalHiddenDnch').innerHTML = '<input type="text"  name="val_dnch" id="val_dnch"  value=' + id_dnch + ' hidden>';
                document.getElementById('textModalHiddenWeek').innerHTML = '<input type="text"  name="val_week" id="val_week"  value=' + id_week + ' hidden>';
                document.getElementById('textModalHiddenDay').innerHTML = '<input type="text"  name="val_day" id="val_day"  value=' + day + ' hidden>';
            })
        )

        $("form#not_chek_save").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: 'ajax/add_chek_not.php',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data == 1) {
                        $("#not_chek_save").text("Выполнено");
                        $("#error-block").hide();
                        alert('Отметка о непосещении записана!');
                        //window.location.replace("http://dnch.loc/control_dcsrb.php");
                        document.location.reload(true);
                        exit;
                    } else {
                        $("#error-block").show();
                        $("#error-block").text(data);
                    }
                }
            });
        });


        $(function() {
            $('#numberWeek').change(function() {
                var week = $(this).val();
                var hrf = 'control_dcsrb.php?week_set=now&weekNumber=' + week;
                location.replace(hrf);
            });

        });
    </script>

</body>

</html>