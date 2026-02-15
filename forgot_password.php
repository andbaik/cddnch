<?php
include_once('block/connect_db.php');
include_once('block/header.php');

$mail = isset($_POST['mail']) && !empty(trim(htmlspecialchars($_POST['mail']))) ? trim(htmlspecialchars($_POST['mail'])) : NULL;
if ($mail != NULL) {

    //Проверяем наличие пользователя в БД
    $query = $pdo->prepare("SELECT * FROM user_control WHERE email = :mail LIMIT 1");
    try {
        $query->execute(['mail' => $mail]);
    } catch (PDOException $e) {
        echo "Ошибка при выполнении запроса: " . $e->getMessage();
        exit;
    }
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo <<<END
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <main class="col-12 col-md-6 mt-5 mb-5 p-4 shadow-sm rounded bg-white">
                    <h4 class="title">Пользователь с такой электронной почтой не найден!</h4>
                </main>
                <p class="title_index"><a href="index.php">На главную страницу</a></p>
            </div>
    </body> 
    END;
        exit;
    }
    //Все хорошо, отправляем письмо для восстановления пароля
    $to = $mail;
    $subject = 'Восстановление пароля';
    $new_password = substr(md5(random_int(0, 10000)), 0, 8);
    $message = "Здравствуйте, " . $user['login'] . "!\nВаш новый пароль: " . $new_password . "\nПожалуйста, смените его после входа в систему.";
    $headers = '<From:>ЦД отдел безопасности</From:>    ' . "\r\n" .
        'Content-Type: text/plain; charset="utf-8"' . "\r\n" .
        'Content-Transfer-Encoding: 8bit' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $message, $headers);
    //Обновляем пароль в БД
    $update = $pdo->prepare("UPDATE user_control SET password = :password WHERE id_user = :id_user");
    $update->execute(['password' => $new_password, 'id_user' => $user['id_user']]);
    echo <<<END
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <main class="col-12 col-md-6 mt-5 mb-5 p-4 shadow-sm rounded bg-white">
                    <h4 class="title">На вашу электронную почту отправлен новый пароль.</h4>
                </main>
                <p class="title_index"><a href="index.php">На главную страницу</a></p>
            </div>
    </body> 
    END;
    include_once('block/footer.php');
    exit;
}
?>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <main class="col-12 col-md-6 mt-5 mb-5 p-4 shadow-sm rounded bg-white">
                <h2 class="title">Если вы забыли пароль введите адрес электронной почты:</h1>
                    <form id="forgot_password_form" method="POST">
                        <div class="mb-3">
                            <label for="mail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="mail" name="mail" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Восстановить пароль</button>
                    </form>
                    <div class="info">
                        <div class="error-mess text-center" id="error-block"></div>
                    </div>
            </main>
        </div>
</body>



<?php include_once('block/footer.php'); ?>