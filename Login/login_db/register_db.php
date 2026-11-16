<?php
//ini_set('display_errors','1');
//error_reporting(E_ALL);
require_once ('../common/database.php');
$user = $_POST["user"];
$pw = $_POST['pw'];
$pw1 = $_POST['pw1'];
$position = $_POST['position'];
$pos_num = $_POST['pos_num'];
$name = $_POST['name'];
$email = $_POST['email'];
$number = $_POST['number'];
$pw_en = base64_encode ($pw);
$pw1_en = base64_encode ($pw1);
$time = time();

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
if(stripos($position, " OR ") !== false || 
stripos($position, " ") !== false || 
stripos($position, "%") !== false || 
stripos($position, "'") !== false || 
stripos($position, "{") !== false || 
stripos($position, "}") !== false || 
stripos($position, "[") !== false || 
stripos($position, "]") !== false || 
stripos($position, "(") !== false || 
stripos($position, ")") !== false || 
stripos($position, "<") !== false || 
stripos($position, ">") !== false || 
stripos($position, "#") !== false || 
stripos($position, "!") !== false || 
stripos($position, "|") !== false || 
stripos($position, "&") !== false || 
stripos($position, ";") !== false || 
stripos($position, ":") !== false || 
stripos($position, "$") !== false || 
stripos($position, "*") !== false || 
stripos($position, "=") !== false || 
stripos($position, ",") !== false || 
stripos($position, ".") !== false || 
strlen($position) > 15){
    echo "detected sensitive cases<br>";
    $position = null;
}
if(stripos($pos_num, " OR ") !== false || 
stripos($pos_num, " ") !== false || 
stripos($pos_num, "%") !== false || 
stripos($pos_num, "'") !== false || 
stripos($pos_num, "{") !== false || 
stripos($pos_num, "}") !== false || 
stripos($pos_num, "[") !== false || 
stripos($pos_num, "]") !== false || 
stripos($pos_num, "(") !== false || 
stripos($pos_num, ")") !== false || 
stripos($pos_num, "<") !== false || 
stripos($pos_num, ">") !== false || 
stripos($pos_num, "#") !== false || 
stripos($pos_num, "!") !== false || 
stripos($pos_num, "|") !== false || 
stripos($pos_num, "&") !== false || 
stripos($pos_num, ";") !== false || 
stripos($pos_num, ":") !== false || 
stripos($pos_num, "$") !== false || 
stripos($pos_num, "*") !== false || 
stripos($pos_num, "=") !== false || 
stripos($pos_num, ",") !== false || 
stripos($pos_num, ".") !== false || 
strlen($pos_num) > 15){
    echo "detected sensitive cases<br>";
    $pos_num = null;
}
if(stripos($name, " OR ") !== false || 
stripos($name, " ") !== false || 
stripos($name, "%") !== false || 
stripos($name, "'") !== false || 
stripos($name, "{") !== false || 
stripos($name, "}") !== false || 
stripos($name, "[") !== false || 
stripos($name, "]") !== false || 
stripos($name, "(") !== false || 
stripos($name, ")") !== false || 
stripos($name, "<") !== false || 
stripos($name, ">") !== false || 
stripos($name, "#") !== false || 
stripos($name, "!") !== false || 
stripos($name, "|") !== false || 
stripos($name, "&") !== false || 
stripos($name, ";") !== false || 
stripos($name, ":") !== false || 
stripos($name, "$") !== false || 
stripos($name, "*") !== false || 
stripos($name, "=") !== false || 
stripos($name, ",") !== false || 
stripos($name, ".") !== false || 
strlen($name) > 15){
    echo "detected sensitive cases<br>";
    $name = null;
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
if(stripos($number, " OR ") !== false || 
stripos($number, " ") !== false || 
stripos($number, "%") !== false || 
stripos($number, "'") !== false || 
stripos($number, "{") !== false || 
stripos($number, "}") !== false || 
stripos($number, "[") !== false || 
stripos($number, "]") !== false || 
stripos($number, "(") !== false || 
stripos($number, ")") !== false || 
stripos($number, "<") !== false || 
stripos($number, ">") !== false || 
stripos($number, "#") !== false || 
stripos($number, "!") !== false || 
stripos($number, "|") !== false || 
stripos($number, "&") !== false || 
stripos($number, ";") !== false || 
stripos($number, ":") !== false || 
stripos($number, "$") !== false || 
stripos($number, "*") !== false || 
stripos($number, "=") !== false || 
stripos($number, ",") !== false || 
stripos($number, ".") !== false || 
strlen($number) > 15){
    echo "detected sensitive cases<br>";
    $number = null;
}
//echo $user;
//echo $pw_en;
//echo $pw1_en;
//echo $position;
//echo $name;
//echo $email;
//echo $number;
$sql = "select * from member where [user] ='$user' AND status!='4'";//查詢整個表單
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$result = sqlsrv_query($link ,$sql, $params, $options)or die("sql error".sqlsrv_errors());
$num=sqlsrv_num_rows($result);


echo $num[0];
if($num==0){
    $sql1 = "INSERT INTO member ([user], pw, pw1, name, email, number,position, pos_num, status) VALUES ('$user','$pw_en','$pw1_en','$name','$email','$number','$position','$pos_num','2')";
    $query=sqlsrv_query($link,$sql1)or die("sql error".sqlsrv_errors());
        echo "success";
}
if($num!=0){
    echo "Failed";
}
?>