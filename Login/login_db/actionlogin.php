<?php session_start();
ini_set('display_errors', '1');
date_default_timezone_set("Asia/Taipei");

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$user = $_POST["user"];
if (
    stripos($user, " OR ") !== false ||
    stripos($user, " ") !== false ||
    stripos($user, "%") !== false ||
    stripos($user, "'") !== false ||
    stripos($user, "{") !== false ||
    stripos($user, "}") !== false ||
    stripos($user, "[") !== false ||
    stripos($user, "]") !== false ||
    stripos($user, "(") !== false ||
    stripos($user, ")") !== false ||
    stripos($user, "<") !== false ||
    stripos($user, ">") !== false ||
    stripos($user, "#") !== false ||
    stripos($user, "!") !== false ||
    stripos($user, "|") !== false ||
    stripos($user, "&") !== false ||
    stripos($user, ";") !== false ||
    stripos($user, ":") !== false ||
    stripos($user, "$") !== false ||
    stripos($user, "*") !== false ||
    stripos($user, "=") !== false ||
    stripos($user, ",") !== false ||
    stripos($user, ".") !== false ||
    strlen($user) > 15) {
    echo json_encode(["error" => "detected sensitive cases"]);
    $user = null;
}
$pw = $_POST["pw"];
if (
    stripos($pw, " OR ") !== false ||
    stripos($pw, " ") !== false ||
    stripos($pw, "%") !== false ||
    stripos($pw, "'") !== false ||
    stripos($pw, "{") !== false ||
    stripos($pw, "}") !== false ||
    stripos($pw, "[") !== false ||
    stripos($pw, "]") !== false ||
    stripos($pw, "(") !== false ||
    stripos($pw, ")") !== false ||
    stripos($pw, "<") !== false ||
    stripos($pw, ">") !== false ||
    stripos($pw, "#") !== false ||
    stripos($pw, "!") !== false ||
    stripos($pw, "|") !== false ||
    stripos($pw, "&") !== false ||
    stripos($pw, ";") !== false ||
    stripos($pw, ":") !== false ||
    stripos($pw, "$") !== false ||
    stripos($pw, "*") !== false ||
    stripos($pw, "=") !== false ||
    stripos($pw, ",") !== false ||
    stripos($pw, ".") !== false ||
    strlen($pw) > 15) {
    echo json_encode(["error" => "detected sensitive cases"]);
    $pw = null;
}
$sql1 = "select * from member where [user]='$user' AND status ='1'";
$params = array();
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
$result1 = sqlsrv_query($link, $sql1, $params, $options) or die("sql error" . sqlsrv_errors());
$num1 = sqlsrv_num_rows($result1);
//echo $num1;
//echo $_SESSION['user'];
if ($num1 < 1) {
    echo json_encode(["error" => "無此帳號或此帳號尚未審核通過"]);
    //    echo "<META HTTP-EQUIV=REFRESH CONTENT='60;URL=./login.php'>";
} else {
    if (!isset($_SESSION["login_limit"])) {
        $_SESSION["login_limit"] = 2;
    }
    //    $_SESSION["login_limit"]= -1;
    //判斷session是否已啟動
    if ($_SESSION["login_limit"] < 0) {
        $sql = "SELECT * FROM login WHERE account ='$user' and login_status ='2'  ORDER BY insertime DESC";
        $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
        $row = sqlsrv_fetch_array($result);
        $newDate = $row[3]->format('Y-m-d H:i:s');
        //        echo '123';
//        echo $newDate;
        $time = strtotime($newDate);
        //        echo $time;
        $current = time();
        $passed_time = $current - $time;
        if ($passed_time > 50) {
            $_SESSION["login_limit"] = 2;
        }
    }
    if ($_SESSION["login_limit"] < 0) {
        echo json_encode(["error" => "登入次數超過限制，已被限制登入！請等待5分鐘後在登入!"]);
        //        echo "<META HTTP-EQUIV=REFRESH CONTENT='60;URL=./login.php'>";
    } else {
        if (empty($_SESSION['check_word'])) {
            echo json_encode(["error" => "無驗證碼資訊，請重新整理頁面!"]);
            die();
        }
        if (!empty($_POST['checkword'])) {  //判斷此兩個變數是否為空
            if ($_SESSION['check_word'] == $_POST['checkword']) {
                $_SESSION['check_word'] = ''; //比對正確後，清空將check_word值
                header('content-Type: text/html; charset=utf-8');
                $pw_en = base64_encode($pw);
                $sql2 = "select * from member where [user]='$user' AND pw ='$pw_en'";
                $params1 = array();
                $options1 = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
                $result2 = sqlsrv_query($link, $sql2, $params1, $options1) or die("sql error" . sqlsrv_errors());
                $row2 = sqlsrv_fetch_array($result2);
                $num = sqlsrv_num_rows($result2);
                $position = $row2[7];
                //                echo $num;
                if ($num == 1) {
                    $_SESSION['user'] = $user;
                    echo json_encode("登入成功");
                    // if ($position == '業務') {
                    //     echo "業務";
                    // }
                    ;
                    //                        echo "<META HTTP-EQUIV=REFRESH CONTENT='60;URL=./index.php'>";
                    $login_status = '1';
                }
                ;
                if ($num != 1) {
                    echo json_encode(["error" => "請確認輸入密碼是否正確，您還有" . $_SESSION["login_limit"] . "次機會"]);
                    //                        echo "<META HTTP-EQUIV=REFRESH CONTENT='60;URL=./login.php'>";
                    $login_status = '2';
                    $_SESSION["login_limit"]--;
                }
            } else {
                $login_status = '4';
                echo json_encode(["error" => "驗證碼錯誤!"]);
            }
        } else {
            $login_status = '5';
            echo json_encode(["error" => "驗證碼未輸入!"]);
        }
        $sql4 = "INSERT INTO login (account,login_status)  VALUES ('$user','$login_status')";
        $result4 = sqlsrv_query($link, $sql4) or die("sql error" . sqlsrv_errors());
    }
}
?>