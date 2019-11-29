<?php include 'header.php';?>
    <div id="wrapper">
<?php include 'sidebar.php';?>
     <div id="content-wrapper">
         <div class="container-fluid">
             
             <!-- Breadcrumbs-->
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="#">Settings</a>
              </li>
              <li class="breadcrumb-item active">Change Password</li>
            </ol>

        <div class="card mx-auto">
            <div class="card-header">
                    <i class="fa fa-key"></i> Change Passsword
            </div>
            <div class="card-body">
                <form method="post" action="changepwd.php">
                    <div class="form-group">
                        <label for="currentpwd">Current password</label>
                        <input type="password" class="form-control" name="currentpwd" placeholder="Enter current password" required>
                    </div>
                    <div class="form-group">
                        <label for="newpwd">New Password</label>
                        <input type="password" class="form-control" name="newpwd" placeholder="Enter new password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmpwd">Confirm New Password</label>
                        <input type="password" class="form-control" name="confirmpwd" placeholder="Confirm new password" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" name="chpwd_btn">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<?php include('footer.php')?>