<?
include_once("_Connect.php");
include_once('_header.php');
include_once('_sidebar.php');
include_once('_header1.php');
?>
<style>
.myTable { background-color:#FFF;border-collapse:collapse;width:100%}
.myTable th { background-color:#3c8dbc; color:white; }
.myTable td, .myTable th { padding:2px;border:1px solid #3c8dbc; font-size:0.8em }
</style>

<?

$che="$";
$sql ="SELECT [SGD Inter Trading Co_,Ltd" . $che. "Item].No_, [SGD Inter Trading Co_,Ltd" . $che. "Item].[Item Category Code], [SGD Inter Trading Co_,Ltd" . $che. "Item].[Product Group Code]
From [SGD Inter Trading Co_,Ltd" . $che. "Item]
GROUP BY [SGD Inter Trading Co_,Ltd" . $che. "Item].No_, [SGD Inter Trading Co_,Ltd" . $che. "Item].[Item Category Code], [SGD Inter Trading Co_,Ltd" . $che. "Item].[Product Group Code]
ORDER BY [SGD Inter Trading Co_,Ltd" . $che. "Item].[Item Category Code] desc";
$rs = odbc_exec($Conn,$sql);
?>

<table class="myTable" id="example1">
       
          <tr>
            <th width="363" align="center"><div class="text-center">Model</div></th>
            <th width="167"><div class="text-center">Item Category </div></th>
            <th width="169"><div class="text-center">Product Group</div></th>
            <th width="189">Stock</th>
            <th width="217">Price</th>
            </tr>

  <? while (odbc_fetch_row($rs)){?>



          <tr>
            <td align="left"><div style="font-size:8px"><?=odbc_result($rs,"No_")?></div></td>
            <td><div style="font-size:8px"><?=odbc_result($rs,"Item Category Code")?></div></td>
            <td><div style="font-size:8px"><?=odbc_result($rs,"Product Group Code")?></div></td>
            <td>&nbsp;</td>

            <td>&nbsp;</td>
          </tr>
          <? }?>
 
      </table>

<? include_once('_foot1.php');?>