<div class="container pb-5 mb-2 mb-md-3">
      <div class="row">
        <!-- Sidebar-->
        <aside class="col-lg-4 pt-4 pt-lg-0">
          <div class="cz-sidebar-static rounded-lg box-shadow-lg px-0 pb-0 mb-5 mb-lg-0">
            <div class="px-4 mb-4">
              <div class="media align-items-center">
                <div class="img-thumbnail rounded-circle position-relative" style="width: 6.375rem;">
                <img class="rounded-circle" src="<?=base_url("assets/uploads/users/thumb/").$this->session->userdata('img')?>" alt="<?=$this->session->userdata('name')?>" style="height: 87px;width: 87px;"></div>
                <div class="media-body pl-3">
                  <h3 class="font-size-base mb-0"><?=$_SESSION['name']?></h3><span class="text-accent font-size-sm"><?=$_SESSION['email']?></span>
                </div>
              </div>
            </div>
            <div class="bg-secondary px-4 py-3">
              <a href="<?=site_url('users#dashboardArea')?>"><h3 class="font-size-sm mb-0 text-muted">Dashboard</h3></a>
            </div>
            <ul class="list-unstyled mb-0">
              <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 <?php if($this->uri->segment(2)=='order_history') echo 'active'?>" href="<?=site_url('users/order_history#ordersArea')?>"><i class="czi-bag opacity-60 mr-2"></i>Orders</a></li>
              <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 <?php if($this->uri->segment(2)=='wishlist') echo 'active'?>" href="<?=site_url('users/wishlist#wishlArea')?>"><i class="czi-heart opacity-60 mr-2"></i>Wishlist</a></li>
              <li class="mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 <?php if($this->uri->segment(2)=='support') echo 'active'?>" href="<?=site_url('users/support#ticketArea')?>"><i class="czi-help opacity-60 mr-2"></i>Support tickets</a></li>
            </ul>
            <div class="bg-secondary px-4 py-3">
              <h3 class="font-size-sm mb-0 text-muted">Account settings</h3>
            </div>
            <ul class="list-unstyled mb-0">
              <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 <?php if($this->uri->segment(2)=='profile') echo 'active'?>" href="<?=site_url('users/profile#profileArea')?>"><i class="czi-user opacity-60 mr-2"></i>Profile info</a></li>
              <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 <?php if($this->uri->segment(2)=='shipping_address') echo 'active'?>" href="<?=site_url('users/shipping_address#shippingArea')?>"><i class="czi-user opacity-60 mr-2"></i>Shipping Address</a></li>
              <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 <?php if($this->uri->segment(2)=='changepassword') echo 'active'?>" href="<?=site_url('users/changepassword#changepwdArea')?>"><i class="czi-user opacity-60 mr-2"></i>Change Password</a></li>
              <li class="border-bottom mb-0"><a class="nav-link-style d-flex align-items-center px-4 py-3 " href="<?=site_url('login/logout')?>"><i class="czi-sign-out opacity-60 mr-2"></i><b>Sign out</b></a></li>
            </ul>
          </div>
        </aside>
        <!-- Content  -->