<?
include_once("_Connect.php");
include_once('_header.php');
include_once('_sidebar.php');

?>
<!----------------HEADER---------------------------->
<link rel="stylesheet" href="./plugins/iCheck/all.css">
<link rel="stylesheet" href="./css/checkbox.css">
<link rel="stylesheet" href="./css/style.css">


<?
$gsql = "SELECT temp_group_name.g_id, temp_group_name.g_name as g_Name
FROM temp_group_name
WHERE temp_group_name.g_id = '$_GET[gid]';";
$grs = odbc_exec($Conn,$gsql);
odbc_fetch_array($grs);
$g_Name = odbc_result($grs,"g_Name");

$che = '$';
/*echo $sql="SELECT [SGD Inter Trading Co_,Ltd".$che."Item].No_,
[SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code],
[SGD Inter Trading Co_,Ltd".$che."Item].[Product Group Code]
FROM [SGD Inter Trading Co_,Ltd".$che."Item]";*/

if($_GET["pg"] !="" && $_GET["pc"] !=""){
  $sql="SELECT [SGD Inter Trading Co_,Ltd".$che."Item].No_,
  [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code],
  [SGD Inter Trading Co_,Ltd".$che."Item].[Product Group Code]
  FROM [SGD Inter Trading Co_,Ltd".$che."Item]
  WHERE [SGD Inter Trading Co_,Ltd".$che."Item].[Product Group Code] = '".$_GET["pg"]."'
  AND [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code] = '".$_GET["pc"]."';";
}else
if($_GET["pc"] !="" ){
  $sql ="SELECT [SGD Inter Trading Co_,Ltd".$che."Item].No_,
  [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code],
  [SGD Inter Trading Co_,Ltd".$che."Item].[Product Group Code]
  FROM [SGD Inter Trading Co_,Ltd".$che."Item]
  WHERE [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code] = '".$_GET["pc"]."';";
}else{
  $sql="SELECT [SGD Inter Trading Co_,Ltd".$che."Item].No_,
  [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code],
  [SGD Inter Trading Co_,Ltd".$che."Item].[Product Group Code]
  FROM [SGD Inter Trading Co_,Ltd".$che."Item]";
}
$rs = odbc_exec($Conn,$sql);

$sqlcat = "SELECT DISTINCT [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code] as Cat_Code
FROM [SGD Inter Trading Co_,Ltd".$che."Item]
ORDER BY Cat_Code ASC;";
$rscat = odbc_exec($Conn,$sqlcat);

if($_GET["pc"]){
  $sqlgroup = "SELECT DISTINCT [SGD Inter Trading Co_,Ltd".$che."Item].[Product Group Code] as Group_Code
  FROM [SGD Inter Trading Co_,Ltd".$che."Item]
  WHERE [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code] = '".$_GET["pc"]."'
  ORDER BY Group_Code;";
  $rsgroup = odbc_exec($Conn,$sqlgroup);
}
?>
<div id='page'></div>
<div id="loading"></div>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Content Header (Page xheader) -->
  <section class="content-header">
    <h1><?=$g_Name?></h1> <input type="hidden" id="gid" value="<?=$_GET["gid"]?>"/>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-primary">
          <div class="box-body">
            <!----------------END HEADER---------------------------->
            <!----------------CONTENT GOES HERE---------------------------->


            <div class="row">

              <form method="POST" action="add_group_items.php" name="selectitem">

                <div align="center">
                  <div class="col-xs-12 col-md-12">

                    <a type="button" class="btn btn-primary" style="width: 100%;" data-toggle="modal" data-target="#myModal">
                      <span class="glyphicon glyphicon-shopping-cart"></span>
                      ตระกร้า
                    </a>

                    <a href="#" class="float" data-toggle="modal" data-target="#myModal">
                      <span class='badge badge-old my-float'>
                        <div id="count">
                          <? include('_update.php');?>
                        </div>
                      </span>
                    </a>

                  </div>
                </div>

                <br><br>


                <div class="col-xs-6 col-md-6">
                  <label>
                    Category
                  </label>
                  <select  class="form-control" id="pcat" name="pcat" onchange="orderSelect()">
                    <? while(odbc_fetch_row($rscat)){
                      $Cat_Code = odbc_result($rscat,"Cat_Code");?>
                      <option value="<?=$Cat_Code?>" <? if($_GET["pc"] == $Cat_Code){echo 'selected'; }?>><?=$Cat_Code?></option>
                    <? }?>
                  </select>
                </div>

                <div class="col-xs-6 col-md-6">
                  <label>
                    Product Group
                  </label>

                  <select  class="form-control" id="pgroup" name="pgroup" onchange="orderSelect()">
                    <? while(odbc_fetch_row($rsgroup)){
                      $Group_Code = odbc_result($rsgroup,"Group_Code");?>
                      <option value="<?=$Group_Code?>" <? if($_GET["pg"] == $Group_Code){echo 'selected'; }?>><?=$Group_Code?></option>
                    <? }?>
                  </select>
                </div>

                <br><br><br><br>

                <div class="container-fluid">
                  <div class="row">
                    <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">



                      <table id="example1" class="table table-bordered table-striped" style="" width="100%">
                        <thead>
                          <tr>
                            <th width="30%">ชื่อสินค้า</th>
                            <th width="30%">Category</th>
                            <th width="30%">Group</th>
                            <th width="30%">เลือก</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?
                          while(odbc_fetch_row($rs)){
                            //iconv แปลง จาก tis-620 > utf-8
                            $po_No = iconv("tis-620", "utf-8", odbc_result($rs,"No_"));
                            $po_Cat = iconv("tis-620", "utf-8", odbc_result($rs,"Item Category Code"));
                            $po_Group = iconv("tis-620", "utf-8", odbc_result($rs,"Product Group Code"));

                            ?>
                            <tr style="height:1px;">
                              <!--
                              <td width="1%">
                              <div align='center'>
                              <input type="checkbox" id="Select_Item" name="Select_Item[]" value="<?=$po_No?>/<?=$po_Group?>/<?=$po_Cat?>">
                            </div>
                          </td>
                        -->
                        <td style="white-space: normal !important; word-break: break-all;  ">
                          <div align="left">
                            <div style="font-size:12px">
                              <?=$po_No?>
                            </div>
                          </div>
                        </td>
                        <td style="white-space: normal !important; word-break: break-all;  ">
                          <div align="left">
                            <div style="font-size:12px">
                              <?=$po_Cat?>
                            </div>
                          </div>
                        </td>
                        <td style="white-space: normal !important; word-break: break-all;  ">
                          <div align="left">
                            <div style="font-size:12px">
                              <?=$po_Group?>
                            </div></div>
                          </td>
                          <td style="white-space: normal !important; word-break: break-all;  ">

                            <a class="btn btn-primary btn-sm glyphicon glyphicon-ok"
                            href="javascript:GET_C('<?=$_GET["gid"]?>','<?=$po_No?>','<?=$po_Group?>','<?=$po_Cat?>','add'),Updatecart('<?=$_GET["gid"]?>')"></a>

                          </td>
                        </tr>
                      <? }?>
                      <tfoot>
                        <tr>

                        </tr>
                      </tfoot>
                    </table>
                    <br><br>
                  </div><!-- /.box-body -->
                </div>
              </div>
            </form>

            <!----------------FOOTER---------------------------->
          </div><!-- /.box -->


        </div><!-- /.col -->
      </div><!-- /.row -->

    </div><!-- /.content-wrapper -->
  </div>
