<?
global $__conn;
initDb();
$__hasTbl=false;
if(isset($cfg->tbl)){
  global $__tbl;
  $__tbl=$cfg->tbl;
  $__hasTbl=true;
}
if(isset($cfg->input)){
  $__inputs=$cfg->input;
  foreach($__inputs as $__a){
    if($cfg->method=='get'){
      if($__hasTbl){global $$__a;}
      if(isset($_GET[$__a])){
        $$__a=mysqli_real_escape_string($__conn,stripcslashes($_GET[$__a]));
      }else{$$__a='';}
    }
    if($cfg->method=='post'){
      if($__hasTbl){global $$__a;}
      if(isset($_POST[$__a])){
          $$__a=mysqli_real_escape_string($__conn,stripcslashes($_POST[$__a]));
        }else{$$__a='';}
      }
  }
}
?>