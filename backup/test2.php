<html>
<head>
<title>ThaiCreate.Com jQuery Tutorials</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

 $("#btn1").click(function(){

   $.post("test3.php", {
   data1: $("#txt1").val()},
    function(result){
     $("#div1").html(result);
    }
   );

  });
 });
</script>


</head>
<body>
<input type="text" id="txt1">
<input type="text" id="txt2">
<input type="button" id="btn1" value="Load">
<div id="div1"></div>

</body>
</html>
