<?
require("_Connect.php");

date_default_timezone_set('Asia/Bangkok');
?>


          <?
          $gsql = "SELECT temp_item_group.item_id as item_id,
                  temp_item_group.pro_groups as po_Group,
                  temp_item_group.pro_types as po_Cat,
                  temp_item_group.po_id as po_No,
                  temp_item_group.g_id as g_ID
                  FROM temp_item_group
                  WHERE temp_item_group.g_id = '$_GET[gid]';";
          $grs = odbc_exec($Conn,$gsql);

              $sqlser ="DELETE FROM temp_item_group WHERE temp_item_group.item_id = '$_GET[item_id]';";
              $queryser=odbc_exec($Conn,$sqlser);

            include("manage_table.php");
              ?>
