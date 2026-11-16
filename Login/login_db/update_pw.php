<?php
require_once ('../common/database.php');
$user = $_POST["user"];
$pw = $_POST['pw'];
$pw1 = $_POST['pw1'];
$email = $_POST['email'];
$pw_en = base64_encode ($pw);
$pw1_en = base64_encode ($pw1);

if(stripos($user, " OR ") !== false || 
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
strlen($user) > 15){
    echo "detected sensitive cases<br>";
    $user = null;
}
if(stripos($pw, " OR ") !== false || 
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
strlen($pw) > 15){
    echo "detected sensitive cases<br>";
    $pw = null;
}
if(stripos($pw1, " OR ") !== false || 
stripos($pw1, " ") !== false || 
stripos($pw1, "%") !== false || 
stripos($pw1, "'") !== false || 
stripos($pw1, "{") !== false || 
stripos($pw1, "}") !== false || 
stripos($pw1, "[") !== false || 
stripos($pw1, "]") !== false || 
stripos($pw1, "(") !== false || 
stripos($pw1, ")") !== false || 
stripos($pw1, "<") !== false || 
stripos($pw1, ">") !== false || 
stripos($pw1, "#") !== false || 
stripos($pw1, "!") !== false || 
stripos($pw1, "|") !== false || 
stripos($pw1, "&") !== false || 
stripos($pw1, ";") !== false || 
stripos($pw1, ":") !== false || 
stripos($pw1, "$") !== false || 
stripos($pw1, "*") !== false || 
stripos($pw1, "=") !== false || 
stripos($pw1, ",") !== false || 
stripos($pw1, ".") !== false || 
strlen($pw1) > 15){
    echo "detected sensitive cases<br>";
    $pw1 = null;
}
if(stripos($email, " OR ") !== false || 
stripos($email, " ") !== false || 
stripos($email, "%") !== false || 
stripos($email, "'") !== false || 
stripos($email, "{") !== false || 
stripos($email, "}") !== false || 
stripos($email, "[") !== false || 
stripos($email, "]") !== false || 
stripos($email, "(") !== false || 
stripos($email, ")") !== false || 
stripos($email, "<") !== false || 
stripos($email, ">") !== false || 
stripos($email, "#") !== false || 
stripos($email, "!") !== false || 
stripos($email, "|") !== false || 
stripos($email, "&") !== false || 
stripos($email, ";") !== false || 
stripos($email, ":") !== false || 
stripos($email, "$") !== false || 
stripos($email, "*") !== false || 
stripos($email, "=") !== false || 
stripos($email, ",") !== false || 
stripos($email, ".") !== false || 
strlen($email) > 15){
    echo "detected sensitive cases<br>";
    $email = null;
}

$sql = "select * from member where [user] ='$user' and email='$email'";//查詢整個表單
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = sqlsrv_query($link ,$sql, $params, $options)or die("sql error".sqlsrv_errors());
$num=sqlsrv_num_rows($result);

if($num==1){
    $sql1 = "UPDATE member set pw='$pw_en',pw1='$pw1_en' where [user] ='$user' and email='$email' and status= '1'";
    $query=sqlsrv_query($link,$sql1)or die("sql error".sqlsrv_errors());
    echo "success";
}
if($num!=1){
    echo "Failed";
}
?>