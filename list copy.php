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
            <h2 class="text-center mb-5">Заполнение для ДНЧ-5 </h2>
            <form action="#" method="POST" id="add_form1" enctype="multipart/form-data">
                <div class="row content">
                    <div class="col-5 content-left">
                        <div class="row mb-2">
                            <div class="col-9"><label for="date" class="form-label">В должности ДНЧ на участке с (дата)</label></div>
                            <div class="col-3"><input type="date" class="form-control" id="date" name="date" aria-describedby="date"></div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="check_g" name="check_g">
                            <label class="form-check-label" for="check_g">Опыт работы на сортировочной горке</label>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="work_dsz" class="form-label">Опыт работы ДСЗ, ДСГ внеклассной и/или 1 класса станции (лет)</label></div>
                            <div class="col-3"><input type="number" min="0" max="100" class="form-control" id="work_dsz" aria-describedby="date"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="work_ds" class="form-label">Опыт работы ДС 2-5 класса станции (лет)</label></div>
                            <div class="col-3"><input type="number" min="0" max="100" class="form-control" id="work_ds" name="work_ds" aria-describedby="date"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="distract" class="form-label">Отвлекался от выполнения должностных обязанностей, кол-во дней в месяц</label></div>
                            <div class="col-3"><input type="number" min="0" max="100" class="form-control" id="distract" name="distract" aria-describedby="date"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-8"><label for="coolness" class="form-label">Классность участка (вн, 1,2,3)</label></div>
                            <div class="col-4">
                                <select class="form-select" id="coolness" name="coolness">
                                    <option selected>Откройте </option>
                                    <option value="0">Внеклассный</option>
                                    <option value="1">Первого класса</option>
                                    <option value="2">Второго класса</option>
                                    <option value="3">Третьего класса</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="mountain" class="form-label">в том числе имеющие сортировочные устройства и станции, работающие "толчками", шт.</label></div>
                            <div class="col-3"><input type="number" min="0" max="10" class="form-control" id="mountain" name="mountain"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="trust" class="form-label">Количество станций на участке, имеющих сертификат соответствия, или паспорт доверия</label></div>
                            <div class="col-3"><input type="number" min="0" max="40" class="form-control" id="trust" name="trust"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="staffing" class="form-label">Штатная численность работников на участке, Д, чел</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="staffing" name="staffing"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="related" class="form-label">Кол-во работников смежных хозяйств, совм. обязанности Д, чел</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="related" name="related"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="risk" class="form-label">Кол-во работников в группе риска "2" в течение месяца, чел</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="risk" name="risk"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="intern" class="form-label">Количество стажеров в течении месяца, чел</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="intern" name="intern"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="rate" class="form-label">Проведена оценка знаний, кол-во чел.</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="rate" name="rate"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="individual" class="form-label">Проведено индивидуальных собеседований и/или тех. занятий, чел</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="individual" name="individual"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="technical" class="form-label">Проведено технических занятий, охвачено чел.</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="technical" name="technical "></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="attestation" class="form-label">Участие в аттестационной комиссии, чел.</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="attestation" name="attestation "></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="check" class="form-label">Участие в проверке знаний ТРА у локомотивных бригад, чел</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="check" name="check "></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="allow" class="form-label">Участие в комиссии по допуску к управлению устр. ЖАТ, чел</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="allow" name="allow "></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="yourself" class="form-label">Участие в комиссии по допуску к самостоятельной работе, чел</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="yourself" name="yourself "></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="newdoc" class="form-label">Участие в разработке новых документов, кол-во док.</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="newdoc" name="newdoc "></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="reconciliation" class="form-label">Проведение выверки документации, кол-во док.</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="reconciliation" name="reconciliation "></div>
                        </div>

                    </div>

                    <div class="col-5 content-right">
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkbd" class="form-label">Проведено проверок по БД, всего</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkbd" name="checkbd"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkbdinto" class="form-label">из них общих (включая вопросы ОТ и ПБ)</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkbdinto" name="checkbdinto"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkbdintocel" class="form-label">из них целевых</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkbdintocel" name="checkbdintocel"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkbdintoformula" class="form-label">Проведено проверок по ОТ, всего ФОРМУЛА</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkbdintoformula" name="checkbdintoformula"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkotintocel" class="form-label">из них целевых</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkotintocel" name="checkotintocel"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkotksotp" class="form-label">из по КСОТ-П</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkotksotp" name="checkotksotp"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkotother" class="form-label">Прочие мероприятия</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkotother" name="checkotother"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkall" class="form-label">Выявлено нарушений всего на станциях участка и путях общего пользования</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkall" name="checkall"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkallbd" class="form-label">из них по БД</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkallbd" name="checkallbd"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkallot" class="form-label">из них по ОТ</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkallot" name="checkallot"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkallpnp" class="form-label">Выявлено нарушений всего на путях необщего пользования</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkallpnp" name="checkallpnp"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkallformula" class="form-label">Количество замечаний на одну проверку ФОРМУЛА</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkallformula" name="checkallformula"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkdsp" class="form-label">Выявлено замечаний по ответственности ДСП, ДСПЦ</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkdsp" name="checkdsp"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkdspg" class="form-label">Выявлено замечаний по ответственности ДСПГ, ОСГ</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkdspg" name="checkdspg"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkdsd" class="form-label">Выявлено замечаний по ответственности составителя поездов, помощника составителя поездов, РСДВ, дежурного стрелочного поста, сигналиста</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkdsd" name="checkdsd"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="checkother" class="form-label">Прочие</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="checkother" name="checkother"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="crashes" class="form-label">Количество КРУШЕНИЙ и АВАРИЙ по ответственности Д</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="crashes" name="crashes"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="crashesother" class="form-label">Количество КРУШЕНИЙ и АВАРИЙ по ответственности смежных подразделений (когда действия работников ж.д. станции указаны в качестве способствующих причин)</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="crashesother" name="crashesother"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="eventsd" class="form-label">Количество иных событий по ответственности Д</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="eventsd" name="eventsd"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="eventsother" class="form-label">Количество иных событий по ответственности смежных подразделений (когда действия работников ж.д. станции указаны в качестве способствующих причин)</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="eventsother" name="eventsother"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="eventsdfirst" class="form-label">Количество случаев нарушений безопасности движения по ответственности работников станций участка в первый год работы</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="eventsdfirst" name="eventsdfirst"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="otsd" class="form-label">Количество ОТС по ответственности Д</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="otsd" name="otsd"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="injury" class="form-label">Количество случаев производственного травматизма Д</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="injury" name="injury"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="injuryother" class="form-label">Количество случаев производственного травматизма работников смежных подразделений по ответственности работников железнодорожных станций участка</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="injuryother" name="injuryother"></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9"><label for="fire" class="form-label">Количество случаев пожаров по ответственности Д</label></div>
                            <div class="col-3"><input type="number" min="0" max="500" class="form-control" id="fire" name="fire"></div>
                        </div>


                    </div>
                <div class="foter-form d-flex justify-content-center gap-5">
                    <button  type="submit" class="btn btn-primary btn-size">Сохранить</button>
                    <button class="btn btn-secondary btn-size">Назад</button>
                </div>
                </div>


            </form>
        </div>
    </main>

    <?php include_once("block/footer.php"); ?>

</body>

</html>