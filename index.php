<?php include 'header.php';?>
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-body">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="form-group">
<!--            <div class="form-label-group">-->
              <input type="username" name="username" class="form-control" placeholder="Username" required="required" autofocus="autofocus">
<!--              <label for="username" >Username</label>-->
<!--            </div>-->
          </div>
          <div class="form-group">
<!--            <div class="form-label-group">-->
              <input type="password" name="password" class="form-control" placeholder="Password" required="required">
<!--              <label for="password">Password</label>-->
<!--            </div>-->
          </div>
          <button type="submit" class="btn btn-primary btn-block" name="login_btn">Login</button>
        </form>
        <div class="text-center">
          <a class="d-block small" href="forgot-password.html">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>
<?php include 'footer.php';?>
