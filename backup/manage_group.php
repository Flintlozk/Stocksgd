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
<script type="text/javascript">
function GetID(data1){
  if (confirm('Confirm delete.'))
  {  <!---Confirm delete -->
    $.ajax({	<!---GET,POST -->
      type: 'get',
      url: '_delete_item.php?gid=' + <?=$_GET['gid']?> + '&item_id=' + data1,
      success: function(result){
        $("#div1").html(result);
      }
    }
  )} <!---END GET,POST -->

}  <!---End Confirm delete -->
</script>

<?

$gsql = "SELECT temp_group_name.g_id, temp_group_name.g_name as g_Name FROM temp_group_name WHERE temp_group_name.g_id = '$_GET[gid]';";
$grs = odbc_exec($Conn,$gsql);
odbc_fetch_array($grs);
$g_Name = odbc_result($grs,"g_Name");

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

          <div id="div1">
            <? require("manage_table.php") ?>
          </div>

          <!----------------FOOTER---------------------------->
        </div>
      </div>
    </div>
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->






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


</body>

<!-- Sort Table by fnSort----------------------------------------------------------------->


<script>/*
$(document).ready(function() {
var oTable = $('#example1').dataTable();
oTable.fnSort( [ [0,'asc'] ] );
});*/
</script>