</section><!-- /.content -->
</div><!-- ./wrapper -->


<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog" style="width: 80%;">
    <!-- Modal content-->
    <div class="modal-content">

      <div id="div1">
        <? require("cart.php") ?>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" onclick="window.document.location='manage_group.php?gid=<?=$_GET['gid']?>'">ยืนยัน</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
      </div>

    </div>

  </div>
</div>

</div>





<? odbc_close($Conn);?>
<? include_once('_js.php');?>

<script>
//Function --> Send Get to URL --> Get Result on success Back to DIV
//Add Item
function GET_C(data1,data2,data3,data4,data5){
  $.get("_update.php?agid=" + data1 + "&apno=" + data2 + "&apgo=" + data3 + "&apco=" + data4 + "&status=" + data5,function(data){$("#count").html (data)})
}
function Updatecart(data1){
  $.get("_update_cart.php?gid=" + data1,function(table){$("#div1").html (table)})
}
//Delete Item
function GetID(data1,data2,data3){
  $.get('_delete_cart.php?gid=' + data1 + '&item_id=' + data2 + '&status=' + data3,function(result){$("#div1").html(result)})
}

function Updatebadge(data1,data2){
  $.get("_update.php?gid=" + data1 + '&status=' + data2,function(badge){$("#count").html (badge)})
}
</script>

<script>
function onReady(callback) {
  var intervalID = window.setInterval(checkReady, 1000);

  function checkReady() {
    if (document.getElementsByTagName('body')[0] !== undefined) {
      window.clearInterval(intervalID);
      callback.call(this);
    }
  }
}

function show(id, value) {
  document.getElementById(id).style.display = value ? 'block' : 'none';
}

onReady(function () {
  show('page',false);
  show('loading', false);
});
</script>

<script type="text/javascript">
//Sorting Table by Category and Product Group
function orderSelect(){
  var gid  = document.getElementById('gid').value
  var pcat  = document.getElementById('pcat').value
  var pgroup  = document.getElementById('pgroup').value
  //-------check function ------------'
  if(gid !='') {var gid = 'gid='+ gid}
  if(pcat !='') {var pcat = '&pc='+ pcat}
  if(pgroup !='') {var pgroup = '&pg='+ pgroup}
  window.location="group_items.php?" + gid + pcat + pgroup;
}
</script>

<script>
//Datatable Enabled
var isBusy=false;
var currIdx=0;
var currPo='';
var tbl;
$(function () {
  tbl=$("#example1").DataTable({
    "iDisplayLength": 100
  });
});
</script>


</body>
