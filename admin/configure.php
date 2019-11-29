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
          <li class="breadcrumb-item active">Configuration</li>
        </ol>
         <form method="post" enctype="multipart/form-data" action="<?php echo $page?>">
             <div class='card mx-auto mb-3'>
                <div class="card-header">
                    <i class="fa fa-globe"></i> Website Details
                 </div>
                <div class="card-body">

                <div class="form-group">
                    <label for="site_url">Site URL</label>
                    <input type="text" class="form-control" name="site_url" placeholder="Enter site URL" value="<?php echo $_SESSION['siteinfo']['site_url']?>" required>
                 </div>
                <div class="form-group">
                    <label for="site_title">Site Title</label>
                    <input type="text" class="form-control" name="site_title" placeholder="Enter site title" value="<?php echo $_SESSION['siteinfo']['site_title']?>" required>
                 </div>

                 <div class="form-group">
                    <label for="name">Site Name</label>
                    <input type="text" class="form-control" name="site_name" placeholder="Enter site name" value="<?php echo $_SESSION['siteinfo']['site_name']?>" required>
                 </div>

                 <div class="form-group">
                    <label for="admin_email">Email Address</label>
                    <input type="email" class="form-control" name="admin_email" placeholder="Enter site email address" value="<?php echo $_SESSION['siteinfo']['admin_email']?>">
                 </div>
                </div>
             </div>
                
            <div class='card mx-auto text-white bg-primary'>
                <div class="card-header">
                    <i class="fa fa-comments"></i> Globe API Setting
                 </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label for="shortcode">Shortcode</label>
                            <input type="number" class="form-control" name="shortcode" placeholder="Enter Globe SMS API shortcode" value="<?php echo $_SESSION['siteinfo']['shortcode']?>">
                         </div>

                         <div class="form-group">
                            <label for="app_id">App ID</label>
                            <input type="text" class="form-control" name="app_id" placeholder="Enter Globe SMS API App ID" value="<?php echo $_SESSION['siteinfo']['app_id']?>">
                         </div>

                        <div class="form-group">
                            <label for="app_secret">App Secret</label>
                            <input type="text" class="form-control" name="app_secret" placeholder="Enter Globe SMS API App Secret" value="<?php echo $_SESSION['siteinfo']['app_secret']?>">
                         </div>
                    </div>
                </div>
            </div>
                <button type="submit" class="btn btn-success mt-5" name="savecfg_btn">Save &amp; Apply Changes </button>
            </form>
        </div>
    </div>
        </div>
</div>
<?php include('footer.php')?>