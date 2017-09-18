<?
include_once("_Connect.php");
include_once('_header.php');
include_once('_sidebar.php');
?>
<!----------------HEADER---------------------------->
<link rel="stylesheet" href="./plugins/iCheck/all.css">
<link rel="stylesheet" href="./css/checkbox.css">



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

  if($_GET["pg"] !=""){
    $sql ="SELECT [SGD Inter Trading Co_,Ltd".$che."Item].No_,
    [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code],
    [SGD Inter Trading Co_,Ltd".$che."Item].[Product Group Code]
    FROM [SGD Inter Trading Co_,Ltd".$che."Item]
    WHERE [SGD Inter Trading Co_,Ltd".$che."Item].[Product Group Code] = '".$_GET["pg"]."';";
  }else
    if ($_GET["pc"] !=""){
          $sql ="SELECT [SGD Inter Trading Co_,Ltd".$che."Item].No_,
          [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code],
          [SGD Inter Trading Co_,Ltd".$che."Item].[Product Group Code]
          FROM [SGD Inter Trading Co_,Ltd".$che."Item]
          WHERE [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code] = '".$_GET["pc"]."';";
        }else if($_GET["pg"] !="" && $_GET["pc"] !=""){
          $sql="SELECT [SGD Inter Trading Co_,Ltd".$che."Item].No_,
          [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code],
          [SGD Inter Trading Co_,Ltd".$che."Item].[Product Group Code]
          FROM [SGD Inter Trading Co_,Ltd".$che."Item]
          WHERE [SGD Inter Trading Co_,Ltd".$che."Item].[Product Group Code] = '".$_GET["pg"]."'
          AND [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code] = '".$_GET["pc"]."';";
        }else {
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

  $sqlgroup = "SELECT DISTINCT [SGD Inter Trading Co_,Ltd".$che."Item].[Product Group Code] as Group_Code
  FROM [SGD Inter Trading Co_,Ltd".$che."Item]
  ORDER BY Group_Code;";
  $rsgroup = odbc_exec($Conn,$sqlgroup);


?>


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

            <section class="content">
              <div class="row">
                <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>

                <script src="http://code.jquery.com/ui/1.9.1/jquery-ui.min.js" type="text/javascript"></script>
                <script>

$(document).ready(function () {
/* Get the checkboxes values based on the class attached to each check box */
$("#buttonClass").click(function() {
   getValueUsingClass();
});

/* Get the checkboxes values based on the parent div id */
$("#buttonParent").click(function() {
   getValueUsingParentTag();
});
});

function getValueUsingClass(){
/* declare an checkbox array */
var chkArray = [];

/* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
$(".chk:checked").each(function() {
chkArray.push($(this).val());
});

/* we join the array separated by the comma */
var selected;
selected = chkArray.join(',') + ",";

/* check if there is selected checkboxes, by default the length is 1 as it contains one single comma */
if(selected.length > 1){
alert("You have selected " + selected);
}else{
alert("Please at least one of the checkbox");
}
}

function getValueUsingParentTag(){
var chkArray = [];

/* look for all checkboes that have a parent id called 'checkboxlist' attached to it and check if it was checked */
$("#checkboxlist input:checked").each(function() {
chkArray.push($(this).val());
});

/* we join the array separated by the comma */
var selected;
selected = chkArray.join(',') + ",";

/* check if there is selected checkboxes, by default the length is 1 as it contains one single comma */
if(selected.length > 1){
alert("You have selected " + selected);
}else{
alert("Please at least one of the checkbox");
}
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<form method="POST" action="test.php">
<div id="checkboxlist">
  <div><input type="checkbox" name="check[]" value="1" class="chk"> Value 1</div>
  <div><input type="checkbox" name="check[]" value="2" class="chk"> Value 2</div>
  <div><input type="checkbox" name="check[]" value="3" class="chk"> Value 3</div>
  <div><input type="checkbox" name="check[]" value="4" class="chk"> Value 4</div>
  <div><input type="checkbox" name="check[]" value="5" class="chk"> Value 5</div>
  <div>
    <input type="submit" value="Get Value Using Class" id="buttonClass">
    <input type="button" value="Get Value Using Parent Tag" id="buttonParent">
  </div>
</div>
</form>
                <form method="POST" action="add_group_items.php" name="selectitem">
                <div align="center">
                  <div class="col-xs-12 col-md-12">
                    <input type="submit" name="submitbtn" style="width: 100%;" class="btn btn-primary" value="บันทึกรายการสินค้า"/>
                    <input type="hidden" name="textg_ID" style="width: 100%;" class="btn btn-primary" value="<?=$_GET["gid"]?>"/>
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
                <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">
                  <table id="example1" class="table table-bordered table-striped table-hover" width="100%">
                    <thead>
                      <tr>
                        <th width="5%"></th>
                        <th>ชื่อสินค้า</th>
                        <th>Category</th>
                        <th>Group</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?
                        while(odbc_fetch_row($rssss)){
                          //iconv แปลง จาก tis-620 > utf-8
                          $po_No = iconv("tis-620", "utf-8", odbc_result($rs,"No_"));
                          $po_Cat = iconv("tis-620", "utf-8", odbc_result($rs,"Item Category Code"));
                          $po_Group = iconv("tis-620", "utf-8", odbc_result($rs,"Product Group Code"));

                      ?>
                      <tr>
                        <td>
                          <div align='center'>
                          <div class="checkbox">
                            <label style="font-size: 1.5em">
                              <input type="checkbox" name="Select_Item[]" value="<?=$po_No?>/<?=$po_Group?>/<?=$po_Cat?>">
                              <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                            </label>
                          </div>
                        </div>
                        </td>
                        <td><?=$po_No?></td>
                        <td><?=$po_Cat?></td>
                        <td><?=$po_Group?></td>
                    <? }?>
                      <tfoot>
                        <tr>

                        </tr>
                      </tfoot>
                    </table>
                    <br>
                    <br>
                      <!----------------FOOTER---------------------------->
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->

            </section><!-- /.content -->
          </div><!-- /.content-wrapper -->

        </div><!-- ./wrapper -->




        <? odbc_close($Conn);?>
        <? include_once('_js.php');?>
        <script type="text/javascript">
        	function orderSelect(){
            var gid  = document.getElementById('gid').value
        		var pgroup  = document.getElementById('pgroup').value
        		var pcat  = document.getElementById('pcat').value
        		//-------check function ------------'
              if(gid !='') {var gid = '&gid='+ gid}
        			if(pgroup !='') {var pgroup = '&pg='+ pgroup}
        			if(pcat !='') {var pcat = '&pc='+ pcat}
        		window.location="group_items.php?" + gid + pgroup + pcat;
        		}
        </script>
        <script>
        var isBusy=false;
        var currIdx=0;
        var currPo='';
        var tbl;
        $(function () {
          tbl=$("#example1").DataTable({
            "iDisplayLength": 50
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
