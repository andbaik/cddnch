<?php
$title = "Вход в систему Управления процессом деятельности ДНЧ";
include_once('block/header.php');

?>

</head>

<body>
    <div class="container">
        <div class="main">
            <main class="form-signin w-100 m-auto">
                <form action="#" method="post" id="enter">
                    <img class="mb-4" src="image/logo_2.png" alt="Логотип" width="400" height="100">
                    <h1 class="h3 mb-3 fw-normal text-center">Вход в систему<br /> Управление процессом деятельности ДНЧ</h1>

                    <div class="form-floating my-3">
                        <input type="text" class="form-control" id="login" name="login" placeholder="логин или email">
                        <label for="floatingInput">Введите логин или email</label>
                        <kpm-field-badge type="default" loading="false" menu-type="login" class="kpm_input-field-button kpm_gray-key-icon" style="--FieldBadge-ZLevel: 4 !important;"></kpm-field-badge>
                    </div>
                    <div class="form-floating my-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль">
                        <label for="floatingPassword">Введите пароль</label>
                        <kpm-field-badge type="default" loading="false" menu-type="login" class="kpm_input-field-button kpm_gray-key-icon" style="--FieldBadge-ZLevel: 4 !important;"></kpm-field-badge>
                    </div>

                    <div class="form-check text-start my-3">
                        <a href="forgot_password.php">Забыли пароль</a>
                    </div>
                    <button class="btn btn-danger w-100 py-2" id="sub" type="submit">Войти</button>
                    <div class="info">
                        <div class="error-mess text-center" id="error-block"></div>
                    </div>
                </form>

            </main>
        </div>
    </div>

    <?php include_once('block/footer.php'); ?>

    <script>
        $("form#enter").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            console.log(formData);

            $.ajax({
                url: 'ajax/user_auth.php',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data == 1) {
                        $("#sub").text("Выполнено");
                        $("#error-block").hide();
                        window.location.replace("http://cddnch.loc/control_cd.php");
                        //document.location.reload(true);
                        exit;
                    }
                    if (data == 2) {
                        $("#sub").text("Выполнено");
                        $("#error-block").hide();
                        window.location.replace("http://cddnch.loc/control_d.php");
                        //document.location.reload(true);
                        exit;
                    }
                    if (data == 3) {
                        $("#sub").text("Выполнено");
                        $("#error-block").hide();
                        window.location.replace("http://cddnch.loc/control_dcsrb.php");
                        //document.location.reload(true);
                        exit;
                    }
                    if (data == 4) {
                        $("#sub").text("Выполнено");
                        $("#error-block").hide();
                        window.location.replace("http://cddnch.loc/control.php");
                        //document.location.reload(true);
                        exit;
                    } else {
                        $("#error-block").show();
                        $("#error-block").text(data);
                    }
                }

            });
        });
    </script>

</body>

</html>