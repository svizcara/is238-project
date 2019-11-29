
        <footer class="fixed-bottom">
            <div class="footer">
                <nav class="nav">
                    <span class="nav-link">Copyright &copy; 2019 IS238 Group A (beta)</span>
                </nav>
            </div>
        </footer>
               
        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <script>
            window.setTimeout(function () {
                $(".alert-success").fadeTo(200, 0).slideUp(200, function () {
                $(this).remove();
                });
            }, 5000);
            
            window.setTimeout(function () {
                $(".alert-warning").fadeTo(200, 0).slideUp(200, function () {
                $(this).remove();
                });
            }, 5000);
        </script>
    </body>

</html>
