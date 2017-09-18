<div class="col-lg-12  col-md-12 col-sm-12 col-xs-12" style=" margin:5px 0px 0px 0px ">
<table id="example1" class="table table-bordered table-striped" width="100%">
  <thead>
    <tr>
      <th>ชื่อสินค้า</th>

      <th>จำนวน</th>
      <th>ราคา</th>
      <th></th>
    </tr>
  </thead>
  <tbody>

    <?
    $che = "$";
    $sql ="SELECT temp_item_group.item_id as item_id,
    temp_item_group.pro_groups as po_Group,
    temp_item_group.pro_types as po_Cat,
    temp_item_group.po_id as po_No,
    temp_item_group.g_id as g_ID
    FROM temp_item_group
    WHERE temp_item_group.g_id = '$_GET[gid]'
    ORDER BY temp_item_group.po_id asc;";

    $rs = odbc_exec($Conn,$sql);
    while(odbc_fetch_row($rs)){
      $item_id = iconv("tis-620", "utf-8", odbc_result($rs,"item_id"));
      $po_Group = iconv("tis-620", "utf-8", odbc_result($rs,"po_Group"));
      $po_Cat = iconv("tis-620", "utf-8", odbc_result($rs,"po_Cat"));
      $po_No = iconv("tis-620", "utf-8", odbc_result($rs,"po_No"));
      ?>
      <tr>
        <td><?=$po_No?></td>


        <?
        $sql_qty="SELECT Sum([SGD Inter Trading Co_,Ltd".$che."Item Ledger Entry].Quantity) AS SumOfQuantity
        FROM [SGD Inter Trading Co_,Ltd".$che."Item Ledger Entry]
        WHERE ((([SGD Inter Trading Co_,Ltd".$che."Item Ledger Entry].[Location Code]) In ('1S_HO','QC'))
        AND (([SGD Inter Trading Co_,Ltd".$che."Item Ledger Entry].[Item No_])='".$po_No."'))";
        $rs_qty = odbc_exec($Conn,$sql_qty);
        $qty = odbc_result($rs_qty,"SumOfQuantity");
        ?>
        <td align="left"><?=number_format($qty,0)?></td>

        <?
        $sql_price="SELECT [Unit Price] FROM [SGD Inter Trading Co_,Ltd".$che."Sales Price]
        WHERE  [SGD Inter Trading Co_,Ltd".$che."Sales Price].[Sales Code] not in('001')
        and [SGD Inter Trading Co_,Ltd".$che."Sales Price].[Item No_]='".$po_No."' Order by [Starting Date] DESC";
        $rs_price = odbc_exec($Conn,$sql_price);
        $price = odbc_result($rs_price,"Unit Price");
        ?>
        <td align="left"><?=number_format($price,0)?></td>
        <td align='center' width='1%'>
          <a href="javascript:GetID(<?=$item_id;?>);" class="glyphicon glyphicon-trash btn btn-danger"></a>
            <?
            /*<a href="./delete_row_manage.php?gid=<?=$_GET['gid']?>&itl=<?=$item_id?>" onclick="return confirm('Are you sure you want to delete group ?') ">

              <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
            </a>
            */?>
        </td>

      </tr>
    <? }?>
    <tfoot>
      <tr>

      </tr>
    </tfoot>
  </table>
</div>
<script>
var isBusy=false;
var currIdx=0;
var currPo='';
var tbl;
$(function () {
  tbl=$("#example1").DataTable({
    "iDisplayLength": 10
  });
});
</script>