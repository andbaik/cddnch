<?php


$title = 'Редактирование данных';

$week_set = !empty($_GET['week_set']) ? $_GET['week_set'] : 'now';
$week_edit = !empty($_GET['weekNumber']) ? $_GET['weekNumber'] : NULL;

include_once('block/header.php');
include_once('block/setting.php');
include_once('function/function.php');
include_once('block/connect_db.php');

if ($level !== 4) {
    header('Location:' . $site);
    exit;
}


//Запрос ДНЧ
$query_user = $pdo->query("SELECT * FROM `user_control` JOIN dnch ON user_control.id_dnch = dnch.id_dnch WHERE `id_user` = $id_user");
$row_user = $query_user->fetch(PDO::FETCH_OBJ);
$id_dnch = $row_user->id_dnch;


$query_station = $pdo->query("SELECT `id_station`, `station` FROM `stations` WHERE `id_dnch` = $id_dnch");
$query_list = $pdo->query('SELECT * FROM `lists`');

$day_n = date('d-m-Y');

$day = DateClass::get_day($day_n);
$month = DateClass::get_month($day_n);
$year = DateClass::get_year($day_n);


switch ($week_set) {
    case 'last':
        /*Предыдущая неделя */
        $today = new DateTime();
        $lastMonday = clone $today;
        $lastMonday->modify('monday this week');
        $lastMonday->modify('-9 day');
        $monday = $lastMonday->format('Y-m-d');
        $weekNumber = getWeekNumber($monday);

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

        $monday = $nextMonday->format('Y-m-d');
        $weekNumber = getWeekNumber($monday);

        for ($i = 0; $i < 7; $i++) {
            $days_arr[] = [$nextMonday->format('d.m.Y')];
            $nextMonday->modify('+1 day');
        }


        /*------*/
        break;

    case 'now':
        /*текущая неделя */

        if ($week_edit !== NULL) {
            $today = new DateTime($week_edit);
        } else {
            $today = new DateTime();
        }
        $start = clone $today;
        $start->modify('monday this week');

        // Пример использования:
        $monday = $start->format('Y-m-d');
        $weekNumber = getWeekNumber($monday);

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

$row_day_week = $query_day_week->fetch(PDO::FETCH_OBJ);

?>

<body class="wrapper">
    <div id="preloader">Добро пожаловать!</div>
    <?php include_once('block/nav_dnch_edit.php'); ?>

    <main class="main">
        <div class="container">
            <div class="heading">
                <h1 class="text-center">Управление процессом деятельности <br> Инструктора по безопасности <?= $row_user->dnch_name ?></h1>
            </div>


            <form action="#" method="POST" id="add_events" action="multipart/form-data>
                <div class=" workspace">
                <div class="workspace_header d-flex justify-content-lg-around">
                    <div class="workspace_header_choice">
                        <div class="header_choice d-flex flex-md-column py-5">

                            <div class="choice_week my-5">
                                <span>Выберите неделю для редактирования:</span>
                                <input type="week" name="numberWeek" id="numberWeek">
                            </div>
                        </div>
                    </div>
                    <div class="workspace_header_calendar">
                        <?= Calendar::getMonth(date('n'), date('Y')); ?>
                    </div>
                </div>
                <div class="d-flex justify-content-lg-center my-1">
                    <?php
                    if (!empty($error)) {
                        echo $error;
                    } ?>
                </div>
                <div class="workspace_week d-flex justify-content-md-between">
                    <div class="week_day mon">
                        <div class="day_header">
                            <span>Понедельник <?= $days_arr[0][0] ?></span>
                        </div>
                        <div class="day_choice d-md-flex flex-column">
                            <div class="choice_object d-flex flex-column">
                                <span>Выбрать объект:</span>
                                <select class="js-chosen" name="object_1_mon" id="object_1_mon"
                                    placeholder="Выберите станцию">

                                    <?php
                                    if (!empty($row_day_week->object_1_mon)) {
                                        echo "<option value=\"$row_day_week->object_1_mon \">$row_day_week->station_1_mon</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    }
                                    ?>

                                    <?php
                                    while ($row_station = $query_station->fetch(PDO::FETCH_OBJ)) {
                                        $html1 .= "<option value={$row_station->id_station}> {$row_station->station}</option>";
                                    };
                                    echo $html1;
                                    ?>
                                </select>
                            </div>
                            <div class="obj1_type d-flex flex-column">
                                <span>Выбрать вид КМ:</span>
                                <select class="js-chosen" name="object_1_mon_type" id="object_1_mon_type"
                                    placeholder="Выберите вид КМ">

                                    <?php
                                    if (!empty($row_day_week->object_1_mon_type)) {
                                        echo "<option value=\"$row_day_week->object_1_mon_type\">$row_day_week->event_1_mon</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    }
                                    ?>

                                    <?php
                                    while ($row_list = $query_list->fetch(PDO::FETCH_OBJ)) {
                                        $html2 .= "<option value={$row_list->id_list}> {$row_list->event}</option>";
                                    };
                                    echo $html2;
                                    ?>
                                </select>
                            </div>
                            <div class="object_2_mon d-flex flex-column">
                                <span>Выбрать еще станцию:</span>
                                <select class="js-chosen" name="object_2_mon" id="object_2_mon"
                                    placeholder="Выберите станцию">

                                    <?php
                                    if (!empty($row_day_week->object_2_mon)) {
                                        echo "<option value=\"$row_day_week->object_2_mon\">$row_day_week->station_2_mon</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    }
                                    ?>

                                    <?= $html1 ?>
                                </select>
                            </div>
                            <div class="obj2_type d-flex flex-column">
                                <span>Выбрать вид КМ:</span>
                                <select class="js-chosen" name="object_2_mon_type" id="object_2_mon_type"
                                    placeholder="Выберите вид КМ">
                                    <?php
                                    if (!empty($row_day_week->object_2_mon_type)) {
                                        echo "<option value=\"$row_day_week->object_2_mon_type\">$row_day_week->event_2_mon</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    }
                                    ?>


                                    <?= $html2 ?>
                                </select>
                            </div>
                            <div class="checkbox_plan">
                                <label class="toggle">
                                    <span class="toggle-lable">Согласно плану</span>
                                    <input class="toggle-checkbox" type="checkbox" name="checkbox_mon"
                                        id="checkbox_mon"
                                        <?php
                                        if (!empty($row_day_week->checkbox_mon)) {
                                            if ($row_day_week->checkbox_mon == 1) {
                                                echo "checked=cheked";
                                            }
                                        } else {
                                            echo "";
                                        }
                                        ?>>
                                    <div class="toggle-switch"></div>

                                </label>
                            </div>
                            <div class="coments d-flex flex-column">
                                <span>Коментарий</span>
                                <textarea name="coments_mon" id="coments_mon" cols="10" rows="5"><?= !empty($row_day_week->coments_mon) ? $row_day_week->coments_mon : "" ?></textarea>
                            </div>

                        </div>

                    </div>
                    <div class="week_day tue">
                        <div class="day_header">
                            <span>Вторник <?= $days_arr[1][0] ?></span>
                        </div>
                        <div class="day_choice d-md-flex flex-column">
                            <div class="choice_object d-flex flex-column">
                                <span>Выбрать объект:</span>
                                <select class="js-chosen" name="object_1_tue" id="object_1_tue"
                                    placeholder="Выберите станцию">
                                    <?php
                                    if (!empty($row_day_week->object_1_tue)) {
                                        echo "<option value=\"$row_day_week->object_1_tue\"> $row_day_week->station_1_tue</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>

                                    <?= $html1 ?>
                                </select>
                            </div>
                            <div class="obj1_type d-flex flex-column">
                                <span>Выбрать вид КМ:</span>
                                <select class="js-chosen" name="object_1_tue_type" id="object_1_tue_type"
                                    placeholder="Выберите вид КМ">
                                    <?php
                                    if (!empty($row_day_week->object_1_tue_type)) {
                                        echo "<option value=\"$row_day_week->object_1_tue_type\">$row_day_week->event_1_tue</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>

                                    <?= $html2 ?>
                                    <option value="1">Общая проверка</option>
                                </select>
                            </div>
                            <div class="object_2_tue d-flex flex-column">
                                <span>Выбрать еще станцию:</span>
                                <select class="js-chosen" name="object_2_tue" id="object_2_tue"
                                    placeholder="Выберите станцию">
                                    <?php
                                    if (!empty($row_day_week->object_2_tue)) {
                                        echo " <option value=\"$row_day_week->object_2_tue \">$row_day_week->station_2_tue </option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>

                                    <?= $html1 ?>
                                </select>
                            </div>
                            <div class="obj2_type d-flex flex-column">
                                <span>Выбрать вид КМ:</span>
                                <select class="js-chosen" name="object_2_tue_type" id="object_2_tue_type"
                                    placeholder="Выберите вид КМ">
                                    <?php
                                    if (!empty($row_day_week->object_2_tue_type)) {
                                        echo " <option value=\"$row_day_week->object_2_tue_type\">$row_day_week->event_2_tue</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>


                                    <?= $html2 ?>
                                </select>
                            </div>
                            <div class="checkbox_plan">
                                <label class="toggle">
                                    <span class="toggle-lable">Согласно плану</span>
                                    <input class="toggle-checkbox" type="checkbox" name="checkbox_tue"
                                        id="checkbox_tue"
                                        <?php
                                        if (!empty($row_day_week->checkbox_tue)) {
                                            if ($row_day_week->checkbox_tue == 1) {
                                                echo "checked=cheked";
                                            }
                                        } else {
                                            echo "";
                                        }
                                        ?>>
                                    <div class="toggle-switch"></div>

                                </label>
                            </div>
                            <div class="coments d-flex flex-column">
                                <span>Коментарий</span>
                                <textarea name="coments_tue" id="coments_tue" cols="10" rows="5"><?= !empty($row_day_week->coments_tue) ? $row_day_week->coments_tue : "" ?></textarea>
                            </div>

                        </div>

                    </div>
                    <div class="week_day wed">
                        <div class="day_header">
                            <span>Среда <?= $days_arr[2][0] ?></span>
                        </div>
                        <div class="day_choice d-md-flex flex-column">
                            <div class="choice_object d-flex flex-column">
                                <span>Выбрать объект:</span>
                                <select class="js-chosen" name="object_1_wed" id="object_1_wed"
                                    placeholder="Выберите станцию">
                                    <?php
                                    if (!empty($row_day_week->object_1_wed)) {
                                        echo "<option value=\"$row_day_week->object_1_wed\">$row_day_week->station_1_wed </option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>


                                    <?= $html1 ?>
                                </select>
                            </div>
                            <div class="obj1_type d-flex flex-column">
                                <span>Выбрать вид КМ:</span>
                                <select class="js-chosen" name="object_1_wed_type" id="object_1_wed_type"
                                    placeholder="Выберите вид КМ">
                                    <?php
                                    if (!empty($row_day_week->object_1_wed_type)) {
                                        echo "<option value=\"$row_day_week->object_1_wed_type\">$row_day_week->event_1_wed </option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>

                                    <?= $html2 ?>
                                </select>
                            </div>
                            <div class="object_2_wed d-flex flex-column">
                                <span>Выбрать еще станцию:</span>
                                <select class="js-chosen" name="object_2_wed" id="object_2_wed"
                                    placeholder="Выберите станцию">
                                    <?php
                                    if (!empty($row_day_week->object_2_wed)) {
                                        echo " <option value=\"$row_day_week->object_2_wed\">$row_day_week->station_2_wed</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>

                                    <?= $html1 ?>
                                </select>
                            </div>
                            <div class="obj2_type d-flex flex-column">
                                <span>Выбрать вид КМ:</span>
                                <select class="js-chosen" name="object_2_wed_type" id="object_2_wed_type"
                                    placeholder="Выберите вид КМ">
                                    <?php
                                    if (!empty($row_day_week->object_2_wed_type)) {
                                        echo "<option value=\"$row_day_week->object_2_wed_type\">$row_day_week->event_2_wed</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>
                                    <?= $html2 ?>
                                </select>
                            </div>
                            <div class="checkbox_plan">
                                <label class="toggle">
                                    <span class="toggle-lable">Согласно плану</span>
                                    <input class="toggle-checkbox" type="checkbox" name="checkbox_wed" id="checkbox_wed"
                                        <?php
                                        if (!empty($row_day_week->checkbox_wed)) {
                                            if ($row_day_week->checkbox_wed == 1) {
                                                echo "checked=cheked";
                                            }
                                        } else {
                                            echo "";
                                        }
                                        ?>>
                                    <div class="toggle-switch"></div>

                                </label>
                            </div>
                            <div class="coments d-flex flex-column">
                                <span>Коментарий</span>
                                <textarea name="coments_wed" id="coments_wed" cols="10" rows="5"><?= !empty($row_day_week->coments_wed) ? $row_day_week->coments_wed : "" ?></textarea>
                            </div>

                        </div>

                    </div>
                    <div class="week_day thu">
                        <div class="day_header">
                            <span>Четверг <?= $days_arr[3][0] ?></span>
                        </div>
                        <div class="day_choice d-md-flex flex-column">
                            <div class="choice_object d-flex flex-column">
                                <span>Выбрать объект:</span>
                                <select class="js-chosen" name="object_1_thu" id="object_1_thu"
                                    placeholder="Выберите станцию">
                                    <?php
                                    if (!empty($row_day_week->object_1_thu)) {
                                        echo "<option value=\"$row_day_week->object_1_thu\">$row_day_week->station_1_thu</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>
                                    <?= $html1 ?>
                                </select>
                            </div>
                            <div class="obj1_type d-flex flex-column">
                                <span>Выбрать вид КМ:</span>
                                <select class="js-chosen" name="object_1_thu_type" id="object_1_thu_type"
                                    placeholder="Выберите вид КМ">
                                    <?php
                                    if (!empty($row_day_week->object_1_thu_type)) {
                                        echo "<option value=\"$row_day_week->object_1_thu_type\">$row_day_week->event_1_thu</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>
                                    <?= $html2 ?>
                                </select>
                            </div>
                            <div class="object_2_wed d-flex flex-column">
                                <span>Выбрать еще станцию:</span>
                                <select class="js-chosen" name="object_2_thu" id="object_2_thu"
                                    placeholder="Выберите станцию">
                                    <?php
                                    if (!empty($row_day_week->object_2_thu)) {
                                        echo " <option value=\"$row_day_week->object_2_thu\">$row_day_week->station_2_thu</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>

                                    <?= $html1 ?>
                                </select>
                            </div>
                            <div class="obj2_type d-flex flex-column">
                                <span>Выбрать вид КМ:</span>
                                <select class="js-chosen" name="object_2_thu_type" id="object_2_thu_type"
                                    placeholder="Выберите вид КМ">
                                    <?php
                                    if (!empty($row_day_week->object_2_thu_type)) {
                                        echo "<option value=\"$row_day_week->object_2_thu_type\">$row_day_week->event_2_thu</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>

                                    <?= $html2 ?>
                                </select>
                            </div>
                            <div class="checkbox_plan">
                                <label class="toggle">
                                    <span class="toggle-lable">Согласно плану</span>
                                    <input class="toggle-checkbox" type="checkbox" name="checkbox_thu" id="checkbox_thu"
                                        <?php
                                        if (!empty($row_day_week->checkbox_thu)) {
                                            if ($row_day_week->checkbox_thu == 1) {
                                                echo "checked=cheked";
                                            }
                                        } else {
                                            echo "";
                                        }
                                        ?>>
                                    <div class="toggle-switch"></div>

                                </label>
                            </div>
                            <div class="coments d-flex flex-column">
                                <span>Коментарий</span>
                                <textarea name="coments_thu" id="coments_thu" cols="10" rows="5"><?= !empty($row_day_week->coments_thu) ? $row_day_week->coments_thu : "" ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="week_day fri">
                        <div class="day_header">
                            <span>Пятница <?= $days_arr[4][0] ?></span>
                        </div>
                        <div class="day_choice d-md-flex flex-column">
                            <div class="choice_object d-flex flex-column">
                                <span>Выбрать объект:</span>
                                <select class="js-chosen" name="object_1_fri" id="object_1_fri"
                                    placeholder="Выберите станцию">
                                    <?php
                                    if (!empty($row_day_week->object_1_fri)) {
                                        echo "<option value=\"$row_day_week->object_1_fri\">$row_day_week->station_1_fri</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>

                                    <?= $html1 ?>
                                </select>
                            </div>
                            <div class="obj1_type d-flex flex-column">
                                <span>Выбрать вид КМ:</span>
                                <select class="js-chosen" name="object_1_fri_type" id="object_1_fri_type"
                                    placeholder="Выберите вид КМ">
                                    <?php
                                    if (!empty($row_day_week->object_1_fri_type)) {
                                        echo "<option value=\"$row_day_week->object_1_fri_type\">$row_day_week->event_1_fri></option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>


                                    <?= $html2 ?>
                                    <option value="1">Общая проверка</option>
                                </select>
                            </div>
                            <div class="object_2_fri d-flex flex-column">
                                <span>Выбрать еще станцию:</span>
                                <select class="js-chosen" name="object_2_fri" id="object_2_fri"
                                    placeholder="Выберите станцию">
                                    <?php
                                    if (!empty($row_day_week->object_2_fri)) {
                                        echo "<option value=\"$row_day_week->object_2_fri\">$row_day_week->station_2_fri </option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>

                                    <?= $html1 ?>
                                </select>
                            </div>
                            <div class="obj2_type d-flex flex-column">
                                <span>Выбрать вид КМ:</span>
                                <select class="js-chosen" name="object_2_fri_type" id="object_2_fri_type"
                                    placeholder="Выберите вид КМ">
                                    <?php
                                    if (!empty($row_day_week->object_2_fri_type)) {
                                        echo "<option value=\"$row_day_week->object_2_fri_type\">$row_day_week->event_2_fri</option>";
                                    } else {
                                        echo  "<option value=\"0\"></option>";
                                    } ?>

                                    <?= $html2 ?>
                                </select>
                            </div>
                            <div class="checkbox_plan">
                                <label class="toggle">
                                    <span class="toggle-lable">Согласно плану</span>
                                    <input class="toggle-checkbox" type="checkbox" name="checkbox_fri" id="checkbox_fri"
                                        <?php
                                        if (!empty($row_day_week->checkbox_fri)) {
                                            if ($row_day_week->checkbox_fri == 1) {
                                                echo "checked=cheked";
                                            }
                                        } else {
                                            echo "";
                                        }
                                        ?>>
                                    <div class="toggle-switch"></div>

                                </label>
                            </div>
                            <div class="coments d-flex flex-column">
                                <span>Коментарий</span>
                                <textarea name="coments_fri" id="coments_fri" cols="10" rows="5"><?= !empty($row_day_week->coments_fri) ? $row_day_week->coments_fri : "" ?></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="text" value="<?= $day_n ?>" name="day_n" id="day_n" hidden>
                    <input type="text" value="<?= $ip_user ?>" name="ip_user" id="ip_user" hidden>
                    <input type="text" value="<?= $weekNumber ?>" name="weekNumber" id="weekNumber" hidden>
                </div>
                <div class="workspace_nav d-flex justify-content-lg-center my-4">
                    <div class="workspace_nav_btn">
                        <button class="btn btn-success mx-3 my-3" type="submit" id="add_events" <?= !empty($row_day_week->station_1_mon) ? 'disabled' : '' ?>>Сохранить</button>
                    </div>
                    <div class="workspace_nav_back mx-3 my-3">
                        <button class="btn btn-primary" type="button" id="history-button">Назад</button>
                    </div>
                </div>
                <div class="comments">
                    <p class="coment text-center">* в настоящее время функция корректировки отключена.</p>
                </div>
                <div class="info">
                    <div class="error-mess text-center" id="error-block"></div>
                </div>
        </div>
        </form>
        </div>
    </main>

    <link rel="stylesheet" href="css/chosen.min.css">
    <?php include_once("block/footer.php"); ?>

    <script>
        $("form#add_events").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: 'ajax/add_events.php',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data == 1) {
                        $("#sub").text("Выполнено");
                        $("#error-block").hide();
                        window.location.replace("http://dnch.loc/control_dnch.php");
                        //document.location.reload(true);
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
                var hrf = 'control_edit.php?week_set=now&weekNumber=' + week;
                location.replace(hrf);
            });

        });

        /*----Кнопка назад----*/
        document.getElementById('history-button').addEventListener('click', () => {
            history.back();
        });
    </script>

</body>

</html>