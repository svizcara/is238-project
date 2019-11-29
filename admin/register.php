<?php include 'header.php';?>
<div id="wrapper">
<?php include 'sidebar.php';?>
    <div id="content-wrapper">
        <div class="container-fluid">
     <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Users</a>
          </li>
             <li class="breadcrumb-item">
            <a href="manage-agents.php">Agents</a>
          </li>
          <li class="breadcrumb-item active">Register</li>
        </ol>
            
            <div class="card mx-auto">
                <div class="card-header">
                    <i class="fa fa-key"></i> Register
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control" name="firstname" placeholder="First name" value="" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" class="form-control" name="lastname" placeholder="Last name" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" placeholder="Please enter a username" value="" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" name="email" placeholder="Please enter a valid email address" value="" required>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="passwd1">Password</label>
                                    <input type="password" class="form-control" name="passwd1" placeholder="Password" value="" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="passwd2">Confirm Password</label>
                                    <input type="password" class="form-control" name="passwd2" placeholder="Confirm Password" value="" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" name="register_btn">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php';?>