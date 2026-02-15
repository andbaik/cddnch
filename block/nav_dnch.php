<?php
$url = !empty($_GET['week_set']) ? $_GET['week_set'] : 'now';

switch ($url) {
    case 'now':
        $active_now = 'active';
        break;
    case 'last':
        $active_last = 'active';
        break;
    case 'next':
        $active_next = 'active';
        break;
    default:
        $active_now = 'active';
        break;
}
?>


<div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <img class="mb-4" src="image/logo_2.png" alt="Логотип" width="300" height="auto">
        </a>

        <ul class="nav nav-pills my-3">
            <li class="nav-item"><a href="control_dnch.php?week_set=now" class="nav-link <?=$active_now?>" aria-current="page">Текущая неделя</a></li>
            <li class="nav-item"><a href="control_dnch.php?week_set=last" class="nav-link <?=$active_last?>">Прошлая неделя</a></li>
            <li class="nav-item"><a href="control_dnch.php?week_set=next" class="nav-link <?=$active_next?>">Следующая неделя</a></li>
            <li class="nav-item"><a href="#" class="nav-link disable">Месяц</a></li>
            <li class="nav-item"><a href="#" class="nav-link disable">Задать вопрос</a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link">Выход</a></li>
        </ul>
    </header>
</div>