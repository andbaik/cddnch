<?php
include_once '../block/connect_db.php';

function getIntField(string $name, int $default = 0): int
{
    $value = filter_input(INPUT_POST, $name, FILTER_VALIDATE_INT, ['options' => ['default' => $default]]);
    return $value === false ? $default : $value;
}

$date = trim(filter_input(INPUT_POST, 'date', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$check_g = isset($_POST['check_g']) ? 1 : 0;
$work_dsz = getIntField('work_dsz');
$work_ds = getIntField('work_ds');
$distract = getIntField('distract');
$coolness = getIntField('coolness');
$mountain = getIntField('mountain');
$trust = getIntField('trust');
$staffing = getIntField('staffing');
$related = getIntField('related');
$risk = getIntField('risk');
$intern = getIntField('intern');
$rate = getIntField('rate');
$individual = getIntField('individual');
$technical = getIntField('technical');
$attestation = getIntField('attestation');
$check = getIntField('check');
$allow = getIntField('allow');
$yourself = getIntField('yourself');
$newdoc = getIntField('newdoc');
$reconciliation = getIntField('reconciliation');
$checkbd = getIntField('checkbd');
$checkbdinto = getIntField('checkbdinto');
$checkbdintocel = getIntField('checkbdintocel');
$checkbdintoformula = getIntField('checkbdintoformula');
$checkotintocel = getIntField('checkotintocel');
$checkotksotp = getIntField('checkotksotp');
$checkotother = getIntField('checkotother');
$checkall = getIntField('checkall');
$checkallbd = getIntField('checkallbd');
$checkallot = getIntField('checkallot');
$checkallpnp = getIntField('checkallpnp');
$checkallformula = getIntField('checkallformula');
$checkdsp = getIntField('checkdsp');
$checkdspg = getIntField('checkdspg');
$checkdsd = getIntField('checkdsd');
$checkother = getIntField('checkother');
$crashes = getIntField('crashes');
$crashesother = getIntField('crashesother');
$eventsd = getIntField('eventsd');
$eventsother = getIntField('eventsother');
$eventsdfirst = getIntField('eventsdfirst');
$otsd = getIntField('otsd');
$injury = getIntField('injury');
$injuryother = getIntField('injuryother');
$fire = getIntField('fire');

$error = '';

if ($date === '') {
    $error = 'Выберите дату';
} elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    $error = 'Дата должна быть в формате YYYY-MM-DD';
}

if ($error !== '') {
    echo $error;
    exit;
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // При необходимости поменяйте имя таблицы на вашу реальную структуру
    $insert = $pdo->prepare(
        'INSERT INTO form1
        (date, check_g, work_dsz, work_ds, distract, coolness, mountain, trust, staffing, related,
         risk, intern, rate, individual, technical, attestation, check_tr, allow_tr, yourself, newdoc,
         reconciliation, checkbd, checkbdinto, checkbdintocel, checkbdintoformula, checkotintocel, checkotksotp,
         checkotother, checkall, checkallbd, checkallot, checkallpnp, checkallformula, checkdsp, checkdspg,
         checkdsd, checkother, crashes, crashesother, eventsd, eventsother, eventsdfirst, otsd, injury,
         injuryother, fire)
         VALUES
         (:date, :check_g, :work_dsz, :work_ds, :distract, :coolness, :mountain, :trust, :staffing, :related,
          :risk, :intern, :rate, :individual, :technical, :attestation, :check_tr, :allow_tr, :yourself, :newdoc,
          :reconciliation, :checkbd, :checkbdinto, :checkbdintocel, :checkbdintoformula, :checkotintocel, :checkotksotp,
          :checkotother, :checkall, :checkallbd, :checkallot, :checkallpnp, :checkallformula, :checkdsp, :checkdspg,
          :checkdsd, :checkother, :crashes, :crashesother, :eventsd, :eventsother, :eventsdfirst, :otsd, :injury,
          :injuryother, :fire)'
    );

    $insert->execute([
        ':date' => $date,
        ':check_g' => $check_g,
        ':work_dsz' => $work_dsz,
        ':work_ds' => $work_ds,
        ':distract' => $distract,
        ':coolness' => $coolness,
        ':mountain' => $mountain,
        ':trust' => $trust,
        ':staffing' => $staffing,
        ':related' => $related,
        ':risk' => $risk,
        ':intern' => $intern,
        ':rate' => $rate,
        ':individual' => $individual,
        ':technical' => $technical,
        ':attestation' => $attestation,
        ':check_tr' => $check,
        ':allow_tr' => $allow,
        ':yourself' => $yourself,
        ':newdoc' => $newdoc,
        ':reconciliation' => $reconciliation,
        ':checkbd' => $checkbd,
        ':checkbdinto' => $checkbdinto,
        ':checkbdintocel' => $checkbdintocel,
        ':checkbdintoformula' => $checkbdintoformula,
        ':checkotintocel' => $checkotintocel,
        ':checkotksotp' => $checkotksotp,
        ':checkotother' => $checkotother,
        ':checkall' => $checkall,
        ':checkallbd' => $checkallbd,
        ':checkallot' => $checkallot,
        ':checkallpnp' => $checkallpnp,
        ':checkallformula' => $checkallformula,
        ':checkdsp' => $checkdsp,
        ':checkdspg' => $checkdspg,
        ':checkdsd' => $checkdsd,
        ':checkother' => $checkother,
        ':crashes' => $crashes,
        ':crashesother' => $crashesother,
        ':eventsd' => $eventsd,
        ':eventsother' => $eventsother,
        ':eventsdfirst' => $eventsdfirst,
        ':otsd' => $otsd,
        ':injury' => $injury,
        ':injuryother' => $injuryother,
        ':fire' => $fire,
    ]);

    echo 1;
} catch (PDOException $e) {
    echo 'Ошибка БД: ' . $e->getMessage();
}
