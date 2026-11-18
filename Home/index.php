<?php
ini_set('display_errors', '1');
$rootPath = $_SERVER['DOCUMENT_ROOT'];
$clientRoot = explode('/', $_SERVER["REQUEST_URI"])[1];

// Login validation, adjust according to login modules
$isLogin = true;

session_start();
if (!isset($_SESSION["user"])) {
    // $isLogin = false;
}

if (!$isLogin) {
    ?>
    <script>
        const hashModule = window.location.hash.substring(1);
        // Navigate to login page
        window.location.href = `/<?php echo $clientRoot ?>/Login/#${hashModule}`;
    </script>
    <?php
    exit();
}

require_once realpath('./shared/models/utils.php');
$sharedUtils = new sharedUtils;

// echo $rootPath;
// echo "<br>";
// $domain = $_SERVER['HTTP_HOST'];
// echo $domain;
// echo "<br>";
// $rootUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// echo $rootUrl;
// echo "<br>";
// echo $clientRoot;
// echo "<br>";
// echo $_SERVER["REQUEST_URI"];
// echo "<br>";


// include ($rootPath . '/' . $clientRoot . '/bootstrap/views/Main.php');
require_once realpath('../bootstrap/views/loadingElements/loadingElement.php');
?>
<div class="full-page-overlay" id="full-overlay-container"></div>
<iframe id="legacy-content" class="legacy-iframe-fullscreen"></iframe>
<div id="full-page-content"></div>
<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="icon" href="/common/IDCC_plugin/images/8664815_copyright_law_icon.png" type="image/png"> -->
</head>

<div id="sidebarModalRoot"></div>
<div id="modalRoot"></div>

<body>
<?php
require_once dirname(__DIR__) . '/env/idcc-mode.php';
$idccConfigured = true;
if (idccDemoMode()) foreach (['HOST', 'NAME', 'UID', 'PWD'] as $key) {
    if (getenv('IDCC_DB_' . $key) === false || getenv('IDCC_DB_' . $key) === '') $idccConfigured = false;
}
if (!$idccConfigured): ?>
<div role="status" style="position:fixed;top:0;left:0;right:0;z-index:2147483647;padding:10px 16px;background:#fff3cd;color:#664d03;text-align:center;">
    尚未設定 IDCC 資料庫。環境可正常安裝，請稍後開啟安裝包的「資料庫設定」工具，儲存並套用連線資訊。
</div>
<?php endif; ?>
    <div id="sidebar"></div>
    <div id="content"></div>

    <iframe id="chat-frame" src="../chatui/index.html"></iframe>
</body>

</html>

<!-- community plugins -->
<!-- css -->
<!-- bootstrap -->
<link href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/common/IDCC_plugin/bootstrap-toggle/bootstrap-toggle.min.css" rel="stylesheet">

<!-- alert -->
<link rel="stylesheet" href="/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.css">

<!-- icons -->
<link href="/common/IDCC_plugin/fa-icons/fa-icons.css" rel="stylesheet">


<!-- js -->
<!-- jquery -->
<script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>
<script src="/common/IDCC_plugin/jquery-ui-1.13.2.custom/jquery-ui.min.js"></script>

<!-- popper -->
<script src="/common/IDCC_plugin/@popperjs/core/dist/umd/popper.min.js"></script>

<!-- bootstrap -->
<script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.min.js"></script>
<script src="/common/IDCC_plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>

<!-- charts -->
<script src="/common/IDCC_plugin/apexcharts/apexcharts.js"></script>

<!-- grid(table) -->
<script src="/common/IDCC_plugin/ag-grid/ag-grid-community.min.js"></script>

<!-- alert -->
<script src="/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.js"></script>


<!-- custom plugins -->
<link href="/<?php echo $clientRoot; ?>/bootstrap/views/gridStyling.css" rel="stylesheet">
<link href="/<?php echo $clientRoot; ?>/bootstrap/views/main.css" rel="stylesheet">
<link href="/<?php echo $clientRoot; ?>/bootstrap/views/lightTheme.css" rel="stylesheet" id="light-theme-css" disabled>

<script src="/<?php echo $clientRoot; ?>/bootstrap/views/loadingElements/loadingElement.js" type="module"></script>
<script src="/<?php echo $clientRoot; ?>/bootstrap/views/populateGrid.js"></script>
<script src="/<?php echo $clientRoot; ?>/bootstrap/views/SPAcontentUpdater.js" type="module"></script>