
                     <br/>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

    </div><!-- ./wrapper -->
   
   
   
   
   
   <? include_once('_js.php');?>
    <script>
    var isBusy=false;
    var currIdx=0;
    var currPo='';
    var tbl;
    $(function () {
      tbl=$("#example1").DataTable({
        "iDisplayLength": 50
      });
    });
    </script>
  </body>

<!-- Sort Table by fnSort----------------------------------------------------------------->


<script>
$(document).ready(function() {
     var oTable = $('#example1').dataTable();
     oTable.fnSort( [ [1,'asc'] ] );
   });
</script>