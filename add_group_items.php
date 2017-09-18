<?
include_once('_header.php');
include_once('_sidebar.php');

require("_Connect.php");

date_default_timezone_set('Asia/Bangkok');
?>
<link rel="stylesheet" href="./css/loading.css">
<style>
.keyInChk{
  transform:scale(1.4);
}
</style>
<?
//SQL Query Section
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page xheader) -->
  <section class="content-header">
    <h1>

    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-primary">
          <div class="box-body">
            <?
            //$po_No $po_Group $po_Cat
            if(empty($_POST["Select_Item"])){
              print "<SCRIPT>alert('กรุณาเลือกรายการสินค้า');</SCRIPT>";
              print "<SCRIPT>window.location='group_items.php?gid=$_POST[textg_ID]'</SCRIPT>";

            }else {
              foreach($_POST["Select_Item"] as $Selectitem){
                $itemlist = explode("|",$Selectitem);

                $sqlser="insert into temp_item_group (temp_item_group.pro_groups,temp_item_group.pro_types,temp_item_group.po_id,temp_item_group.g_id)
                        values ('".$itemlist['1']."','".$itemlist['2']."','".$itemlist['0']."','".$_POST['textg_ID']."');";
                $rs = odbc_exec($Conn,$sqlser);

              //echo $sql="insert into item_group (po_ID,g_ID) values ('".$Selectitem."','".$_POST["textg_ID"]."');";
              //$query=mysql_query($sql);
            }
              ?>
              <div class="loading">Loading</div>
              <?
                print "<SCRIPT>window.location='manage_group.php?gid=$_POST[textg_ID]'</SCRIPT>";
              }?>




          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div><!-- /.col -->
    </div><!-- /.row -->

  </section><!-- /.content -->
</div><!-- /.content-wrapper -->

</div><!-- ./wrapper -->

<? include_once('_js.php');?>




</body>
</html>
