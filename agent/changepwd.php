<?php include 'header.php'; ?>

<div class="container mx-auto mt-5">
    <form method="post" action="changepwd.php">
        <h1 class="d-flex justify-content-center">Change Password</h1>
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

<?php include('footer.php')?>