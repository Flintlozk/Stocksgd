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
$gsql = "SELECT temp_group_name.g_id as g_ID, temp_group_name.g_name as g_Name FROM temp_group_name WHERE temp_group_name.g_id = '$_GET[gid]';";
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

    <!----- Header------------->
    <div class="box box-primary">
      <div class="box-body">
        <div class="row">


          <!----- Edit ------------->
          <form method="POST" action="edit_group_name.php"/>
          <div class="col col-xs-12 col-md-12" style="align=center; margin:5px 0px 0px 0px">
            <label>
              ชื่อหมวดหมู่
            </label>
            <div class="form-group">
              <input type="hidden" class="form-control" id="textg_Name" name="textg_ID" value="<?=$_GET["gid"]?>"/>
              <input type="text" class="form-control" id="textg_Name" name="textg_Name" value="<?=$g_Name?>" autofocus required/>
            </div>
            <div class="form-group">
              <button class="btn btn-primary btn-block" onclick="window.document.location='#'">แก้ไข</button>
            </div>
          </div>
        </form>
        <!----- End Edit / Delete-------------



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

</body>

<!-- Sort Table by fnSort----------------------------------------------------------------->


<script>/*
$(document).ready(function() {
var oTable = $('#example1').dataTable();
oTable.fnSort( [ [0,'asc'] ] );
});*/
</script>
