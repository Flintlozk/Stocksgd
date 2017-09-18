<?
include_once(dirname(__FILE__).'/../../cfg/cfg.php');
if (substr($_SERVER['REMOTE_ADDR'], 0, 4) == '127.' || $_SERVER['REMOTE_ADDR'] == '::1'){
  if(filemtime('.\dist\css\style.less')>filemtime('.\dist\css\style.css')){
    exec('lessc -x .\dist\css\style.less > .\dist\css\style.css');
  }
}
$__perms=array('product'=>'สินค้า','price'=>'ราคาสินค้า','order'=>'คำสั่งซื้อ','usr'=>'ลูกค้า','news'=>'ข่าวสาร','adminUsr'=>'แอดมินผู้ใช้งานระบบ');
?>
