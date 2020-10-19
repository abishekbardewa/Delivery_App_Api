<div class="page-title-overlap bg-dark pt-4">
  <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
    <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
          <li class="breadcrumb-item"><a class="text-nowrap" href="<?=site_url('home')?>"><i class="czi-home"></i>Home</a></li>
          <li class="breadcrumb-item text-nowrap"><a href="<?=site_url('cart')?>">Cart</a>
          </li>
          <li class="breadcrumb-item text-nowrap active" aria-current="page">Checkout</li>
        </ol>
      </nav>
    </div>
    <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
      <h1 class="h3 text-light mb-0">Checkout</h1>
    </div>
  </div>
</div>

<div class="container pb-5 mb-2 mb-md-4">
      <div class="row">
        <section class="col-lg-8">
          <!-- Steps-->
          <div class="steps steps-light pt-2 pb-3 mb-5"><a class="step-item active" href="<?=site_url('cart')?>">
              <div class="step-progress"><span class="step-count">1</span></div>
              <div class="step-label"><i class="czi-cart"></i>Cart</div></a><a class="step-item active current" href="javascript:void(0)">
              <div class="step-progress"><span class="step-count">2</span></div>
              <div class="step-label"><i class="czi-user-circle"></i>Your details</div></a><a class="step-item" href="javascript:void(0)">
              <div class="step-progress"><span class="step-count">3</span></div>
              <div class="step-label"><i class="czi-package"></i>Shipping</div></a><a class="step-item" href="javascript:void(0)">
              <div class="step-progress"><span class="step-count">4</span></div>
              <div class="step-label"><i class="czi-card"></i>Payment</div></a><a class="step-item" href="javascript:void(0)">
              <div class="step-progress"><span class="step-count">5</span></div>
              <div class="step-label"><i class="czi-check-circle"></i>Review & Confirm</div></a></div>
          
               <?php 
               if(validation_errors()){echo '<div class="alert alert-danger">'.validation_errors()."</div>";}
               ?>
               <?php if(@$this->session->flashdata('msg')){
    echo "<div class='alert alert-info'>".$this->session->flashdata('msg')."</div>";
}?>
              <?php if($this->session->userdata('user_id')){?>
              <h2 class="h6 pt-1 pb-3 mb-3 border-bottom">Your Profile</h2>
              <?php }else{?>
              <h2 class="h6 pt-1 pb-3 mb-3 border-bottom"> </h2>
              <ul class="nav nav-tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#">Create Account</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#signin-modal" data-toggle="modal">Sign In</a>
                  </li>
              </ul>
              <?php }?>
                  <form action="<?=site_url('cart/checkout?show=1')?>" method="post">
                        <div class="row">              
                          <div class="col-sm-4">
                            <label>Full Name</label>
                            <input class="form-control" name='name' value="<?=@$_POST['name']?>">
                          </div>
                          <div class="col-sm-4">
                            <label>Gender</label>
                            <select class="form-control" name='gender'>
                                <option value="M" <?php if(@$_POST['gender']=="M") echo "selected"?>>Male</option>
                                <option value="F" <?php if(@$_POST['gender']=="F") echo "selected"?>>Female</option>
                            </select>
                          </div>
                          <div class="col-sm-4">
                            <label>Email address</label>
                            <input class="form-control" type="email" name='email' value="<?=@$_POST['email']?>">
                          </div>
                          <div class="col-sm-4">
                            <label>Phone</label>
                            <input class="form-control" type="number" value="<?=@$_POST['phone']?>" name='phone'>
                          </div>
                          <?php if(!$this->session->userdata('user_id')){?>
                          <div class="col-sm-4">
                            <label>Date of Birth</label>
                            <input class="form-control" type="date" value="<?=@$_POST['dob']?>" name='dob'>
                          </div>
                          <div class="col-sm-4">
                            <label>Password</label>
                            <input class="form-control" type="password" name='pass'>
                          </div>
                          <?php }?>
                          <div class="col-sm-4"><br>
                            <div class="custom-control custom-checkbox d-block">
                                <input class="custom-control-input" type="checkbox" id="subscribe_me" checked="checked" disabled>
                                <label class="custom-control-label" for="subscribe_me">Subscribe me to Newsletter</label>
                              </div>
                          </div>
                          
                           <div class="col-sm-12">
                              <div class=" d-lg-flex pt-4 mt-3 row">
                                <div class="w-50 pr-3">
                                    <a class="btn btn-secondary btn-block" href="<?=site_url('cart')?>">
                                        <i class="czi-arrow-left mt-sm-0 mr-1"></i><span class="d-none d-sm-inline">Back to Cart</span>
                                        <span class="d-inline d-sm-none">Back</span>
                                    </a>
                                </div>
                                <div class="w-50 pl-2">
                                    <button class="btn btn-primary btn-block" type="submit">
                                        <span class="d-none d-sm-inline">Proceed to Shipping</span><span class="d-inline d-sm-none">Next</span><i class="czi-arrow-right mt-sm-0 ml-1"></i>
                                    </button>
                                </div>
                              </div>
                          </div>
                      </div>
                 </form>
          
          
        </section>
        <!-- Sidebar-->
        <?php include 'checkout/sideCart.php'?>
      </div>
    </div>