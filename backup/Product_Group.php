<?php
header("content-type: text/html; charset=utf-8");
header ("Expires: Wed, 21 Aug 2013 13:13:13 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");

include "_Connect.php";
$che = '$';
$data = $_GET['data'];
$val = $_GET['val'];

if ($data=='category') {
  echo "<select name='category'class='form-control' id='pcat' onChange=\"dochange('productgroup', this.value);getID();\">";
  echo "<option value='0'> </option>\n";
  $sqlcat = "SELECT DISTINCT [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code] as Cat_Code
  FROM [SGD Inter Trading Co_,Ltd".$che."Item]
  ORDER BY Cat_Code ASC;";
  $rscat = odbc_exec($Conn,$sqlcat);
  while(odbc_fetch_row($rscat)){
  $Cat_Code = odbc_result($rscat,"Cat_Code");
    echo "<option value='$Cat_Code'>$Cat_Code</option>" ;
  }
} else if ($data=='productgroup') {
  echo "<select name='productgroup' id='productgroup' id='pgroup' class='form-control' onChange=\"getID()\">\n";
  echo "<option value='0'> </option>\n";
  $sqlgroup = "SELECT DISTINCT [SGD Inter Trading Co_,Ltd".$che."Item].[Product Group Code] as Group_Code
  FROM [SGD Inter Trading Co_,Ltd".$che."Item]
  WHERE [SGD Inter Trading Co_,Ltd".$che."Item].[Item Category Code] = '$val'
  ORDER BY Group_Code ASC ;";
  $rsgroup = odbc_exec($Conn,$sqlgroup);
  while(odbc_fetch_row($rsgroup)){
  $Group_Code = odbc_result($rsgroup,"Group_Code");
    echo "<option value='$Group_Code'>$Group_Code</option> \n" ;
  }
}
echo "</select>\n";

echo mysql_error();

?>
