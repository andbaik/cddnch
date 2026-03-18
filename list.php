<?php
$title = 'Недельное планирование';

include_once('block/header.php');
include_once('block/setting.php');
include_once('function/function.php');
include_once('block/connect_db.php');


?>

<body class="wrapper">
    <div id="preloader">Добро пожаловать!</div>

    <main class="main">
        <div class="container">
            <h1 class="text-center">Форма 1</h1>
            <h2 class="text-center">Заполнение для ДНЧ-5 </h2>
            <form action="#" method="POST" id="add_form1" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-5">
                        <div class="mb-2">
                            <label for="date" class="form-label">В должности ДНЧ на участке с (дата)</label>
                            <input type="date" class="form-control" id="date" aria-describedby="date">
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="check_g">
                            <label class="form-check-label" for="check_g">Опыт работы на сортировочной горке</label>
                        </div>
                        <div class="mb-2">
                            <label for="work_dsz" class="form-label">Опыт работы ДСЗ, ДСГ внеклассной и/или 1 класса станции (лет)</label>
                            <input type="number" min="0" max="100" class="form-control" id="work_dsz" aria-describedby="date">
                        </div>
                        <div class="mb-2">
                            <label for="work_ds" class="form-label">Опыт работы ДС 2-5 класса станции (лет)</label>
                            <input type="number" min="0" max="100" class="form-control" id="work_ds" aria-describedby="date">
                        </div>
                        <div class="mb-2">
                            <label for="distract" class="form-label">Отвлекался от выполнения должностных обязанностей, кол-во дней в месяц</label>
                            <input type="number" min="0" max="100" class="form-control" id="distract" aria-describedby="date">
                        </div>
                        <div class="mb-2">
                            <label for="coolness" class="form-label">Классность участка (вн, 1,2,3)</label>
                            <select class="form-select" id="coolness">
                                <option selected>Откройте это меню выбора</option>
                                <option value="0">Внеклассный</option>
                                <option value="1">Первого класса</option>
                                <option value="2">Второго класса</option>
                                <option value="3">Третьего класса</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="mountain" class="form-label">в том числе имеющие сортировочные устройства и станции, работающие "толчками", шт.</label>
                            <input type="number" min="0" max="10" class="form-control" id="mountain">
                        </div>
                        <div class="mb-2">
                            <label for="trust" class="form-label">Количество станций на участке, имеющих сертификат соответствия, или паспорт доверия</label>
                            <input type="number" min="0" max="40" class="form-control" id="trust">
                        </div>


                    </div>

                </div>

            </form>
        </div>
    </main>

    <?php include_once("block/footer.php"); ?>

</body>

</html>