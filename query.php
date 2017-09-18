<?
//Joint
SELECT temp_item_group.item_id, temp_group_name.g_name, temp_item_group.pro_types, temp_item_group.po_id, temp_item_group.g_id
FROM temp_group_name INNER JOIN temp_item_group ON temp_group_name.g_id = temp_item_group.g_id;

//Group
SELECT temp_group_name.g_id, temp_group_name.g_name
FROM temp_group_name;


$sql = "SELECT [SGD Inter Trading Co_,Ltd" . $che. "Item].No_,
 [SGD Inter Trading Co_,Ltd" . $che. "Item].[Item Category Code],
 [SGD Inter Trading Co_,Ltd" . $che. "Item].[Product Group Code]
From [SGD Inter Trading Co_,Ltd" . $che. "Item]
GROUP BY [SGD Inter Trading Co_,Ltd" . $che. "Item].No_,
[SGD Inter Trading Co_,Ltd" . $che. "Item].[Item Category Code],
[SGD Inter Trading Co_,Ltd" . $che. "Item].[Product Group Code]
ORDER BY [SGD Inter Trading Co_,Ltd" . $che. "Item].[Item Category Code];"
$rs = odbc_exec($Conn,$sql);


//--------------------------------------------------------------จำนวนคงคลัง
$sql_qty="SELECT Sum([SGD Inter Trading Co_,Ltd".$che."Item Ledger Entry].Quantity) AS SumOfQuantity
FROM [SGD Inter Trading Co_,Ltd".$che."Item Ledger Entry]
WHERE ((([SGD Inter Trading Co_,Ltd".$che."Item Ledger Entry].[Location Code]) In ('1S_HO','QC'))
AND (([SGD Inter Trading Co_,Ltd".$che."Item Ledger Entry].[Item No_])='".odbc_result($rs,"No_")."'))";
$rs_qty = odbc_exec($Conn,$sql_qty);
$qty = odbc_result($rs_qty,"SumOfQuantity");

// ---------------------------------------------------------------ราคาขาย
$sql_price="SELECT [Unit Price] FROM [SGD Inter Trading Co_,Ltd".$che."Sales Price]
WHERE  [SGD Inter Trading Co_,Ltd".$che."Sales Price].[Sales Code] not in('001')
and [SGD Inter Trading Co_,Ltd".$che."Sales Price].[Item No_]='".odbc_result($rs,"No_")."' Order by [Starting Date] DESC";
$rs_price = odbc_exec($Conn,$sql_price);
$price = odbc_result($rs_price,"Unit Price");
?>
