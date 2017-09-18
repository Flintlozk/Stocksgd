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


<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
 <script>
      $(function () {
	    $('form').bind('submit', function () {
          $.ajax({
            type: 'post',
            url: '_add_group.php',
            data: $('form').serialize(),
            success: function(result){
					$("#div1").html(result);
				}
          });
          return false;
        });
      });
    </script>

<script type="text/javascript">
function GetID(data1){
if (confirm('Confirm delete.'))
{  <!---Confirm delete -->
	        $.ajax({	<!---GET,POST -->
            type: 'get',
            url: '_delete_group.php?gid=' + data1,
			success: function(result){
			$("#div1").html(result);
				}
			}
			)} <!---END GET,POST -->

}  <!---End Confirm delete -->
</script>



  <button type="button" class="btn btn-primary btn-lg btn-block" style=" width:100%" data-toggle="collapse" data-target="#add">
    <span class="glyphicon glyphicon-plus"></span> เพิ่มหมวดหมู่</a>
  </button>



  <form name="form1" method="post" id="form" >
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





<div id="div1">
<? include("table.php")?>
</div>

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
