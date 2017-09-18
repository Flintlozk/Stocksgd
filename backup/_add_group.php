<?
require("_Connect.php");

?>
<?
//SQL Query Section
$sql = "SELECT temp_group_name.g_id as g_ID, temp_group_name.g_name as g_Name FROM temp_group_name;";
$rs = odbc_exec($Conn,$sql);
odbc_fetch_array($rs);
$g_ID = odbc_result($rs,"g_ID");
$g_Name = odbc_result($rs,"g_Name");

//$sql = "SELECT g_ID,g_Name from group_name WHERE g_Name = '$_POST[textg_Name]'";
//$result = mysql_query($sql) or die(mysql_error());
//$row = mysql_fetch_array($result);


?>

            <?

            if($g_Name == $_POST["textg_Name"]){
              echo "<SCRIPT>alert('ชื่อหมวดหมู่ซ้ำ');</SCRIPT>";
              print "<SCRIPT>window.history.back();</SCRIPT>";
            } else {
              $sqlser="insert into temp_group_name (temp_group_name.g_name) values ('".$_POST["textg_Name"]."')";
              $queryser=odbc_exec($Conn,$sqlser);
              include("table.php");
            }?>

