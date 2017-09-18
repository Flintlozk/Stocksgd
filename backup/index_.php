<?
include_once("_Connect.php");
include_once('_header.php');
include_once('_sidebar.php');
include_once('_header1.php');
?>
<style>
.badge {
  padding: 1px 9px 2px;
  font-size: 12.025px;
  font-weight: bold;
  white-space: nowrap;
  color: #ffffff;
  background-color: #999999;
  -webkit-border-radius: 9px;
  -moz-border-radius: 9px;
  border-radius: 9px;
}
.badge:hover {
  color: #ffffff;
  text-decoration: none;
  cursor: pointer;
}
.badge-new {
  background-color: #b94a48;
}
.badge-new:hover {
  background-color: #953b39;
}
.badge-old {
  background-color: #3a87ad;
}
.badge-old:hover {
  background-color: #2d6987;
}
.keyInChk{
  transform:scale(1.4);
}
</style>



  <button type="button" class="btn btn-primary btn-lg btn-block" style=" width:100%" data-toggle="collapse" data-target="#add">
    <span class="glyphicon glyphicon-plus"></span> เพิ่มหมวดหมู่</a>
  </button>



  <form name="frmMain" method="post" action="add_group.php">
    <div class="collapse" id="add">
      <br>
      <div class="form-group">
        <label>
          ชื่อหมวดหมู่ <font color="red"></font>
        </label>
        <input type="text" class="form-control" name="textg_Name" required />

      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary btn-block" name="submittype" value="ยืนยัน">
      </div>
    </div>
  </form>


  <table width="100%">

    <?
    $sql = "SELECT temp_group_name.g_id as g_ID, temp_group_name.g_name as g_Name FROM temp_group_name;";
    $rs = odbc_exec($Conn,$sql);

    while(odbc_fetch_row($rs)){
      $g_ID = odbc_result($rs,"g_ID");
      $g_Name = odbc_result($rs,"g_Name");

      $csql = "SELECT Count(temp_item_group.item_id) AS ICount, temp_item_group.g_id FROM temp_item_group WHERE temp_item_group.g_id = $g_ID GROUP BY temp_item_group.g_id;;";
      $crs = odbc_exec($Conn,$csql);
      odbc_fetch_array($crs);
      $ICount = odbc_result($crs,"ICount");

      ?>



      <tr>
        <td width="70%">
          <div style=" margin:5px 0px 0px 0px">
            <button type="button" class="btn btn-success" style=" width:98%" onclick="window.document.location='./manage_group.php?gid=<?=$g_ID?>'">
              <?
              echo $g_Name;
              if (!empty($ICount)){
                echo " ";
                echo "<span class='badge badge-new'>";
                echo  $ICount;
                echo "</span>";
              }
              ?>
            </button>
          </div>
        </td>
        <td  width="15%">
           <a href='edit_group_manage.php?gid=<?=$g_ID?>'>
          <div style=" margin:5px 0px 0px 0px">
            <button type="button" id="editbtn_<?=$g_ID?>" name="" class="btn btn-warning" style=" width:95%"/>
             <span class="glyphicon glyphicon-edit"></span> 
          </button>
        </div>
      </a>
      </td>
      <td  width="15%">
        <div style=" margin:5px 0px 0px 0px">
          <a href="delete_group_manage.php?gid=<?=$g_ID?>">
            <button type="button" name="" class="btn btn-danger" style=" width:95%" onclick="return confirm('Are you sure you want to delete group ?')"/>
             <span class="glyphicon glyphicon-trash"></span> 
          </button>
        </a>
        </div>
      </td>

    </tr>




  <? }?>

</table>


<? include_once('_foot1.php');?>

<script type="text/javascript">
$(document).ready(function(){
 $("#editbtn_<?=$g_ID?>").click(function(){
   var
   alert(this.id);
   $.post("test3.php", {
   data1: $("#txt1").val()},
    function(result){
     $("#editg_Name").html(result);
    }
   );

  });
 });
</script>

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
