          <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
               
        <!-- Bootstrap core JavaScript-->
          <script src="../vendor/jquery/jquery.min.js"></script>
          <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

            <script>
            $('*[data-href]').on("click", function() {
                window.location = $(this).data("href");
            });
            </script>
            
        <script type="text/javascript">
            $('.respond-modal-btn').on("click", function () {
                    var id_value = $(this).data('id');
                    $('#modalticketNo').val(id_value);
            });
        </script>
          <!-- Core plugin JavaScript-->
          <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

          <!-- Page level plugin JavaScript-->
          <script src="../vendor/chart.js/Chart.min.js"></script>
          <script src="../vendor/datatables/jquery.dataTables.js"></script>
          <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>

          <!-- Custom scripts for all pages-->
          <script src="../js/sb-admin.min.js"></script>

          <!-- Demo scripts for this page-->
          <script src="../js/demo/datatables-demo.js"></script>
          <script src="../js/demo/chart-area-demo.js"></script>

        
    </body>

</html>
