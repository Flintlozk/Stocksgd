<?
$servername = 'server-navision';
$databasename = 'SGD_PROD';
$user = 'sa'; 
$pass = 'avision'; 
$connection_string = "DRIVER={SQL Server};SERVER=$servername;DATABASE=$databasename;AutoTranslate=no"; 
$Conn = odbc_connect($connection_string,$user, $pass);

?>