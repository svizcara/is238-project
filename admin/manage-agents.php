<?php include 'header.php'; ?> 
    <div id="wrapper">
        <?php include 'sidebar.php';?>
         <div id="content-wrapper">
            <div class="container-fluid">
         <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Users</a>
          </li>
          <li class="breadcrumb-item active">Agents</li>
        </ol>
                <a class="btn btn-primary mb-5" name="register_btn" href="register.php">Add New Agent </a>
                 <!-- List Agents -->
                <div class="card mb-3">
                  <div class="card-header">
                    <i class="fas fa-users"></i>
                    Agents</div>

                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                          <?php list_agents() ?>
                        </table>
                    </div>
                  </div>
                  <div class="card-footer small text-muted"></div>
                </div>
             </div>
             </div>
        </div>
<?php include('footer.php')?>