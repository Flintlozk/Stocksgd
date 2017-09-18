<?//echo 'util.php';
if(isset($_GET['todo'])){if(isset($__cfg->get->$_GET['todo'])){
    $__task = preg_replace("/[^A-Za-z0-9]/", "", $_GET['todo']);
    if(!isset($__cfg->get->$__task->fn)){$__cfg->get->$__task->fn=$__task;}
    $__cfg->get->$__task->method='get';
    $_=$__cfg->get->$__task->fn;$_($__cfg->get->$__task);
}}
if(isset($_POST['todo'])){if(isset($__cfg->post->$_POST['todo'])){
    $__task = preg_replace("/[^A-Za-z0-9]/", "", $_POST['todo']);
    if(!isset($__cfg->post->$__task->fn)){$__cfg->post->$__task->fn=$__task;}
    $__cfg->post->$__task->method='post';
    $_=$__cfg->post->$__task->fn;$_($_=$__cfg->post->$__task);
}}
function exampleFunc($cfg){
  include('./lib/sanitize.php');
  dInitDb();die;
}
function cancelPO($cfg){
  include('./lib/sanitize.php');
  perm(array('order'));
  $r=fq("select cartStat,stat from cart where id='$id'");
  if($r['cartStat']=='paid'&&$r['stat']='successful'){
    q("update cart set cartStat='canceled',cancelTxt='$cancelTxt' where id='$id'");
    // roll back inventory  มั๊ย ?
    cancelPOMail($id);
  }
  echo 'ok';
  dInitDb();die;
}
function deliveringPO($cfg){
  include('./lib/sanitize.php');
  perm(array('order'));
  $r=fq("select cartStat,stat from cart where id='$id'");
  if($r['cartStat']=='paid'&&$r['stat']='successful'){
    q("update cart set cartStat='delivering' where id='$id'");
  }
  echo 'ok';
  dInitDb();die;
}
function deliveredPO($cfg){
  include('./lib/sanitize.php');
  perm(array('order'));
  $r=fq("select cartStat,stat from cart where id='$id'");
  if($r['cartStat']=='delivering'&&$r['stat']='successful'){
    q("update cart set cartStat='delivered',deliverTxt='$deliverTxt' where id='$id'");
    deliveredPOMail($id);
  }
  echo 'ok';
  dInitDb();die;
}
function deliveredPOMail($id){
  $r=fq("select deliverTxt,mail,whichSite,poId from cart left join usr on usrId=usr.id where cart.id='$id'");
  $usrMail=$r['mail'];
  $whichSite=$r['whichSite'];
  $mailHost=($whichSite=='Fujiko')?FKmailHost:KPmailHost;
  $mailUsr=($whichSite=='Fujiko')?FKmailUsr:KPmailUsr;
  $mailPwd=($whichSite=='Fujiko')?FKmailPwd:KPmailPwd;
  $mailFrom=($whichSite=='Fujiko')?FKmailFrom:KPmailFrom;
  $descr='<span style="color: darkgreen;font-weight: bold;font-size: 110%;">เราได้ทำการจัดส่งสินค้าตามคำสั่งซื้อหมายเลข _poId_ แล้ว';
  if(strlen($r['deliverTxt'])>0){
    $descr.='<br/><span style="color:black;">ข้อมูลเพิ่มเติม :: '.str_replace("\n",'<br/>',$r['deliverTxt']).'</span>';
  }
  $descr.="</span>";
  list($subject,$msg)=genPOMail($id,'',$descr);
  $subject=str_replace('::','ได้รับการจัดส่งแล้ว ::',$subject);
  
  require_once(dirname(__FILE__).'/class.phpmailer.php');
  genPoMail($id);
  $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
  $mail->IsSMTP(); // telling the class to use SMTP$mail->SMTPDebug = 1;
  try {
    $mail->SMTPDebug  = false;                     // enables SMTP debug information (for testing)
    $mail->CharSet   = "utf-8";                  // enable SMTP authentication
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    //$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
    $mail->Host       = $mailHost;      // sets GMAIL as the SMTP server
    $mail->Username   = $mailUsr;  // GMAIL username
    $mail->Password   = $mailPwd;            // GMAIL password
    $mail->AddReplyTo($mailUsr,$mailFrom);
    $mail->SetFrom($mailUsr,$mailFrom);
    $mail->AddAddress($usrMail);
    $ccMails=json_decode(file_get_contents('http://fujiko.co.th/admin/ccMail.json'),true);
    $ccMails=explode(',',$ccMails['po']);
    for($i=0;$i<count($ccMails);$i++){
      $mail->AddAddress(trim($ccMails[$i]));
    }
    $mail->Subject = $subject;
    $mail->MsgHTML($msg);
    $mail->Send();
    //echo "Message Sent OK<p></p>\n";
  } catch (phpmailerException $e) {
    //echo $e->errorMessage(); //Pretty error messages from PHPMailer
  } catch (Exception $e) {
    //echo $e->getMessage(); //Boring error messages from anything else!
  }
}
function cancelPOMail($id){
  $r=fq("select cancelTxt,mail,whichSite from cart left join usr on usrId=usr.id where cart.id='$id'");
  $usrMail=$r['mail'];
  $whichSite=$r['whichSite'];
  $mailHost=($whichSite=='Fujiko')?FKmailHost:KPmailHost;
  $mailUsr=($whichSite=='Fujiko')?FKmailUsr:KPmailUsr;
  $mailPwd=($whichSite=='Fujiko')?FKmailPwd:KPmailPwd;
  $mailFrom=($whichSite=='Fujiko')?FKmailFrom:KPmailFrom;
  
  list($subject,$msg)=genPOMail($id,'ยกเลิก','<span style="color: crimson;font-weight: bold;font-size: 110%;">เราได้ทำการยกเลิกคำสั่งซื้อหมายเลข _poId_ แล้ว<br/> สาเหตุ :: '.str_replace("\n",'<br/>',$r['cancelTxt'])."</span>");
  
  require_once(dirname(__FILE__).'/class.phpmailer.php');
  genPoMail($id);
  $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
  $mail->IsSMTP(); // telling the class to use SMTP$mail->SMTPDebug = 1;
  try {
    $mail->SMTPDebug  = false;                     // enables SMTP debug information (for testing)
    $mail->CharSet   = "utf-8";                  // enable SMTP authentication
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    //$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
    $mail->Host       = $mailHost;      // sets GMAIL as the SMTP server
    $mail->Username   = $mailUsr;  // GMAIL username
    $mail->Password   = $mailPwd;            // GMAIL password
    $mail->AddReplyTo($mailUsr,$mailFrom);
    $mail->SetFrom($mailUsr,$mailFrom);
    $mail->AddAddress($usrMail);
    $ccMails=json_decode(file_get_contents('http://fujiko.co.th/admin/ccMail.json'),true);
    $ccMails=explode(',',$ccMails['po']);
    for($i=0;$i<count($ccMails);$i++){
      $mail->AddAddress(trim($ccMails[$i]));
    }
    $mail->Subject = $subject;
    $mail->MsgHTML($msg);
    $mail->Send();
    //echo "Message Sent OK<p></p>\n";
  } catch (phpmailerException $e) {
    //echo $e->errorMessage(); //Pretty error messages from PHPMailer
  } catch (Exception $e) {
    //echo $e->getMessage(); //Boring error messages from anything else!
  }
}

