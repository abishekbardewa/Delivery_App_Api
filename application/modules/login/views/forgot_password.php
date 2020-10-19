<div class="bg-dark py-4">
      <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-start">
              <li class="breadcrumb-item"><a class="text-nowrap" href="<?=site_url('home')?>"><i class="czi-home"></i>Home</a></li>
              <li class="breadcrumb-item text-nowrap active" aria-current="page">Forgot Password</li>
            </ol>
          </nav>
        </div>
        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
          <h1 class="h3 mb-0">Forgot Password</h1>
        </div>
      </div>
    </div>
    <!-- Page Content-->
    <!-- Contact detail cards-->
    <section class="container-fluid pt-grid-gutter">
		<div class="container">
			<div class="col-sm-6 offset-sm-3">
			<?php //print_r($_SESSION)?>
			<?=@$_SESSION['msg']?><br>
			    <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link <?php if(!@$_GET['type']) echo 'active'?>" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Recover by Email</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link <?php if(@$_GET['type']=='phone') echo 'active'?>" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Recover by Phone Number</a>
                  </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade  <?php if(!@$_GET['type']) echo 'show active'?>" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form action="<?php echo site_url('login/pwd_by_email')?>" method="post">
                      <label for="name">Email</label><br>
    				  <input name="email" type="email" class="form-control" value="<?=@$_POST['email']?>" placeholder="Email Address"><br>
    				  <button type="submit" class="btn btn-success">Send OTP in Mail</button>
    				</form>
                  </div>
                  <div class="tab-pane fade <?php if(@$_GET['type']=='phone') echo 'show active'?>" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    				  <?php if(@$_GET['otp']){?>
    				  <form action="<?php echo site_url('login/verify_phn_otp')?>" method="post">
    				      <?php if(@$_GET['err']=='1') echo '<div class="alert alert-danger">Invalid OTP</div>'?>
        				  <small>OTP sent to ******<?=substr(@$_SESSION['phone'], 6,10)?></small><br>
        				  <label for="name">Enter OTP</label><br>
        				  <input name="otp" type="number" class="form-control" placeholder="Enter OTP"><br>  
        				  <button type="submit" class="btn btn-success">Validate OTP</button>
    				  </form>
    				  <?php }else{?>
    				  <form action="<?php echo site_url('login/pwd_by_phone')?>" method="post">
        				  <label for="name">Phone Number</label><br>
        				  <input name="phone" type="number" class="form-control" value="<?=@$_POST['phone']?>" placeholder="10 Digits Number"><br>
        				  <button type="submit" class="btn btn-success">Send OTP in Phone</button>
    				  </form>
    				  <?php }?>
    				
                  </div>
                </div>
			
			    
				<br><br><br><br><br><br>
			</div>
		</div>
	</section>