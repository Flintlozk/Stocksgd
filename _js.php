<div id='loadingAni'></div>
    <!-- jQuery 2.1.4 -->
    <script src="./plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="./plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="./plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="./plugins/select2/select2.full.min.js"></script>
    <!-- InputMask -->
    <script src="./plugins/input-mask/jquery.inputmask.js"></script>
    <script src="./plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="./plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="./plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap color picker -->
    <script src="./plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
    <!-- bootstrap time picker -->
    <script src="./plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="./plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="./plugins/iCheck/icheck.min.js"></script>
    <!-- FastClick -->
    <script src="./plugins/fastclick/fastclick.min.js"></script>
    <!-- ckeditor -->
    <script src="./plugins/ckeditor/ckeditor.js"></script>
    <!-- AdminLTE App -->
    <script src="./dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="./dist/js/demo.js"></script>
      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!-- for sortable -->
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="../js/common.js"></script>
    <!-- magnify -->
    <script src="../js/bootstrap-magnify.min.js"></script>
    <script>
      $(function(){
        $('.fa-sign-out').parent().click(function(){
          $.getJSON('index.php',{todo:'logout'},function(data){
            document.location='index.php';
          });
        });
        <?
          include_once(dirname(__FILE__).'/_genKenCfg.php');
          if($need2genKenCfg){?>
            $.get("http://kenprocctv.com/api?todo=genConfigNaJa",function(data){
              $.get('index.php',{todo:'doneGenKenCfg'},function(){
                if(document.location.href.indexOf('activitiesk.php')>0||document.location.href.indexOf('newsk.php')>0){
                  document.location.reload();
                }
              });
            });
          <?}
        ?>
      });
    </script>

<style>
  #loadingAni.busy{
    top: 0px;
  }
  #loadingAni{
    border-color: #bbb transparent transparent;
    border-style: solid;
    border-width: 50px;
    left: 50%;
    margin-left: -85px;
    position: fixed;
    top: -50px;
    width: 170px;
    z-index: 99999;
    transition-duration:0.2s;
    transition-timing-function:ease-in-out;
  }
  #loadingAni::after{
    background-image: url("../img/gear.gif");
    background-size: contain;
    content: "";
    height: 52px;
    left: -5px;
    position: absolute;
    top: -49px;
    width: 75px;
  }
</style>    