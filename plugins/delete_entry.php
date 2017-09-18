<?php
require("./connect.php");

$id=$_POST['id'];
$delete = "DELETE FROM comment WHERE Comment_No=$id";
$result = mysql_query($delete) or die(mysql_error());
?>
