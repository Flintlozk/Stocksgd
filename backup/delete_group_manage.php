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
            while(odbc_fetch_array($grs)){
              $sqlser ="DELETE FROM temp_item_group WHERE temp_item_group.g_id = '$_GET[gid]';";
              $queryser=odbc_exec($Conn,$sqlser);
            }

              $sqlg ="DELETE FROM temp_group_name WHERE temp_group_name.g_id = '$_GET[gid]';";
              $queryg=odbc_exec($Conn,$sqlg);
              ?>
              <div class="loading">Loading</div>
              <?
                print "<SCRIPT>window.location='index.php'</SCRIPT>";
            ?>


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
