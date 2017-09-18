<?
global $__conn; //db connection
$_ =file_get_contents('./lib/data.dat');
$__cfg=json_decode($_);
function initDb(){
  global $__conn;
  if(!isset($__conn)){
    $__conn=mysqli_connect(dbHost,dbUsr,dbPwd);
    q("use `".dbName."`");
    q("set character set utf8");
    q('set collation_connection ="utf8_unicode_ci"');
  }
}
function dInitDb(){
  global $__conn;
  if(isset($__conn)){mysqli_close($__conn);}
}
function q($s,$debug=0){
  global $__conn;
  if($debug){echo $s."<br/>";}
  return mysqli_query($__conn,$s);
}
function f($rs){return mysqli_fetch_assoc($rs);}
function fq($s,$debug=0){
  global $__conn;
  if($debug){echo $s."<br/>";}
  return mysqli_fetch_assoc(mysqli_query($__conn,$s));
}
function e($val){
  global $__conn;
  return mysqli_real_escape_string($__conn,stripcslashes($val));
}
function esc($var){
  global $__conn;
  if(isset($_POST[$var])){
    return mysqli_real_escape_string($__conn,stripcslashes($_POST[$var]));
  }
  if(isset($_GET[$var])){
    return mysqli_real_escape_string($__conn,stripcslashes($_GET[$var]));
  }
}
function i($debug=0,$extra=null){
  global $__tbl;
  if(isset($__tbl)){
  global $__conn;
    $qStr=$vals="";
    $rs=q("describe `$__tbl`");
    while($r=f($rs)){
      global $$r['Field'];
      $f=$r['Field'];
      $v=$$r['Field'];
      if($r['Field']!='id'&&isset($v)){
        if($qStr==''){
          $qStr="insert into `$__tbl`(`$f`";
          $vals="values('$v'";
        }else{
          $qStr.=",`$f`";
          $vals.=",'$v'";
        }
        //trace($r['Field'].' '.$$r['Field']);
      }
    }
    if(isset($extra)&&count($extra)>0){
      foreach( $extra as $a => $b){
        if($qStr==''){
          $qStr="insert into `$__tbl`(`$a`";
          $vals="values('$b'";
        }else{
          $qStr.=",`$a`";
          $vals.=",'$b'";
        }
      }
    }
    $qStr.=') '.$vals.');';
    q($qStr,$debug);
    return mysqli_insert_id($__conn);
  }
  if($debug){echo 'no insert';}
}
function u($debug=0,$extra=null){
  global $__tbl;
  global $id;
  if(isset($__tbl)&&isset($id)&&$id>0){
  global $__conn;
    $qStr="";
    $rs=q("describe `$__tbl`");
    while($r=f($rs)){
      global $$r['Field'];
      $f=$r['Field'];
      $v=$$r['Field'];
      if($r['Field']!='id'&&isset($v)){
        if($qStr==''){
          $qStr="update `$__tbl` set `$f`='$v'";
        }else{
          $qStr.=",`$f`='$v'";
        }
        //trace($r['Field'].' '.$$r['Field']);
      }
    }
    if(isset($extra)&&strlen($extra)>0){
      $qStr.=','.$extra;
    }
    $qStr.=" where id='$id';";
    q($qStr,$debug);
    return mysqli_insert_id($__conn);
  }
  if($debug){echo 'no update';}
}
function genRandom($length = 8){
  $password = "";
  $possible = "2346789bcdfghjkmnpqrsvwxyzABCDEFGHJKLMNPQRTUVWXYZ";
  $i = 0; 
  while ($i < $length) { 
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
    $password .= $char;
    $i++;
  }
  return $password;
}
function numPart($inp){
  return preg_replace("/[^0-9]/","", $inp); 
}
function trace($str){
  echo "$str<br/>";
}
function isLocal(){
  return (substr($_SERVER['REMOTE_ADDR'], 0, 4) == '127.' || $_SERVER['REMOTE_ADDR'] == '::1');
}
?>