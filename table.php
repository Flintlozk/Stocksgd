

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
        <td  width="90%">
          <div style=" margin:5px 0px 0px 0px">
            <button type="button" class="btn btn-success" style=" width:99%" onclick="window.document.location='./manage_group.php?gid=<?=$g_ID?>'">
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
        <td  width="4%">
        

        
          <div style=" margin:5px 5px 0px 0px">
          
     <a href="edit_group_manage.php?gid=<?=$g_ID;?>" class="glyphicon glyphicon-edit btn btn-warning">  </a>
          
          
        </div>
      </td>
      <td  width="4%">
        <div style=" margin:5px 0px 0px 0px">
        
        <a href="javascript:GetID(<?=$g_ID;?>);" class="glyphicon glyphicon-trash btn btn-danger"></a>
        
        </div>
      </td>

    </tr>




  <? }?>

</table>