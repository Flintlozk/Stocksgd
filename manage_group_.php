<?
include_once("_Connect.php");
include_once('_header.php');
include_once('_sidebar.php');
?>
<!----------------HEADER---------------------------->
<link rel="stylesheet" href="./plugins/iCheck/all.css">
<link rel="stylesheet" href="./css/checkbox.css">
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.9/css/dataTables.checkboxes.css" rel="stylesheet" />

<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.9/js/dataTables.checkboxes.min.js"></script>


<?
$gsql = "SELECT temp_group_name.g_id, temp_group_name.g_name as g_Name FROM temp_group_name WHERE temp_group_name.g_id = '$_GET[gid]';";
$grs = odbc_exec($Conn,$gsql);
odbc_fetch_array($grs);
$g_Name = odbc_result($grs,"g_Name");

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



/*$sql = "SELECT product.po_ID,po_Name,po_Cat,group_name.g_ID,g_Name,item_ID
FROM product,group_name,item_group
WHERE product.po_ID = item_group.po_ID
and group_name.g_ID = item_group.g_ID
AND item_group.g_ID = '$_GET[gid]'
ORDER BY po_ID ASC";
$result = mysql_query($sql) or die(mysql_error());
$row1 = mysql_fetch_array($result);*/


?>







<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Content Header (Page xheader) -->
  <section class="content-header">
    <h1><?=$g_Name?></h1>
  </section>

  <!-- Main content -->
  <section class="content">






        <div class="box box-primary">
          <div class="box-body">



              <div class="row">


                <div class="col-xs-12 col-md-12" style="margin:5px 0px 0px 0px">
                <a href='group_items.php?gid=<?=$_GET[gid]?>'>
                <div class="btn btn-primary" style="width: 100%;">
                <span class="glyphicon glyphicon-plus"></span>
                เพิ่มสินค้า
                </div>
                </a>

                </div>



 <!----- Edit -------------
 <div class="col-xs-12 col-md-6" style=" margin:5px 0px 0px 0px">

   <a href='edit_group_manage.php?gid=<?=$_GET[gid]?>'>
     <div class="btn btn-warning" style="width: 100%;">
       <span class="glyphicon glyphicon-trash"></span>
       แก้ไขหมวดหมู่
     </div>
   </a>

 </div>
 <!----- Delete-------------

 <div class="col-xs-12 col-md-6" style=" margin:5px 0px 0px 0px">

   <a href='delete_group_manage.php?gid=<?=$_GET[gid]?>' onclick="return confirm('Are you sure you want to delete group ?')">
     <div class="btn btn-danger" style="width: 100%;">
       <span class="glyphicon glyphicon-trash"></span>
       ลบหมวดหมู่
     </div>
   </a>

 </div>
<!----- End Edit / Delete------------->



                  <?
                  $rs2 = odbc_exec($Conn,$sql);
                  odbc_fetch_row($rs2);

                  /*if(!odbc_result($rs,"po_No")){
                    echo "<h3>ไม่พบข้อมูลสินค้าในหมวดหมู่นี้ <a href='group_items.php?gid=$_GET[gid]'>กรุณาเพิ่มสินค้า</a></h3>";
                    //print "<SCRIPT>window.location='group_items.php?gid=$_GET[gid]'</SCRIPT>";
                  }*/

                  ?>


                <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12" style=" margin:5px 0px 0px 0px ">
                  <table class="myTable" width="100%">
                    <thead>
                      <tr>
                        <th>ชื่อสินค้า</th>
                        <th>ประเภทสินค้า</th>
                        <th>ชนิด</th>
                        <th>จำนวน</th>
                        <th>ราคา</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>

                      <?
                      while(odbc_fetch_row($rs)){
                        $item_id = iconv("tis-620", "utf-8", odbc_result($rs,"item_id"));
                        $po_Group = iconv("tis-620", "utf-8", odbc_result($rs,"po_Group"));
                        $po_Cat = iconv("tis-620", "utf-8", odbc_result($rs,"po_Cat"));
                        $po_No = iconv("tis-620", "utf-8", odbc_result($rs,"po_No"));
                        ?>
                        <tr>
                          <td><?=$po_No?></td>
                          <td><?=$po_Group?></td>
                          <td><?=$po_Cat?></td>

                          <?
                          $sql_qty="SELECT Sum([SGD Inter Trading Co_,Ltd".$che."Item Ledger Entry].Quantity) AS SumOfQuantity
                          FROM [SGD Inter Trading Co_,Ltd".$che."Item Ledger Entry]
                          WHERE ((([SGD Inter Trading Co_,Ltd".$che."Item Ledger Entry].[Location Code]) In ('1S_HO','QC'))
                          AND (([SGD Inter Trading Co_,Ltd".$che."Item Ledger Entry].[Item No_])='".$po_No."'))";
                          $rs_qty = odbc_exec($Conn,$sql_qty);
                          $qty = odbc_result($rs_qty,"SumOfQuantity");
                          ?>
                          <td align="center"><?=number_format($qty,0)?></td>

                          <?
                          $sql_price="SELECT [Unit Price] FROM [SGD Inter Trading Co_,Ltd".$che."Sales Price]
                          WHERE  [SGD Inter Trading Co_,Ltd".$che."Sales Price].[Sales Code] not in('001')
                          and [SGD Inter Trading Co_,Ltd".$che."Sales Price].[Item No_]='".$po_No."' Order by [Starting Date] DESC";
                          $rs_price = odbc_exec($Conn,$sql_price);
                          $price = odbc_result($rs_price,"Unit Price");
                          ?>
                          <td align="right"><?=number_format($price,0)?></td>
                          <td align='center'>
                              <a href="./delete_row_manage.php?gid=<?=$_GET['gid']?>&itl=<?=$item_id?>" onclick="return confirm('Are you sure you want to delete group ?') ">

                                <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
                              </a>
                          </td>

                        </tr>
                      <? }?>
                      <tfoot>
                        <tr>

                        </tr>
                      </tfoot>
                    </table>



                    <? ?>
                    <!----------------FOOTER---------------------------->
                  </div><!-- /.box-body -->
                </div><!-- /.box -->
              </div><!-- /.col -->
            </div><!-- /.row -->

          </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

      </div><!-- ./wrapper -->





      <? include_once('_js.php');?>
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

      <script>
      $(document).ready(function (){
   var table = $('#example1').DataTable({
      'ajax': '/lab/jquery-datatables-checkboxes/ids-arrays.txt',
      'columnDefs': [
         {
            'targets': 0,
            'checkboxes': {
               'selectRow': true
            }
         }
      ],
      'select': {
         'style': 'multi'
      },
      'order': [[1, 'asc']]
   });


   // Handle form submission event
   $('#frm-example').on('submit', function(e){
      var form = this;

      var rows_selected = table.column(0).checkboxes.selected();

      // Iterate over all selected checkboxes
      $.each(rows_selected, function(index, rowId){
         // Create a hidden element
         $(form).append(
             $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'id[]')
                .val(rowId)
         );
      });
   });
});

      </script>
    </body>

    <!-- Sort Table by fnSort----------------------------------------------------------------->


    <script>/*
    $(document).ready(function() {
    var oTable = $('#example1').dataTable();
    oTable.fnSort( [ [0,'asc'] ] );
  });*/
  </script>
