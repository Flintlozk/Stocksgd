<?
require("_Connect.php");

date_default_timezone_set('Asia/Bangkok');
?>

<?
//SQL Query Section
$gsql = "SELECT temp_item_group.item_id as item_id,
        temp_item_group.pro_groups as po_Group,
        temp_item_group.pro_types as po_Cat,
        temp_item_group.po_id as po_No,
        temp_item_group.g_id as g_ID
        FROM temp_item_group
        WHERE temp_item_group.g_id = '$_GET[gid]';";
$grs = odbc_exec($Conn,$gsql);

//$sql = "SELECT g_ID,g_Name from group_name WHERE g_Name = '$_POST[textg_Name]'";
//$result = mysql_query($sql) or die(mysql_error());
//$row = mysql_fetch_array($result);


?>

            <?
            while(odbc_fetch_array($grs)){
              $sqlser ="DELETE FROM temp_item_group WHERE temp_item_group.g_id = '$_GET[gid]';";
              $queryser=odbc_exec($Conn,$sqlser);
            }

              $sqlg ="DELETE FROM temp_group_name WHERE temp_group_name.g_id = '$_GET[gid]';";
              $queryg=odbc_exec($Conn,$sqlg);
			   include("table.php");
              ?>
       


