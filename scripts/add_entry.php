<?
require("connect.php");
$text_comment = $_POST["text_comment"];
$text_cusid_hid = $_POST["cusid_hid"];
$text_userid_hid = $_POST["userid_hid"]
$sqlser="insert into comment_list (Comment_Text,Cus_ID,User_ID)
        values ('".$text_comment."',
        '".$text_cusid_hid."',
        '".$text_userid_hid."')";

        $queryser=mysql_query($sqlser) or die(mysql_error());;
?>