function genPOMail($id,$preHead,$descr){
  $msg='';
  $head=$preHead;
  if($r=fq("select * from cart where id=$id")){
    $descr=str_replace('_poId_',$r['poId'],$descr);
    $uName=$r['aName'];
    if($r['baName']!='')$uName=$r['baName'];
    $whichSite=$r['whichSite'];
    $color='green';
    $logo='http://kenprocctv.com/img/logo.png';
    $head.='คำสั่งซื้อหมายเลข '.$r['poId'].' :: Official Kenpro CCTV';
    if($r['whichSite']=='Fujiko'){
      $color='midnightblue';
      $logo='http://fujiko.co.th/img/logo.png';
      $head.='คำสั่งซื้อหมายเลข '.$r['poId'].' :: Official Fujiko CCTV';
    }
    $usrId=$r['usrId'];
    $taxId=$r['taxId'];
  $msg='<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
      .total{
        color:'.$color.';
        font-weight: bold;
      }
      h1{
        font-size: 20px;
        font-weight: bold;
        letter-spacing: 0.5px;
        color:'.$color.';
      }
      .heading{
        padding-top:30px ;
        margin-bottom: 15px;
        border-bottom: 1px solid black;
        font-weight: bold;
      }
      .poTbl{
        margin-top: 0px;
        width: 100%;
      }
      .td1{
        width:140px;
      }
      .td2{
        width: 100%;
      }
      .td3{
        font-weight: bold;
        text-align: right !important;
        white-space: nowrap;
      }
      .poTbl td{
        border-top: 1px dashed #bbb;
        padding:15px 10px 10px 10px;
        text-align: left;
        vertical-align: top;
      }
      .poTbl img{
        max-width: 120px;
        padding: 5px;
      }
      .fTd{
        border-top:0 none !important;
      }
      .poTbl .summary td{
        border-top: 1px solid #bbb;
      }
      .std1{
        text-align: right !important;
        white-space: nowrap;
      }
      .std2{
        text-align: right !important;
        white-space: nowrap;
      }
      .footer{
        text-align: center;
        margin-top:30px;
        color:#aaa;
      }
      .footer a{
        color:inherit;
      }
    </style>
  </head>
  <body style="color:#333;
        max-width: 680px;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        display: block;
        margin-left: auto;
        margin-right: auto;
        font-family: Helvetica,Arial,sans-serif;">
    <div><img src="'.$logo.'"/></div>
    <h1>สวัสดีคุณ '.$uName.',</h1>
    <div>'.$descr.'</div>
    <div class="heading">รายละเอียดการสั่งซื้อ</div>
    <table class="poTbl" cellpadding=0 cellspacing="0">';
      $rs2=q("select pId,cartitem.amount,price,pName,uri from cartitem left join product on product.id=pId where cartId='$id' order by cartitem.id asc");
      while($r2=f($rs2)){
        $pId=$r2['pId'];
        $img='';
        if($r3=fq("select url from prodimg where pId='$pId' order by sortIdx asc limit 1")){
          $img='<img src="http://fujiko.co.th'.$r3['url'].'"/>';
        }
      $msg.='<tr>
        <td class="td1 fTd">'.$img.'</td>
        <td class="td2 fTd">'.$r2['pName'].'<br/>คำอธิบาย<br/>จำนวน : '.$r2['amount'].'</td>
        <td class="td3 fTd">'.$r2['price'].' ฿</td>
      </tr>';
      }
      $creditAmount=$r['creditAmount'];
      $cardAmount=$r['fullAmount']-$creditAmount;
      $msg.='
      <tr class="summary"><td></td>
        <td class="std1">ราคาสินค้า :<br/>ค่าจัดส่ง :<br/>รวม :<br/>';
        $msg.='<span style="color:red;">** ราคาดังกล่าวรวมภาษีแล้ว 7%</span><br/><br/>';
        if($creditAmount>0)
          $msg.='** ชำระด้วย Dealer\'s Credit :<br/>';
        if($cardAmount>0)
          $msg.='** ชำระด้วยบัตรเครดิตร :<br/>';
        $msg.='</td>
        <td class="std2">'.number_format($r['prodAmount']).' ฿<br/>'.number_format($r['deliAmount']).' ฿<br/><span class="total">'.number_format($r['fullAmount']).' ฿</span><br/><br/><br/>';
        if($creditAmount>0)
          $msg.=number_format($creditAmount).' ฿<br/>';
        if($cardAmount>0)
          $msg.=number_format($cardAmount).' ฿<br/>';
        $msg.='</td>
      </tr>
    </table>
    <div class="heading">การจัดส่งสินค้า</div>
    <div>'.$r['aName'].' ( '.$r['tel'].' )<br/>'.$r['addr1'].'<br/>'.$r['addr2'].'<br/>'.$r['d'].' '.$r['a'].'<br/>'.$r['p'].'<br/>'.$r['zip'].'<br/>';
    if($r['taxId']!='')
      $msg.='
    <div class="heading">ที่อยู่สำหรับออกใบกำกับภาษี</div>
    <div>หมายเลขประจำตัวผู้เสียภาษี'.$r['taxId'].'<br/>'.$r['baName'].' ( '.$r['btel'].' )<br/>'.$r['baddr1'].'<br/>'.$r['baddr2'].'<br/>'.$r['bd'].' '.$r['ba'].'<br/>'.$r['bp'].'<br/>'.$r['bzip'].'<br/>';
    $msg.='
    <div class="footer">หากท่านมีปัญหาหรือข้อสงสัยเกี่ยวกับคำสั่งซื้อนี้<br/>ท่านสามารถติดต่อได้ที่ <a href="mailto:sgdinter@sgdinter.com">sgdinter@sgdinter.com</a><br/> หรือโทร <a id="officePhone" class="" href="tel:022220559"> 0-2222-0559 (Call Center)</a></div>
  </body>
</html>';
  }
  return array($head,$msg);
}
function saveCCMail($cfg){
  include('./lib/sanitize.php');
  perm(array('adminUsr'));
  $arr=array();
  $arr['po']=$po;
  $arr['contact']=$contact;
  file_put_contents(dirname(__FILE__).'/../ccMail.json',json_encode($arr));
  echo 'ok';
  dInitDb();die;
}
function isKeyIn($cfg){
  include('./lib/sanitize.php');
  perm(array('order'));
  q("update cart set isKeyIn='$val' where id='$id'",1);
  echo 'ok';
  dInitDb();die;
}
function savePromo($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $descr=$_POST['descr'];
  $rs=q("select * from promoimg where which='Fujiko'");
  while($r=f($rs)){
    $id=$r['id'];
    $url=$r['url'];
    if(!strpos($descr,$url)){
      unlink(dirname(__FILE__).'/../..'.$url);
      q("delete from promoimg where id='$id'");
    }
  }
  file_put_contents(dirname(__FILE__).'/../../gen/promo.html',$_POST['descr']);
  echo 'ok';
  dInitDb();die;
}
function savePromok($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $descr=$_POST['descr'];
  $rs=q("select * from promoimg where which='Kenpro'");
  while($r=f($rs)){
    $id=$r['id'];
    $url=$r['url'];
    if(!strpos($descr,$url)){
      unlink(dirname(__FILE__).'/../..'.$url);
      q("delete from promoimg where id='$id'");
    }
  }
  file_put_contents(dirname(__FILE__).'/../../gen/promok.html',$_POST['descr']);
  echo 'ok';
  dInitDb();die;
}
function saveGuide($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  file_put_contents(dirname(__FILE__).'/../../gen/guide.js','var chartData ='.$_POST['data'].';');
  echo 'ok';
  genConfig();
  dInitDb();die;
}
function saveGuideKen($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  file_put_contents(dirname(__FILE__).'/../../gen/guideKen.js','var chartData ='.$_POST['data'].';');
  echo 'ok';
  genConfig();
  dInitDb();die;
}
function delGuideImg($cfg){
  dInitDb();die;
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('product'));
  if($r=fq("select id from guideimg where url='$url'")){
    $id=$r['id'];
    unlink(dirname(__FILE__).'/../..'.$url);
    q("delete from guideimg where id='$id'");
  }
  echo 'ok';
  dInitDb();die;
}
function saveGuideImg($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('product'));
  q("insert into guideimg(url) values('')");
  $id=mysqli_insert_id($__conn);
  $dest="/upload/guide/$id".'_'.genRandom().'.png';
  file_put_contents('..'.$dest, base64_decode(substr($_POST['img'], strpos($_POST['img'], ",")+1)));
  q("update guideimg set url='$dest' where id='$id'");
  echo json_encode(array('stat'=>'ok','id'=>$id,'url'=>$dest));
  dInitDb();die;
}
function doneGenKenCfg($cfg){
  include('./lib/sanitize.php');
  perm();
  file_put_contents(dirname(__FILE__).'/../_genKenCfg.php','<?$need2genKenCfg=false;?>');
  echo 'ok';
  dInitDb();die;
}
function delPointRule($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('price'));
  $stat='ok';
  $msg='';
  $r=fq("select count(id)as c from product where pointRuleId='$id'");
  if($r['c']>0){
    $stat='error';
    $msg='มีสินค้าใช้กฎนี้อยู่ ';
    $rs=q("select pName from product where pointRuleId='$id' order by pName asc");
    while($r=f($rs)){
      $msg.=$r['pName'].',';
    }
  }else{
    q("delete from pointrules where id='$id'");
  }
  echo json_encode(array('status'=>$stat,'msg'=>$msg));
  dInitDb();die;
}
function savePointRules($cfg){
  include('./lib/sanitize.php');
  perm(array('price'));
  $ret='ok';
  $pointId=explode('||',$pointId);
  $pointName=explode('||',$pointName);
  $pointAmount=explode('||',$pointAmount);
  for($i=0;$i<count($pointId);$i++){
    $pId=$pointId[$i];
    $pName=$pointName[$i];
    $pAmt=$pointAmount[$i];
    if($pId==0){
      q("insert into pointrules(pointName,pointAmount) values('$pName','$pAmt')");
    }else{
      q("update pointrules set pointName='$pName',pointAmount='$pAmt' where id='$pId'");
    }
  }
  genConfig();
  echo 'ok';
  echo json_encode(array('status'=>$ret));
  dInitDb();die;
}
function savePriceRule($cfg){
  include('./lib/sanitize.php');
  perm(array('price'));
  $ret='ok';
  if($id==0){i();}else{u();}
  genConfig();
  echo json_encode(array('status'=>$ret));
  dInitDb();die;
}
function saveDeliRule($cfg){
  include('./lib/sanitize.php');
  perm(array('price'));
  $ret='ok';
  if($id==0){i();}else{u();}
  genConfig();
  echo json_encode(array('status'=>$ret));
  dInitDb();die;
}
function delSpec($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $ret='';
  $r=fq("select count(sId)as c from prodspec where sId='$id'");
  if($r['c']==0){
    q("delete from specname where id='$id'");
  }else{
    $ret='มีสินค้าบางตัวที่ยังใช้ [';
    $rs=q("select pName from product where id in(select pId from prodspec where sId='$id')");
    while($r=f($rs)){
      $ret.=$r['pName'].',';
    }
    $ret=rtrim($ret,',').']';
  }
  genConfig();
  echo json_encode(array('status'=>$ret));
  dInitDb();die;
}
function updateSpec($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $i=1;
  foreach($_GET['spec'] as $spec){
    $id=$spec['id']*1;
    $val=e($spec['val']);
    $id*=1;
    q("update specname set sortIdx='$i',sName='$val' where id='$id'");
    $i++;
  }
  genConfig();
  echo json_encode(array('status'=>'ok'));
  dInitDb();die;
}
function _sortSpec(){
  $rs2=q("select id from specname where parentId=0");
  while($r2=f($rs2)){
    $parentId=$r2['id'];
    $rs=q("select id from specname where parentId='$parentId' order by sortIdx asc,sName asc");
    $spec=array();
    while($r=f($rs)){
      $spec[]=$r['id'];
    }
    $i=0;
    while($i++<count($spec)){
      $id=$spec[$i-1];
      q("update specname set sortIdx='$i' where id='$id'");
    }
  }
}
function updateDoc($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('product'));
  $docId=explode(',',$docId);
  $doc=$_POST['doc'];
  for($i=0;$i<count($docId);$i++){
    $id=$docId[$i];
    $aDoc=e($doc[$i]);
    q("update doc set name='$aDoc' where id='$id'");
  }
  genConfig();
  echo json_encode(array('status'=>'ok'));
  dInitDb();die;
}
function updateStock($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('product'));
  $prodId=explode(',',$prodId);
  $prodAmount=explode(',',$prodAmount);
  for($i=0;$i<count($prodId);$i++){
    $id=$prodId[$i];
    $amount=$prodAmount[$i];
    q("update product set amount='$amount' where id='$id'");
  }
  genConfig();
  echo json_encode(array('status'=>'ok'));
  dInitDb();die;
}
function delNews($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('news'));
  $rs=q("select url from newsimg where nId='$id'");
  while($r=f($rs)){
    unlink('..'.$r['url']);
  }
  q("delete from newsimg where nId='$id'");
  q("delete from news where id='$id'");
  genConfig();
  echo json_encode(array('status'=>'ok'));
  dInitDb();die;
}
function delNewsk($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('news'));
  $rs=q("select url from newsimgk where nId='$id'");
  while($r=f($rs)){
    unlink('..'.$r['url']);
  }
  q("delete from newsimgk where nId='$id'");
  q("delete from newsk where id='$id'");
  genConfig();
  echo json_encode(array('status'=>'ok'));
  dInitDb();die;
}
function delActivity($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('news'));
  $rs=q("select url from actiimg where aId='$id'");
  while($r=f($rs)){
    unlink('..'.$r['url']);
  }
  q("delete from actiimg where aId='$id'");
  $rs=q("select url from ogimg where aId='$id'");
  while($r=f($rs)){
    unlink('..'.$r['url']);
  }
  q("delete from ogimg where aId='$id'");
  q("delete from activity where id='$id'");
  genConfig();
  echo json_encode(array('status'=>'ok'));
  dInitDb();die;
}
function delActivityk($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('news'));
  $rs=q("select url from actiimgk where aId='$id'");
  while($r=f($rs)){
    unlink('..'.$r['url']);
  }
  q("delete from actiimgk where aId='$id'");
  $rs=q("select url from ogimgk where aId='$id'");
  while($r=f($rs)){
    unlink('..'.$r['url']);
  }
  q("delete from ogimgk where aId='$id'");
  q("delete from activityk where id='$id'");
  genConfig();
  echo json_encode(array('status'=>'ok'));
  dInitDb();die;
}
function delProdPrice($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('price'));
  $stat='ok';
  $msg='';
  $r=fq("select count(id)as c from product where priceRuleId='$id'");
  if($r['c']>0){
    $stat='error';
    $msg='มีสินค้าใช้กฎนี้อยู่ ';
    $rs=q("select pName from product where priceRuleId='$id' order by pName asc");
    while($r=f($rs)){
      $msg.=$r['pName'].',';
    }
  }else{
    q("delete from prodpricerules where id='$id'");
  }
  echo json_encode(array('status'=>$stat,'msg'=>$msg));
  dInitDb();die;
}
function updateNewsImgId($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('news'));
  $status='';
  $foo=explode(',',$imgIds);
  foreach($foo as $i){
    q("update ogimg set nId='$nId' where id='$i'");
  }
  echo json_encode(array('status'=>$status));
  dInitDb();die;
}
function updateNewsImgIdk($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('news'));
  $status='';
  $foo=explode(',',$imgIds);
  foreach($foo as $i){
    q("update ogimgk set nId='$nId' where id='$i'");
  }
  echo json_encode(array('status'=>$status));
  dInitDb();die;
}
function updateActivityImgId($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('news'));
  $status='';
  $foo=explode(',',$imgIds);
  foreach($foo as $i){
    q("update ogimg set aId='$aId' where id='$i'");
  }
  echo json_encode(array('status'=>$status));
  dInitDb();die;
}
function updateActivityImgIdk($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('news'));
  $status='';
  $foo=explode(',',$imgIds);
  foreach($foo as $i){
    q("update ogimgk set aId='$aId' where id='$i'");
  }
  echo json_encode(array('status'=>$status));
  dInitDb();die;
}
function saveActivity($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('news'));
  $status='';
  $descr=str_replace('./../upload/','./upload/',$descr);
  $title=($title=='')?'_':$title;
  while($r=fq("select title from activity where id<>'$id' and title='$title'")){
    $status='dup';
    $title.='_';
  }
  if($id==0){
    $id=i(0,array('hash'=>genRandom(),'createDate'=>date("Y-m-d h:i:s")));
    $rs=q("select * from actiimg where aId=0");
    while($r=f($rs)){
      $url=$r['url'];
      $actiimgId=$r['id'];
      if(strpos($descr,$url)>0){q("update actiimg set aId='$id' where id='$actiimgId'");}
    }
  }else{u();}
  q("delete from actirelated where aId='$id'");
  $foo=explode(',',$related);
  $sortIdx=0;
  foreach($foo as $i){q("insert into actirelated(aId,relatedId,sortIdx) values('$id','$i','$sortIdx')");$sortIdx++;}
  if(strlen($_POST['thumbUrl'])!=0){
    $dest='/upload/activity/thumb_'.$id.'_'.genRandom().'.png';
    file_put_contents('..'.$dest, base64_decode(substr($_POST['thumbUrl'], strpos($_POST['thumbUrl'], ",")+1)));
    q("update activity set thumbUrl='$dest' where id='$id'");
  }
  genConfig();
  echo json_encode(array('status'=>$status,'title'=>$title,'id'=>$id));
  dInitDb();die;
}
function saveActivityk($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('news'));
  $status='';
  $descr=str_replace('./../upload/','./upload/',$descr);
  $title=($title=='')?'_':$title;
  while($r=fq("select title from activityk where id<>'$id' and title='$title'")){
    $status='dup';
    $title.='_';
  }
  if($id==0){
    $id=i(0,array('hash'=>genRandom(),'createDate'=>date("Y-m-d h:i:s")));
    $rs=q("select * from actiimgk where aId=0");
    while($r=f($rs)){
      $url=$r['url'];
      $actiimgId=$r['id'];
      if(strpos($descr,$url)>0){q("update actiimgk set aId='$id' where id='$actiimgId'");}
    }
  }else{u();}
  q("delete from actirelatedk where aId='$id'");
  $foo=explode(',',$related);
  $sortIdx=0;
  foreach($foo as $i){q("insert into actirelatedk(aId,relatedId,sortIdx) values('$id','$i','$sortIdx')");$sortIdx++;}
  if(strlen($_POST['thumbUrl'])!=0){
    $dest='/upload/activity/thumb_'.$id.'_'.genRandom().'.png';
    file_put_contents('..'.$dest, base64_decode(substr($_POST['thumbUrl'], strpos($_POST['thumbUrl'], ",")+1)));
    q("update activityk set thumbUrl='$dest' where id='$id'");
  }
  genConfig();
  echo json_encode(array('status'=>$status,'title'=>$title,'id'=>$id));
  dInitDb();die;
}
function saveNews($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('news'));
  $status='';
  $descr=str_replace('./../upload/','./upload/',$descr);
  $title=($title=='')?'_':$title;
  while($r=fq("select title from news where id<>'$id' and title='$title'")){
    $status='dup';
    $title.='_';
  }
  if($id==0){
    $id=i(0,array('hash'=>genRandom(),'createDate'=>date("Y-m-d h:i:s")));
    $rs=q("select * from newsimg where nId=0");
    while($r=f($rs)){
      $url=$r['url'];
      $newsimgId=$r['id'];
      if(strpos($descr,$url)>0){q("update actiimg set nId='$id' where id='$newsimgId'");}
    }
  }else{u();}
  q("delete from newsrelated where nId='$id'");
  $foo=explode(',',$related);
  $sortIdx=0;
  foreach($foo as $i){q("insert into newsrelated(nId,relatedId,sortIdx) values('$id','$i','$sortIdx')");$sortIdx++;}
  if(strlen($_POST['thumbUrl'])!=0){
    $dest='/upload/news/thumb_'.$id.'_'.genRandom().'.png';
    file_put_contents('..'.$dest, base64_decode(substr($_POST['thumbUrl'], strpos($_POST['thumbUrl'], ",")+1)));
    q("update news set thumbUrl='$dest' where id='$id'");
  }
  if(strlen($_POST['socialUrl'])!=0){
    $dest='/upload/news/social_'.$id.'_'.genRandom().'.png';
    file_put_contents('..'.$dest, base64_decode(substr($_POST['socialUrl'], strpos($_POST['socialUrl'], ",")+1)));
    q("update news set socialUrl='$dest' where id='$id'");
  }
  genConfig();
  echo json_encode(array('status'=>$status,'title'=>$title,'id'=>$id));
  dInitDb();die;
}
function saveNewsk($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('news'));
  $status='';
  $descr=str_replace('./../upload/','./upload/',$descr);
  $title=($title=='')?'_':$title;
  while($r=fq("select title from newsk where id<>'$id' and title='$title'")){
    $status='dup';
    $title.='_';
  }
  if($id==0){
    $id=i(0,array('hash'=>genRandom(),'createDate'=>date("Y-m-d h:i:s")));
    $rs=q("select * from newsimgk where nId=0");
    while($r=f($rs)){
      $url=$r['url'];
      $newsimgId=$r['id'];
      if(strpos($descr,$url)>0){q("update actiimgk set nId='$id' where id='$newsimgId'");}
    }
  }else{u();}
  q("delete from newsrelatedk where nId='$id'");
  $foo=explode(',',$related);
  $sortIdx=0;
  foreach($foo as $i){q("insert into newsrelatedk(nId,relatedId,sortIdx) values('$id','$i','$sortIdx')");$sortIdx++;}
  if(strlen($_POST['thumbUrl'])!=0){
    $dest='/upload/news/thumb_'.$id.'_'.genRandom().'.png';
    file_put_contents('..'.$dest, base64_decode(substr($_POST['thumbUrl'], strpos($_POST['thumbUrl'], ",")+1)));
    q("update newsk set thumbUrl='$dest' where id='$id'");
  }
  if(strlen($_POST['socialUrl'])!=0){
    $dest='/upload/news/social_'.$id.'_'.genRandom().'.png';
    file_put_contents('..'.$dest, base64_decode(substr($_POST['socialUrl'], strpos($_POST['socialUrl'], ",")+1)));
    q("update newsk set socialUrl='$dest' where id='$id'");
  }
  genConfig();
  echo json_encode(array('status'=>$status,'title'=>$title,'id'=>$id));
  dInitDb();die;
}
function delProduct($cfg){
  include('./lib/sanitize.php');
  $status='';
  perm(array('product'));
  q("update product set isDel='yes' where id='$id'");
  genConfig();
  echo json_encode(array('status'=>$status));
  dInitDb();die;
}
function saveProductPrice($cfg){
  include('./lib/sanitize.php');
  $status='';
  perm(array('price'));
  if($id>0){
    u();
    genConfig();
  }
  echo json_encode(array('status'=>$status,'id'=>$id));
  dInitDb();die;
}
function saveProduct($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('product'));
  $status='';
  $descr=str_replace('./../upload/','./upload/',$descr);
  $inTheBox=str_replace('./../upload/','./upload/',$inTheBox);
  $root='Fujiko';
  if($r=fq("select root from prodcat where id='$cId'")){
    $root=$r['root'];
  }
  $pName=($pName=='')?'_':$pName;
  while($r=fq("select pName from product where isDel='no' and id<>'$id' and root='$root' and pName='$pName'")){
    $status='dup';
    $pName.='_';
  }
  if($id==0){$id=i(0,array('hash'=>genRandom()));}else{u();}
  $specIds='0';
  foreach($_POST['spec'] as $o){
    $specId=e($o['specId']);
    $specIds.=','.$specId;
    $specName=e($o['specName']);
    $specVal=e($o['specVal']);
    $parentId=e($o['specGroup']);
    if($specId=='none'){
      q("insert into specname(sName,parentId) values('$specName','$parentId')");
      $specId=mysqli_insert_id($__conn);
    }
    q("insert into prodspec(pId,sId,val) values('$id','$specId','$specVal')");
    q("update prodspec set val='$specVal' where pId='$id' and sId='$specId'");
  }
  q("delete from prodspec where pId='$id' and sId not in($specIds)");
  _sortSpec();
  //color
  
  $colorIds='0';
  foreach($_POST['color'] as $o){
    $colorId=e($o['id']);
    $cName=e($o['text']);
    if($colorId*1==0){
      q("insert into colorname(cName) values('$cName')");
      $colorId=mysqli_insert_id($__conn);
    }
    $colorIds.=','.$colorId;
    q("insert into prodcolor(pId,cId) values('$id','$colorId')");
  }
  q("delete from prodcolor where pId='$id' and cId not in($colorIds)");
  q("delete from colorname where id not in(select distinct cId from prodcolor)");
  //mustHave
  $pIds='0';
  foreach($_POST['mustHave'] as $o){
    $pId=e($o);
    $pIds.=','.$pId;
    q("insert into prodmust(pId,oId) values('$id','$pId')");
  }
  q("delete from prodmust where pId='$id' and oId not in($pIds)");
  //relatedProducts
  $pIds='0';
  foreach($_POST['related'] as $o){
    $pId=e($o);
    $pIds.=','.$pId;
    q("insert into prodrelated(pId,oId) values('$id','$pId')");
  }
  q("delete from prodrelated where pId='$id' and oId not in($pIds)");
  //doc
  $oIds='0';
  foreach($_POST['doc'] as $o){
    $dId=e($o);
    $oIds.=','.$dId;
    q("insert into proddoc(pId,dId) values('$id','$dId')");
  }
  q("delete from proddoc where pId='$id' and dId not in($oIds)");
  //easySpec
  $pIds='0';
  foreach($_POST['easySpec'] as $o){
    $pId=e($o);
    $pIds.=','.$pId;
    q("insert into prodspeceasy(pId,sId) values('$id','$pId')");
  }
  q("delete from prodspeceasy where pId='$id' and sId not in($pIds)");
  updateHasChild();
  genConfig();
  echo json_encode(array('status'=>$status,'pName'=>$pName,'id'=>$id));
  dInitDb();die;
}
function updateHasChild(){
  q("update prodcat set hasChild='no'");
  q("update prodcat set hasChild='yes' where id in(select distinct cId from product)");
  q("update specname set hasChild='no'");
  q("update specname set hasChild='yes' where id in(select distinct sId from prodspec)");
  q("update speceasy set hasChild='no'");
  q("update speceasy set hasChild='yes' where id in(select distinct sId from prodspeceasy)");
}
function login($cfg){
  include('./lib/sanitize.php');
  $status='เมล์หรือรหัสผ่านไม่ถูกต้อง';
  $redi='';
  if($r=fq("select id,pwd from adminusr where mail='$mail'")){
    //echo md5($pwd.substr($r['pwd'],0,32));
    if(md5($pwd.substr($r['pwd'],0,32))==substr($r['pwd'],32)){
      $_SESSION['adminId']=$adminId=$r['id'];
      $perm=array();
      $rs=q("select perm from perms where uId='$adminId' order by perm asc");
      while($r=f($rs)){
        $perm[]=$r['perm'];
        if($redi==''){
          switch($r['perm'])
          {
            case 'product':
              $redi='prodCats.php';break;
            case 'price':
              $redi='prodPrice.php';break;
            case 'order':
              $redi='orders.php';break;
            case 'adminUsr':
              $redi='adminUsr.php';break;
            case 'news':
              $redi='news.php';break;
            case 'usr':
              $redi='users.php';break;
            default:
              break;
          }          
        }
      }
      $_SESSION['perm']=$perm;
      $status='';
    }
  }
  echo json_encode(array('status'=>$status,'redi'=>$redi));
  dInitDb();die;
}
function changePwd($cfg){
  include('./lib/sanitize.php');
  $status='เกิดข้อผิดพลาดขึ้น กรุณาลองอีกครั้ง';
  if(isset($_SESSION['adminId'])){
    $id=$_SESSION['adminId'];
    if(strlen($pwd)>0 && $r=fq("select pwd from adminusr where id='$id'")){
      if(md5($cPwd.substr($r['pwd'],0,32))==substr($r['pwd'],32)){
        $tmp=genRandom(32);
        $pwd=$tmp.md5($pwd.$tmp);
        q("update adminusr set pwd='$pwd' where id='$id'");
        $status='แก้ไขรหัสผ่านเรียบร้อยแล้ว';
      }else{
        $status='รหัสผ่านปัจจุบันไม่ถูกต้อง';
      }
    }
  }
  echo json_encode(array('status'=>$status));
  dInitDb();die;
}
function delAdminUsr($cfg){
  include('./lib/sanitize.php');
  perm(array('adminUsr'));
  $status='';
  q("delete from perms where uId='$id'");
  q("delete from adminusr where id='$id'");
  echo json_encode(array('status'=>$status));
  dInitDb();die;
}
function saveUsr($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('usr'));
  $currPwd='';
  $status='';
  if($id==0){
    q("insert into usr(mail) values('')");
    $id=mysqli_insert_id($__conn);
  }
  q("update usr set baName='$baName',mail='$mail',ci='$ci',bonus='$bonus' where id='$id'");
  if($pwd!=''){
    $currPwd=$pwd;
    $tmp=genRandom(32);
    $pwd=$tmp.md5($pwd.$tmp);
    q("update usr set pwd='$pwd' where id='$id'");
  }
  if($currPwd!=''){
    require_once(dirname(__FILE__).'/class.phpmailer.php');
    $usrMail=$mail;
    $msg='<div><img style="height:42px;margin-right:10px;" src="http://fujiko.co.th/img/logo.png"/><img style="height:42px" src="http://kenprocctv.com/img/logo.png"/></div>';
    $msg.="<h3>เรียนคุณ $baName ,</h3>";
    $msg.="ท่านสามารถ login เข้าสู่เวปไซต์ fujiko.co.th และ kenprocctv.com ได้โดยใช้เมลและรหัสผ่านดังนี้<br/><br/>";
    $msg.="mail : <b>$usrMail</b><br/>";
    $msg.="password : <b>$currPwd</b><br/>";
    $msg.="<br/>ทีมงาน Official Fujiko / Kenpro CCTV";
    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
    $mail->IsSMTP(); // telling the class to use SMTP$mail->SMTPDebug = 1;
    try {
      $mail->SMTPDebug  = false;                     // enables SMTP debug information (for testing)
      $mail->CharSet   = "utf-8";                  // enable SMTP authentication
      $mail->SMTPAuth   = true;                  // enable SMTP authentication
      //$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
      $mail->Host       = mailHost;      // sets GMAIL as the SMTP server
      $mail->Username   = mailUsr;  // GMAIL username
      $mail->Password   = mailPwd;            // GMAIL password
      $mail->AddReplyTo(mailUsr,mailFrom);
      $mail->SetFrom(mailUsr,mailFrom);
      $mail->AddAddress($usrMail);
      $mail->Subject = 'ข้อมูล account Fujiko / Kenpro';
      $mail->MsgHTML($msg);
      $mail->Send();
      //echo "Message Sent OK<p></p>\n";
    } catch (phpmailerException $e) {
      //echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      //echo $e->getMessage(); //Boring error messages from anything else!
    }
  }
  echo json_encode(array('status'=>$status));
  dInitDb();die;
}
function saveAdminUsr($cfg){
  global $__conn;
  include('./lib/sanitize.php');
  perm(array('adminUsr'));
  $status='';
  if($id==0){
    q("insert into adminusr(aName) values('')");
    $id=mysqli_insert_id($__conn);
  }
  q("update adminusr set aName='$aName',mail='$mail' where id='$id'");
  if($pwd!=''){
    $tmp=genRandom(32);
    $pwd=$tmp.md5($pwd.$tmp);
    q("update adminusr set pwd='$pwd' where id='$id'");
  }
  q("delete from perms where uId='$id'");
  foreach($_POST['perm'] as $a){
    $a=e($a);
    q("insert into perms(uId,perm) values('$id','$a')");
  }
  echo json_encode(array('status'=>$status));
  dInitDb();die;
}
function logout($cfg){
  include('./lib/sanitize.php');
  $status='ok';
  session_unset();
  session_destroy();
  echo json_encode(array('status'=>$status));
  dInitDb();die;
}
function lol(){
  
  u(1,'descr="lol"');
  $wName='mai';
  i(1,array('descr'=>'hohohohoh','mail'=>'hhh'));
  trace('id '.$id);
  echo esc('ggg');
  
}
function getProdCatThumb($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $r=fq("select * from prodcat where id='$id'");
  echo json_encode(array('thumbUrl'=>$r['thumbUrl'],'coverUrl'=>$r['coverUrl']));
  dInitDb();die;
}
function getEasySpecThumb($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $r=fq("select url from speceasy where id='$id'");
  echo json_encode(array('url'=>$r['url']));
  dInitDb();die;
}
function saveProdCatThumb($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  //cover
  if(isset($_POST['coverPic'])){
    $r=fq("select coverUrl from prodcat where id='$id'");
    if(strpos($r['coverUrl'],'/img/')===false){
      unlink('..'.$r['coverUrl']);
    }
    if(strlen($_POST['coverPic'])==0){
      $dest='';
    }else{
      $dest='/upload/coverPic'.$id.'_'.genRandom().'.png';
      file_put_contents('..'.$dest, base64_decode(substr($_POST['coverPic'], strpos($_POST['coverPic'], ",")+1)));
    }
    q("update prodcat set coverUrl='$dest' where id='$id'");
  }
  //thumb
  if(isset($_POST['thumbPic'])){
    $r=fq("select thumbUrl from prodcat where id='$id'");
    if(strpos($r['thumbUrl'],'/img/')===false){
      unlink('..'.$r['thumbUrl']);
    }
    if(strlen($_POST['thumbPic'])==0){
      $dest='';
    }else{
      $dest='/upload/thumbPic'.$id.'_'.genRandom().'.png';
      file_put_contents('..'.$dest, base64_decode(substr($_POST['thumbPic'], strpos($_POST['thumbPic'], ",")+1)));
    }
    q("update prodcat set thumbUrl='$dest' where id='$id'");
  }
  $nodeType='default';
  $r=fq("select * from prodcat where id='$id'");
  $hasChild=($r['hasChild']=='yes')?true:false;
  $hasImg=($r['id']>2&&(strlen($r['thumbUrl'])==0||strlen($r['coverUrl'])==0))?false:true;
  if($hasChild){
    $nodeType=($hasImg)?'hasChildOk':'hasChild';
  }else{
    $nodeType=($hasImg)?'emptyOk':'default';
  }
  if($r['id']==1){$nodeType='Fujiko';}
  if($r['id']==2){$nodeType='Kenpro';}
  genConfig();
  echo json_encode(array('stat'=>'ok','nodeType'=>$nodeType));
  dInitDb();die;
}
function sortProdImg($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $foo=explode(',',$sortIdx);
  $i=0;
  foreach($foo as $o){
    q("update prodimg set sortIdx='$i' where id='$o'");
    $i++;
  }
  genConfig();
  echo json_encode(array('stat'=>'ok'));
  dInitDb();die;
}
function delOgImg($cfg){
  include('./lib/sanitize.php');
  perm(array('news'));
  $r=fq("select url from ogimg where id='$id'");
  $img='..'.$r['url'];
  unlink($img);
  q("delete from ogimg where id='$id'");
  //genConfig();
  echo json_encode(array('stat'=>'ok'));
  dInitDb();die;
}
function saveOgImg($cfg){
  include('./lib/sanitize.php');
  perm(array('news'));
  $id=i(1);
  $dest="/upload/news/ogImg_$id".'_'.genRandom().'.png';
  file_put_contents('..'.$dest, base64_decode(substr($_POST['img'], strpos($_POST['img'], ",")+1)));
  
  list($ogImgW, $ogImgH) = getimagesize('..'.$dest);
  q("update ogimg set url='$dest' where id='$id'");
  //genConfig();
  echo json_encode(array('stat'=>'ok','id'=>$id,'url'=>baseUrl.$dest,'w'=>$ogImgW,'h'=>$ogImgH));
  dInitDb();die;
}
function delProdImg($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $r=fq("select url from prodimg where id='$id'");
  $img='..'.$r['url'];
  $tmb=str_replace('.png','_tmb.png',$img);
  unlink($img);
  unlink($tmb);
  q("delete from prodimg where id='$id'");
  genConfig();
  echo json_encode(array('stat'=>'ok'));
  dInitDb();die;
}
function saveProdImg($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $id=i();
  $dest="/upload/prodImg_$pId"."_$id".'_'.genRandom().'.png';
  $tmb=str_replace('.png','_tmb.png',$dest);
  file_put_contents('..'.$dest, base64_decode(substr($_POST['img'], strpos($_POST['img'], ",")+1)));
  file_put_contents('..'.$tmb, base64_decode(substr($_POST['tmb'], strpos($_POST['tmb'], ",")+1)));
  q("update prodimg set url='$dest' where id='$id'");
  genConfig();
  echo json_encode(array('stat'=>'ok','id'=>$id));
  dInitDb();die;
}
function saveEasySpecThumb($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  //cover
  if(isset($_POST['url'])){
    $r=fq("select url from speceasy where id='$id'");
    if(strpos($r['url'],'/img/')===false){
      unlink('..'.$r['url']);
    }
    if(strlen($_POST['url'])==0){
      $dest='';
    }else{
      $dest='/upload/specEasy'.$id.'_'.genRandom().'.png';
      file_put_contents('..'.$dest, base64_decode(substr($_POST['url'], strpos($_POST['url'], ",")+1)));
    }
    q("update speceasy set url='$dest' where id='$id'");
  }
  $nodeType='default';
  $r=fq("select url from speceasy where id='$id'");
  genConfig();
  echo json_encode(array('stat'=>'ok','icon'=>'..'.$r['url']));
  dInitDb();die;
}
function getSpec($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $hasExact=false;
  $ret=array();
  $rs=q("select * from specname where parentId='$specGroup' and sName like '%$q%' and id not in($currSpec) order by sortIdx asc,sName asc");
  while($r=f($rs)){
    $foo=array();
    $foo['id']=$r['id'];
    $foo['text']=$r['sName'];
    if($r['sName']==$q)$hasExact=true;
    $ret[]=$foo;
  }
  if(!$hasExact){
    $foo=array();
    $foo['id']='none';
    $foo['text']=$q;
    $ret[]=$foo;
  }
  echo json_encode($ret);
  dInitDb();die;
}
function getEasySpecSel($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $hasExact=false;
  $ret=array();
  $exclude='0';
  foreach($_GET['curr'] as $o){
    $exclude.=','.e($o*1);
  }
  $rs=q("select distinct parentId from speceasy where id in($exclude) and parentId>0");
  $excludeP='';
  while($r=f($rs)){
    $excludeP.=','.$r['parentId'];
  }
  if(strlen($excludeP)>0){
    $excludeP=ltrim($excludeP,',');
    $excludeP="and parentId not in($excludeP)";
  }
  $rs=q("select * from speceasy where sName like '%$q%' and id not in(select distinct parentId from speceasy) and id not in($exclude) $excludeP order by globalIdx");
  while($r=f($rs)){
    $foo=array();
    $foo['id']=$r['id'];
    $foo['text']=$r['sName'];
    $ret[]=$foo;
  }
  echo json_encode($ret);
  dInitDb();die;
}
function getColorSel($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $hasExact=false;
  $ret=array();
  $exclude='0';
  foreach($_GET['curr'] as $o){
    $exclude.=','.e($o*1);
  }
  $rs=q("select * from colorname where cName like '%$q%' and id not in($exclude) order by cName");
  while($r=f($rs)){
    $foo=array();
    $foo['id']=$r['id'];
    $foo['text']=$r['cName'];
    if($r['sName']==$q)$hasExact=true;
    $ret[]=$foo;
  }
  if(!$hasExact){
    $foo=array();
    $foo['id']='none_'.time();
    $foo['text']=$q;
    $ret[]=$foo;
  }
  echo json_encode($ret);
  dInitDb();die;
}
function getDocSel($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $ret=array();
  $exclude='0';
  foreach($_GET['curr'] as $o){
    $exclude.=','.e($o*1);
  }
  $rs=q("select id,cat,name from doc where root='$whichSite' and id not in($exclude) and name like '%$q%' order by cat asc,name asc");
  while($r=f($rs)){
    $foo=array();
    $foo['id']=$r['id'];
    $foo['text']=$r['cat'].' - '.$r['name'];
    $ret[]=$foo;
  }
  echo json_encode($ret);
  dInitDb();die;
}
function getMustHaveSel($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $ret=array();
  $exclude='0';
  foreach($_GET['curr'] as $o){
    $exclude.=','.e($o*1);
  }
  $rs=q("select pName,id from product where id<>'$id' and id not in($exclude) and pName like '%$q%' order by pName asc");
  while($r=f($rs)){
    $foo=array();
    $foo['id']=$r['id'];
    $foo['text']=$r['pName'];
    $ret[]=$foo;
  }
  echo json_encode($ret);
  dInitDb();die;
}
function getRelated($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $ret=array();
  $exclude='0';
  foreach($_GET['curr'] as $o){
    $exclude.=','.e($o*1);
  }
  $rs=q("select pName,id from product where id<>'$id' and id not in($exclude) and pName like '%$q%' order by pName asc");
  while($r=f($rs)){
    $foo=array();
    $foo['id']=$r['id'];
    $foo['text']=$r['pName'];
    $ret[]=$foo;
  }
  echo json_encode($ret);
  dInitDb();die;
}
function delProdCat($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $ret='error';
  if($r=fq("select parentId from prodcat where id='$id' and hasChild='no'")){
    $parentId=$r['parentId'];
    $r=fq("select count(id)as c from prodcat where parentId='$id'");
    if($r['c']==0){
      $r=fq("select * from prodcat where id='$id'");
      if(strpos($r['coverUrl'],'/img/')===false){
        unlink('..'.$r['coverUrl']);
      }
      if(strpos($r['thumbUrl'],'/img/')===false){
        unlink('..'.$r['thumbUrl']);
      }
      q("delete from prodcat where id='$id'");
      sortProdCat($parentId);
      $ret='ok';
    }
  }
  genConfig();
  echo json_encode(array('ret'=>$ret));
  dInitDb();die;
}
function moveProdCat($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  sortProdCat($parentId,$id,$sortIdx);
  if($parentId!=$oldParentId){
    sortProdCat($oldParentId);
  }
  genConfig();
  echo json_encode(array('ret'=>'ok'));
  dInitDb();die;
}
function sortProdCat($parentId,$id=0,$sortIdx=0){
  $hasNode=($id==0)?false:true;
  $idx=0;
  if($hasNode){
    q("update prodcat set parentId='$parentId',sortIdx='$sortIdx' where id='$id'");
  }
  $rs=q("select id from prodcat where parentId='$parentId' order by sortIdx asc");
  while($r=f($rs)){
    $nId=$r['id'];
    if($hasNode&&$idx==$sortIdx)$idx++;
    if($hasNode&$nId!=$id){
      q("update prodcat set sortIdx='$idx' where id='$nId'");
      $idx++;
    }
    if(!$hasNode){
      q("update prodcat set sortIdx='$idx' where id='$nId'");
      $idx++;
    }
  }
}
function renameProdCat($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $stat='ok';
  
  //check duplicate with product name
  if($r=fq("select id from product where pName='$cName'")){
    $stat='error';
    while($r=fq("select id from product where pName='$oldName'")){
      $oldName.='_';
    }
  }
  $r=fq("select root from prodcat where id='$id'");
  $root=$r['root'];
  //check duplicate with other category name
  if($r=fq("select id from prodcat where cName='$cName' and root='$root' and id<>'$id'")){
    $stat='error';
    while($r=fq("select id from prodcat where cName='$oldName'")){
      $oldName.='_';
    }
  }else{
    if($stat=='ok'){
      u();
    }
  }
  if($stat=='error'){
    q("update prodcat set cName='$oldName' where id='$id'");
  }
  genConfig();
  echo json_encode(array('stat'=>$stat,'oldName'=>$oldName));
  dInitDb();die;
}
function delEasySpec($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $ret='error';
  if($r=fq("select parentId from speceasy where id='$id' and hasChild='no'")){
    $parentId=$r['parentId'];
    $r=fq("select count(id)as c from speceasy where parentId='$id'");
    if($r['c']==0){
      $r=fq("select * from speceasy where id='$id'");
      if(strpos($r['url'],'/img/')===false){
        unlink('..'.$r['url']);
      }
      q("delete from speceasy where id='$id'");
      sortEasySpec($parentId);
      sortEasySpecGlobal();
      $ret='ok';
    }
  }
  genConfig();
  echo json_encode(array('ret'=>$ret));
  dInitDb();die;
}
function moveEasySpec($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  sortEasySpec($parentId,$id,$sortIdx);
  if($parentId!=$oldParentId){
    sortEasySpec($oldParentId);
  }
  sortEasySpecGlobal();
  genConfig();
  echo json_encode(array('ret'=>'ok'));
  dInitDb();die;
}
function sortEasySpec($parentId,$id=0,$sortIdx=0){
  $hasNode=($id==0)?false:true;
  $idx=0;
  if($hasNode){
    q("update speceasy set parentId='$parentId',sortIdx='$sortIdx' where id='$id'");
  }
  $rs=q("select id from speceasy where parentId='$parentId' order by sortIdx asc");
  while($r=f($rs)){
    $nId=$r['id'];
    if($hasNode&&$idx==$sortIdx)$idx++;
    if($hasNode&$nId!=$id){
      q("update speceasy set sortIdx='$idx' where id='$nId'");
      $idx++;
    }
  }
}
function sortEasySpecGlobal($parentId=0,$sortIdx=0){
  $rs=q("select id from speceasy where parentId='$parentId' order by sortIdx asc");
  while($r=f($rs)){
    $id=$r['id'];
    q("update speceasy set globalIdx='$sortIdx' where id='$id'");
    $sortIdx++;
    $sortIdx=sortEasySpecGlobal($id,$sortIdx);
  }
  return $sortIdx;
}
function renameEasySpec($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  u();
  genConfig();
  echo json_encode(array('ret'=>'ok'));
  dInitDb();die;
}
function createEasySpec($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $r=fq("select max(sortIdx)+1 as sortIdx from speceasy where parentId='$parentId'");
  $sortIdx=$r['sortIdx']*1;
  $id=i(0,array('sName'=>'New node','sortIdx'=>$sortIdx));
  sortEasySpec($parentId);
  sortEasySpecGlobal();
  genConfig();
  echo json_encode(array('id'=>$id,'sortIdx'=>$sortIdx));
  dInitDb();die;
}
function createProdCat($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $r=fq("select root from prodcat where id='$parentId'");
  $root=$r['root'];
  $r=fq("select max(sortIdx)+1 as sortIdx from prodcat where parentId='$parentId'");
  $sortIdx=$r['sortIdx']*1;
  
  $cName='New node';
  $hash=genRandom();
  while($r=fq("select cName from prodcat where cName='$cName' and root='$root'")){
    $cName.='_';
  }
  $id=i(0,array('cName'=>$cName,'hash'=>$hash,'sortIdx'=>$sortIdx,'root'=>$root));
  sortProdCat($parentId);
  genConfig();
  echo json_encode(array('id'=>$id,'cName'=>$cName,'sortIdx'=>$sortIdx));
  dInitDb();die;
}
function delProdSpec($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $ret='error';
  if($r=fq("select parentId from specname where id='$id' and hasChild='no'")){
    $parentId=$r['parentId'];
    $r=fq("select count(id)as c from specname where parentId='$id'");
    if($r['c']==0){
      q("delete from specname where id='$id'");
      sortProdSpec($parentId);
      $ret='ok';
    }
  }
  genConfig();
  echo json_encode(array('ret'=>$ret));
  dInitDb();die;
}
function moveProdSpec($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  sortProdSpec($parentId,$id,$sortIdx);
  if($parentId!=$oldParentId){
    sortProdSpec($oldParentId);
  }
  genConfig();
  echo json_encode(array('ret'=>'ok'));
  dInitDb();die;
}
function sortProdSpec($parentId,$id=0,$sortIdx=0){
  $hasNode=($id==0)?false:true;
  $idx=0;
  if($hasNode){
    q("update specname set parentId='$parentId',sortIdx='$sortIdx' where id='$id'");
  }
  $rs=q("select id from specname where parentId='$parentId' order by sortIdx asc");
  while($r=f($rs)){
    $nId=$r['id'];
    if($hasNode&&$idx==$sortIdx)$idx++;
    if($hasNode&$nId!=$id){
      q("update specname set sortIdx='$idx' where id='$nId'");
      $idx++;
    }
  }
}
function renameProdSpec($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  u();
  genConfig();
  echo json_encode(array('ret'=>'ok'));
  dInitDb();die;
}
function createProdSpec($cfg){
  include('./lib/sanitize.php');
  perm(array('product'));
  $r=fq("select max(sortIdx)+1 as sortIdx from specname where parentId='$parentId'");
  $sortIdx=$r['sortIdx']*1;
  $sName='New node';
  while($r2=fq("select id from specname where sName='$sName'")){
    $sName.='_';
  }
  $id=i(0,array('sName'=>$sName,'sortIdx'=>$sortIdx));
  genConfig();
  echo json_encode(array('id'=>$id,'sortIdx'=>$sortIdx));
  dInitDb();die;
}
function perm($perms=array(),$redi=''){
  $isOk=true;
  if(isset($_SESSION['perm'])){
    foreach($perms as $a){
      if(!in_array($a,$_SESSION['perm'])){
        $isOk=false;
        break;
      }
    }
  }else{
    $isOk=false;
  }
  if(!$isOk){
    dInitDb();
    if($redi!=''){?>
      <script>document.location='<?=$redi?>';</script>
      <?
    }
    die;
  }
}
function getProdCatOptions($selectedId=0,$whichSite=''){
  if($r=fq("select root from prodcat where id='$selectedId'")){
    if($r['root']=='Kenpro')
      $ret=getProdCatOptionsInner($selectedId,2,'Kenpro');
    else
      $ret=getProdCatOptionsInner($selectedId,1,'Fujiko');
  }else{
    if($whichSite=='Kenpro')
      $ret=getProdCatOptionsInner($selectedId,2,'Kenpro');
    else
      $ret=getProdCatOptionsInner($selectedId,1,'Fujiko');
  }
  echo "<option value='0'> เลือกหมวดหมู่ </option>".$ret;
}
function getProdCatOptionsInner($selectedId,$parentId,$prevStr){
  $ret='';
  $hasChild=false;
  $selected=($selectedId==$parentId)?'selected':'';
  $rs=q("select * from prodcat where parentId='$parentId' order by sortIdx asc");
  while($r=f($rs)){
    $hasChild=true;
    $id=$r['id'];
    $cName=$r['cName'];
    $str=($prevStr=='')?$cName:$prevStr.' -> '.$cName;
    $ret.=getProdCatOptionsInner($selectedId,$id,$str);
  }
  //include self, except Fujiko+Kenpro
  if($parentId>1){
    $ret="<option value='$parentId' $selected >$prevStr</option>".$ret;
  }
  return $ret;
}
function getCatNodes($parentId=0){
  $ret=array();
  $rs=q("select * from prodcat where parentId='$parentId'");
  while($r=f($rs)){
    $item=array();
    $item['id']=$r['id'];
    $item['text']=str_replace('"',"&quot;",$r['cName']);
    $hasChild=($r['hasChild']=='yes')?true:false;
    $hasImg=($r['id']>2&&(strlen($r['thumbUrl'])==0||strlen($r['coverUrl'])==0))?false:true;
    if($hasChild){
      $item['type']=($hasImg)?'hasChildOk':'hasChild';
    }else{
      $item['type']=($hasImg)?'emptyOk':'default';
    }
    if($r['id']==1){$item['type']='Fujiko';}
    if($r['id']==2){$item['type']='Kenpro';}
    //$item['text'].='*';
    //if($r['id']>2&&)$item['text'].='*';
    $item['data']=$r['sortIdx'];
    $item['children']=getCatNodes($r['id']);
    $ret[]=$item;
  }
  return $ret;
}
function getEasySpecNodes($parentId=0){
  $ret=array();
  $rs=q("select * from speceasy where parentId='$parentId'");
  while($r=f($rs)){
    $item=array();
    $item['id']=$r['id'];
    $item['text']=str_replace('"',"&quot;",$r['sName']);
    $item['type']=($r['hasChild']=='yes')?'hasChild':'default';
    $item['data']=$r['sortIdx'];
    if($r['url']!=''){
    $item['icon']='..'.$r['url'];
    }
    $item['children']=getEasySpecNodes($r['id']);
    $ret[]=$item;
  }
  return $ret;
}
function getSpecNodes($parentId=0){
  $ret=array();
  $rs=q("select * from specname where parentId='$parentId' order by sortIdx asc");
  while($r=f($rs)){
    $item=array();
    $item['id']=$r['id'];
    $item['text']=str_replace('"',"&quot;",$r['sName']);
    $item['type']=($r['hasChild']=='yes')?'hasChild':'default';
    $item['type']=($parentId==0)?'folder':$item['type'];
    $item['data']=$r['sortIdx'];
    $item['children']=getSpecNodes($r['id']);
    $ret[]=$item;
  }
  return $ret;
}
function genConfig(){
  //del old cache
  $res=scandir('./../cache');
  foreach($res as $f){
    if($f!='.'&&$f!='..'){
     unlink("./../cache/$f");
    }
  }
  //gen rewrite mapping
  $dat=Array();
  $dat[]=Array('u'=>'','cache'=>'index','include'=>'index.php','params'=>array());
  $dat[]=Array('u'=>'promotions','cache'=>'promotions','include'=>'promotions.php','params'=>array());
  $dat[]=Array('u'=>'products','cache'=>'products','include'=>'category.php','params'=>array('id'=>1));
  $dat[]=Array('u'=>'product','cache'=>'products','include'=>'category.php','params'=>array('id'=>1));
  $dat[]=Array('u'=>'about','cache'=>'about','include'=>'about.php','params'=>array());
  $dat[]=Array('u'=>'faq','cache'=>'faq','include'=>'faq.php','params'=>array());
  $dat[]=Array('u'=>'contact','cache'=>'contact','include'=>'contact.php','params'=>array());
  $dat[]=Array('u'=>'news','cache'=>'news','include'=>'allNews.php','params'=>array());
  $dat[]=Array('u'=>'omisepayment','cache'=>'omisePayment','include'=>'omisePayment.php','params'=>array());
  $dat[]=Array('u'=>'register','cache'=>'register','include'=>'register.php','params'=>array());
  $dat[]=Array('u'=>'checkout','cache'=>'checkout','include'=>'checkout.php','params'=>array());
  $dat[]=Array('u'=>'articles','cache'=>'articles','include'=>'allActivity.php','params'=>array());
  $dat[]=Array('u'=>'miracle_eyes','cache'=>'miracle_eyes','include'=>'miracle_eyes.php','params'=>array());
  $dat[]=Array('u'=>'history','cache'=>'history','include'=>'history.php','params'=>array());
  $dat[]=Array('u'=>'download','cache'=>'download','include'=>'download.php','params'=>array());
  $url=implode('/', array_map('rawurlencode', explode('/', str_replace(' ','_','downddns/download.html'))));
  $dat[]=Array('u'=>$url,'cache'=>'downloadDDNS','include'=>'downloadDDNS.php','params'=>array());
  $url=implode('/', array_map('rawurlencode', explode('/', str_replace(' ','_','ของหายจ่ายจริง'))));
  $dat[]=Array('u'=>$url,'cache'=>'lostAndPay','include'=>'lostAndPay.php','params'=>array());
  $smPre='http:';
  $smLastMod='<lastmod>'.date('Y-m-d').'</lastmod>';
  $siteMap='<?xml version="1.0" encoding="UTF-8"?> <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
  $siteMap.="<url><loc>$smPre".baseUrl."/"."</loc>$smLastMod</url>";
  $siteMap.="<url><loc>$smPre".baseUrl."/promotions"."</loc>$smLastMod</url>";
  $siteMap.="<url><loc>$smPre".baseUrl."/products"."</loc>$smLastMod</url>";
  $siteMap.="<url><loc>$smPre".baseUrl."/product"."</loc>$smLastMod</url>";
  $siteMap.="<url><loc>$smPre".baseUrl."/about"."</loc>$smLastMod</url>";
  $siteMap.="<url><loc>$smPre".baseUrl."/faq"."</loc>$smLastMod</url>";
  $siteMap.="<url><loc>$smPre".baseUrl."/contact"."</loc>$smLastMod</url>";
  $siteMap.="<url><loc>$smPre".baseUrl."/news"."</loc>$smLastMod</url>";
  $siteMap.="<url><loc>$smPre".baseUrl."/register"."</loc>$smLastMod</url>";
  $siteMap.="<url><loc>$smPre".baseUrl."/articles"."</loc>$smLastMod</url>";
  $siteMap.="<url><loc>$smPre".baseUrl."/miracle_eyes"."</loc>$smLastMod</url>";
  $siteMap.="<url><loc>$smPre".baseUrl."/download"."</loc>$smLastMod</url>";
  $siteMap.="<url><loc>$smPre".baseUrl."/ของหายจ่ายจริง"."</loc>$smLastMod</url>";
  
  $whichSite=whichSite;
  $rs=q("select id,pName,hash from product where isDel='no' and cId in(select id from prodcat where root='$whichSite')");
  while($r=f($rs)){
    $id=$r['id'];
    $url=strtolower('product/'.$r['pName']);
    $url=implode('/', array_map('rawurlencode', explode('/', str_replace(' ','_',$url))));
    q("update product set uri='/$url' where id='$id'");
    $cache='product_'.$r['id'].'_'.$r['hash'];
    $dat[]=Array('u'=>$url,'cache'=>$cache,'include'=>'product.php','params'=>array('id'=>$id));
    $siteMap.="<url><loc>$smPre".baseUrl.'/'.$url."</loc>$smLastMod</url>";
  }


  $rs=q("select id,hash from prodcat where id>2 and root='$whichSite'");
  while($r=f($rs)){
    $id=$r['id'];
    $cName=_getCName($r['id'],'');
    $url=strtolower('product/'.$cName);
    $url=implode('/', array_map('rawurlencode', explode('/', str_replace(' ','_',$url))));
    q("update prodcat set uri='/$url' where id='$id'");
    $cache='category_'.$r['id'].'_'.$r['hash'];
    $dat[]=Array('u'=>$url,'cache'=>$cache,'include'=>'category.php','params'=>array('id'=>$id));
    $siteMap.="<url><loc>$smPre".baseUrl.'/'.$url."</loc>$smLastMod</url>";
  }

  $rs=q("select id,title,isPublished,hash from activity");
  while($r=f($rs)){
    $id=$r['id'];
    if($r['isPublished']=='checked'){
      $url=strtolower('article/'.str_replace('&','และ',$r['title']));
    }else{
      $url=strtolower('article/'.$r['hash']);
    }
    $url=implode('/', array_map('rawurlencode', explode('/', str_replace(' ','_',$url))));
    q("update activity set uri='/$url' where id='$id'");
    $cache='news_'.$r['id'].'_'.$r['hash'];
    $dat[]=Array('u'=>$url,'cache'=>$cache,'include'=>'activity.php','params'=>array('id'=>$id));
    $siteMap.="<url><loc>$smPre".baseUrl.'/'.$url."</loc>$smLastMod</url>";
  }
  $rs=q("select id,title,isPublished,hash from news");
  while($r=f($rs)){
    $id=$r['id'];
    if($r['isPublished']=='checked'){
      $url=strtolower('news/'.str_replace('&','และ',$r['title']));
    }else{
      $url=strtolower('news/'.$r['hash']);
    }
    $url=implode('/', array_map('rawurlencode', explode('/', str_replace(' ','_',$url))));
    q("update news set uri='/$url' where id='$id'");
    $cache='news_'.$r['id'].'_'.$r['hash'];
    $dat[]=Array('u'=>$url,'cache'=>$cache,'include'=>'news.php','params'=>array('id'=>$id));
    $siteMap.="<url><loc>$smPre".baseUrl.'/'.$url."</loc>$smLastMod</url>";
  }
  file_put_contents('../gen/cfgHash.php','<?$cfgHash="'.genRandom().'";?>');
  file_put_contents('../gen/config.dat',json_encode($dat));
  $siteMap.="</urlset>";
  file_put_contents(dirname(__FILE__).'/../../sitemap.xml',$siteMap);
  
  
  //gen js for include (product data)
  $prods="var prods=new Object();\r\n";
  $rs=q("select * from product where cId in(select id from prodcat where root='$whichSite')");
  while($r=f($rs)){
    $id=$r['id'];
    $title=$r['pName'];
    $deli=$r['deliCost'];
    $weight=$r['weight'];
    $priceRuleId=$r['priceRuleId'];
    $pointRuleId=$r['pointRuleId'];
    $fullPrice=$r['fullPrice'];
    $url=baseUrl.$r['uri'];
    $img=baseUrl.'/img/defaultProdThumb.png';
    if($r2=fq("select url from prodimg where pId='$id' order by sortIdx asc limit 1")){
      $img=motherUrl.$r2['url'];
    }
    $prods.="prods['$id']=(".json_encode(Array('title'=>$title,'url'=>$url,'img'=>$img,'price'=>$fullPrice,'ruleId'=>$priceRuleId,'pointId'=>$pointRuleId,'weight'=>$weight)).");\r\n";
  }
  //gen js for include (rules data)
  $prods.="var pr=new Object();\r\n";
  $rs=q("select * from prodpricerules");
  while($r=f($rs)){
    $id=$r['id'];
    $rules=$r['rules'];
    $prods.="pr['$id']=$rules;\r\n";
  }
  //gen js for include (points data)
  $prods.="var por=new Object();\r\n";
  $rs=q("select * from pointrules");
  while($r=f($rs)){
    $id=$r['id'];
    $amt=$r['pointAmount'];
    $prods.="por['$id']=$amt;\r\n";
  }
  //gen js for include (delivery data)
  $r=fq("select rules from delirules where id=1");
  $prods.="var deliCost=".$r['rules'].";\r\n";
  
  file_put_contents("../gen/config.js",$prods);
  genInventory();
  file_put_contents(dirname(__FILE__).'/../_genKenCfg.php','<?$need2genKenCfg=true;?>');
  //file_get_contents("http://kenprocctv.com/api?todo=genConfigNaJa");
}
function genInventory(){
  $whichSite=whichSite;
  $prods="var prodAmount=new Object();\r\n";
  $rs=q("select id,amount from product where cId in(select id from prodcat where root='$whichSite')");
  while($r=f($rs)){
    $id=$r['id'];
    $amount=$r['amount'];
    $prods.="prodAmount['$id']=$amount;\r\n";
  }
  file_put_contents("../gen/inventory.js",$prods);
}
function _getCName($id,$str){
  $r=fq("select parentId,cName from prodcat where id='$id'");
  $cName=$r['cName'].(($str=='')?'':'/').$str;
  if($r['parentId']>2){
    return _getCName($r['parentId'],$cName);
  }else{
    return $cName;
  }
}
function isKeyInChk(){
  if(isset($_SESSION['usrId'])){
    $qCon="and usrId='".$_SESSION['usrId']."'"; 
  }
  //ignore usr session
  $qCon='';
  $qStr="select id,poId from cart where isKeyIn='no' $qCon and cartStat<>'canceled' order by id asc";
  $rs=q($qStr);
  while($r=f($rs)){
    $id=$r['id'];
    $poId=$r['poId'];
    if(_isKeyIn($poId)=='yes')
      q("update cart set isKeyIn='yes' where id='$id'");
  }
}
function _isKeyIn($poId){
  $stat=file_get_contents("http://download.fujikocctv.com:3333/Credit/invoice.php?poid=$poId");
  return strpos($stat,'yes')?'yes':'no';
}
?>