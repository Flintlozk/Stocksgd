<?
include_once("_Connect.php");

$gid = $_GET["agid"];
$pno = $_GET["apno"];
$pgo = $_GET["apgo"];
$pco = $_GET["apco"];
$status = $_GET["status"];

if($status == ""){
  $sql_sum1 ="SELECT temp_item_group.g_id, Count(temp_item_group.item_id) AS qty
  FROM temp_item_group
  WHERE temp_item_group.g_id ='$_GET[gid]'
  GROUP BY temp_item_group.g_id;";

  $sumrs1 = odbc_exec($Conn,$sql_sum1);
  while(odbc_fetch_row($sumrs1)){
    $qty1 = odbc_result($sumrs1,"qty");
    //Count Result
    echo $qty1;

  }
}

if($status == "add"){
  $sqlser="insert into temp_item_group (temp_item_group.pro_groups,temp_item_group.pro_types,temp_item_group.po_id,temp_item_group.g_id)
  values ('".$pgo."','".$pco."','".$pno."','".$gid."');";
  $rs = odbc_exec($Conn,$sqlser);

  $sql_sum1 ="SELECT temp_item_group.g_id, Count(temp_item_group.item_id) AS qty
  FROM temp_item_group
  WHERE temp_item_group.g_id ='$gid'
  GROUP BY temp_item_group.g_id;";

  $sumrs1 = odbc_exec($Conn,$sql_sum1);
  while(odbc_fetch_row($sumrs1)){
    $qty1 = odbc_result($sumrs1,"qty");
    //Count Result
    echo $qty1;

  }
}

if($status == "badge"){
  $sql_sum1 ="SELECT temp_item_group.g_id, Count(temp_item_group.item_id) AS qty
  FROM temp_item_group
  WHERE temp_item_group.g_id ='$_GET[gid]'
  GROUP BY temp_item_group.g_id;";

  $sumrs1 = odbc_exec($Conn,$sql_sum1);
  while(odbc_fetch_row($sumrs1)){
    $qty1 = odbc_result($sumrs1,"qty");
    //Count Result
    echo $qty1-1;

  }
}



?>
