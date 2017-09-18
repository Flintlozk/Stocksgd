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
$sql = "SELECT temp_group_name.g_id as g_ID, temp_group_name.g_name as g_Name FROM temp_group_name;";
$rs = odbc_exec($Conn,$sql);
odbc_fetch_array($rs);
$g_ID = odbc_result($rs,"g_ID");
$g_Name = odbc_result($rs,"g_Name");

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

            if($g_Name == $_POST["textg_Name"]){
              echo "<SCRIPT>alert('ชื่อหมวดหมู่ซ้ำ');</SCRIPT>";
              print "<SCRIPT>window.history.back();</SCRIPT>";
            } else {
              $sqlser="insert into temp_group_name (temp_group_name.g_name) values ('".$_POST["textg_Name"]."')";
              $queryser=odbc_exec($Conn,$sqlser);
              //$queryser=mysql_query($sqlser);
              ?>
              <div class="loading">Loading</div>
              <?
                print "<SCRIPT>window.location='index.php'</SCRIPT>";
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
