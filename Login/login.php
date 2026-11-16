<?php
ini_set('display_errors', '1');

if (!isset($loginToken)) {
    header("Location: ./");
}

$rootPath = $_SERVER['DOCUMENT_ROOT'];
$requestURI = $_SERVER['REQUEST_URI'];
$clientRoot = explode('/', $_SERVER["REQUEST_URI"])[1];

require_once realpath($rootPath . "/common/language/language_index.php");
$lang = new Language();
$lang->load("login");
$lang->load("header");
// var_dump($lang);
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo $lang->line("header.web_title"); ?></title>
    <?php require_once realpath($rootPath . '/common/legacyTemplate/head.php'); ?>
    <link rel="stylesheet" href="/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.css">
    <script src="/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.js"></script>
    <style style="text/css">
        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="mdl-layout mdl-js-layout color--gray is-small-screen login">
        <main class="mdl-layout__content">
            <div class="mdl-card mdl-card__login mdl-shadow--2dp">
                <div class="mdl-card__supporting-text color--dark-gray">
                    <div class="mdl-grid" id="login-form">
                        <div class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone">
                            <span
                                class="login-name text-color--white"><?php echo $lang->line("login.login_title"); ?></span>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone">
                            <!-- <span class="mdl-card__title-text text-color--white">資訊查詢管理系統</span> -->
                            <!-- <span class="login-secondary-text text-color--smoke">Enter fields to sign in to DARKBOARD</span> -->
                        </div>
                        <div class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size">
                                <input class="mdl-textfield__input" type="text" id="account">
                                <label class="mdl-textfield__label"
                                    for="e-mail"><?php echo $lang->line("login.login_account"); ?></label>
                            </div>
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size">
                                <input class="mdl-textfield__input" type="password" id="password">
                                <label class="mdl-textfield__label"
                                    for="password"><?php echo $lang->line("login.login_password"); ?></label>
                            </div>
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size">
                                <img src="./captcha.php" id="imgcode" style="width: 150px;height: 50px">
                                <button style="background-color: #3c3f41"
                                    onclick="document.getElementById('imgcode').src = document.getElementById('imgcode').src + '?' + (new Date()).getMilliseconds()"><img
                                        src="/common/images/restart.png" style="width: 45px;height: 45px"></button>
                            </div>
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label full-size">
                                <input class="mdl-textfield__input" type="text" name="checkword" id="checkword">
                                <label class="mdl-textfield__label" for="checkword">輸入驗證碼</label>
                            </div>
                            <a href="forgot-password.php"
                                class="login-link"><?php echo $lang->line("login.login_forget_pw"); ?></a>
                        </div>
                        <div class="mdl-cell mdl-cell--12-col mdl-cell--4-col-phone submit-cell">
                            <a href="sign-up.php"
                                class="login-link"><?php echo $lang->line("login.login_signup"); ?></a>
                            <div class="mdl-layout-spacer"></div>
                            <button type="button" class="mdl-button mdl-js-button mdl-button--raised color--light-blue"
                                id='submit'>
                                <?php echo $lang->line("login.login_button"); ?>
                            </button>
                        </div>
                        <a href='login.php?lang=zh-TW'>中文</a>&nbsp;|&nbsp;<a href='login.php?lang=en-US'>English</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        $(document).keyup(function (event) {
            if (event.keyCode == 13) {
                $("#submit").click();
            }
        });

        function clearInputs() {
            const $container = $('#login-form');
            const $inputs = $container.find('input, textarea, select');

            $inputs.each(function () {
                const $input = $(this);
                const type = $input.attr('type');

                if ($input.is('input[type="text"], input[type="number"], input[type="password"], input[type="email"], input[type="url"], textarea')) {
                    $input.val('');
                } else if ($input.is('input[type="checkbox"], input[type="radio"]')) {
                    $input.prop('checked', false);
                } else if ($input.is('select')) {
                    $input.prop('selectedIndex', -1);
                }
            });
        }

        $("#submit").click(function () {
            var user = $('#account').val();
            var pw = $('#password').val();
            var checkword = $('#checkword').val();
            let formValidation = true;
            if (user == '') {
                Swal.fire({
                    title: '登入錯誤',
                    text: '帳號不能為空！',
                    icon: 'error'
                });
                formValidation = false;
                return;
            }
            if (pw == '') {
                Swal.fire({
                    title: '登入錯誤',
                    text: '密碼不能為空！',
                    icon: 'error'
                });
                formValidation = false;
                return;
            }
            if (formValidation == true) {
                $.ajax({
                    url: "./login_db/actionlogin.php",
                    type: "POST",
                    data: { user: user, pw: pw, checkword: checkword }, //也可以把ViewModel的資料送到後端
                    dataType: "text",
                    success: function (response) {
                        const data = JSON.parse(response);
                        if (data === "登入成功") {
                            if (data === "業務") {
                                // $(location).attr('href', './index1.php');
                            }
                            else {
                                const selectedModule = window.location.hash;
                                $(location).attr('href', `../Home/${selectedModule}`);
                            }
                        }
                        else if (data.error == "無驗證碼資訊，請重新整理頁面!") {
                            Swal.fire({
                                title: '登入錯誤',
                                text: data.error,
                                icon: 'error'
                            }).then((result) => {
                                // window.location.reload();
                            });
                        }
                        else if (data.error) {
                            Swal.fire({
                                title: '登入錯誤',
                                text: data.error,
                                icon: 'error'
                            });
                            clearInputs();
                        }
                        else {
                            Swal.fire({
                                title: '登入錯誤',
                                text: "未知錯誤!請與工程師聯絡，謝謝",
                                icon: 'error'
                            });
                            console.error(data)
                        }
                    }
                });
            }
        });    
    </script>
    <!-- inject:js -->
    <script src="/common/legacyTemplate/d3.min.js"></script>
    <script src="/common/legacyTemplate/getmdl-select.min.js"></script>
    <script src="/common/legacyTemplate/material.min.js"></script>
    <script src="/common/legacyTemplate/nv.d3.min.js"></script>
    <script src="/common/legacyTemplate/layout/layout.min.js"></script>
    <script src="/common/legacyTemplate/scroll/scroll.min.js"></script>
    <!-- endinject -->

</body>

</html>