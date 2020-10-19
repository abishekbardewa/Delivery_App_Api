<div class="page-title-overlap bg-dark pt-4">
  <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
    <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
          <li class="breadcrumb-item"><a class="text-nowrap" href="<?=site_url('home')?>"><i class="czi-home"></i>Home</a></li>
          <li class="breadcrumb-item text-nowrap"><a href="<?=site_url('cart')?>">Cart</a>
          </li>
          <li class="breadcrumb-item text-nowrap active" aria-current="page">Review</li>
        </ol>
      </nav>
    </div>
    <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
      <h1 class="h3 text-light mb-0">Review & confirm</h1>
    </div>
  </div>
</div>

<div class="container pb-5 mb-2 mb-md-4">
      <div class="row">
        <section class="col-lg-8">
          <!-- Steps-->
          <div class="steps steps-light pt-2 pb-3 mb-5"><a class="step-item active" href="<?=site_url('cart')?>">
              <div class="step-progress"><span class="step-count">1</span></div>
              <div class="step-label"><i class="czi-cart"></i>Cart</div></a><a class="step-item active" href="<?=site_url('cart/checkout?show=1')?>">
              <div class="step-progress"><span class="step-count">2</span></div>
              <div class="step-label"><i class="czi-user-circle"></i>Your details</div></a><a class="step-item active" href="<?=site_url('cart/shipping')?>">
              <div class="step-progress"><span class="step-count">3</span></div>
              <div class="step-label"><i class="czi-package"></i>Shipping</div></a><a class="step-item active" href="<?=site_url('cart/payment')?>">
              <div class="step-progress"><span class="step-count">4</span></div>
              <div class="step-label"><i class="czi-card"></i>Payment</div></a><a class="step-item active current" href="<?=site_url('cart/review')?>">
              <div class="step-progress"><span class="step-count">5</span></div>
              <div class="step-label"><i class="czi-check-circle"></i>Review & Confirm</div></a></div>
          
               <?php 
               $coupon_disc=@$_SESSION['coupon_disc']?$_SESSION['coupon_disc']:0;
               if(validation_errors()){echo '<div class="alert alert-danger">'.validation_errors()."</div>";}
               ?>
               <?php if(@$this->session->flashdata('msg')){
                    echo "<div class='alert alert-info'>".$this->session->flashdata('msg')."</div>";
                }?>
              <h2 class="h6 pt-1 pb-3 mb-3 border-bottom">Review your details</h2>
              <div class="bg-secondary rounded-lg px-4 pt-4 pb-2">
                <div class="row">
                    <div class="col-sm-8">
                    <h2><small>Order Total</small>&nbsp; &nbsp;  &#8377; <?=$_SESSION['cart_total']+$_SESSION['shipping']-$coupon_disc?></h2>
                    
                    Payment Method: <b style="color:green">Cash on Delivery</b>
                    </div>
                    <div class="col-sm-4">
                    <img src="<?=base_url('assets/w/cash.png')?>" alt="">
                    </div>
                  <div class="col-sm-6">
                  <br>
                        <small>Your Informations</small>
                        <hr><br>
                    <?php 
                    $where['user_id']=$this->session->userdata('user_id');
                    $uq=$this->db->where($where)->get('users')->result();?>
                    <h4 class="h6"><?=@$uq[0]->name?></h4>
                    <label>Phone: <b><?=@$uq[0]->phone?></b></label><br>
                    <label>Email: <?=@$uq[0]->email?></label>
                  </div>
                  <div class="col-sm-6">
                  <br>
                  <small>Shipping Informations</small>
                        <hr><br>
                     <?php 
                     $swhere['user_id']=$this->session->userdata('user_id');
                     $swhere['addr_id']=$_SESSION['addr_id'];
                     $aq=$this->db->where($swhere)->get('users_address')->result();?>
                    <label>Full Name: <b><?=$aq[0]->name?> <?=$aq[0]->lname?></b></label><br>
                    <label>City: <b><?=$aq[0]->city?></b></label><br>
                    <label>Pin: <b><?=$aq[0]->pin?></b></label><br>
                    <label>Address: <b><?=$aq[0]->address?></b></label>
                  </div>  
                </div>
              </div>
               
               <div class="col-sm-12">
                  <div class=" d-lg-flex pt-4 mt-3 row">
                    <div class="w-50 pr-3">
                        <a class="btn btn-secondary btn-block" href="<?=site_url('cart/payment')?>">
                            <i class="czi-arrow-left mt-sm-0 mr-1"></i><span class="d-none d-sm-inline">Back to Payment</span>
                            <span class="d-inline d-sm-none">Back</span>
                        </a>
                    </div>
                    <div class="w-50 pl-2">
                        <a class="btn btn-primary btn-block" href="<?=site_url('cart/complete')?>">
                            <span class="d-none d-sm-inline">Confirm Order</span><span class="d-inline d-sm-none">Next</span><i class="czi-arrow-right mt-sm-0 ml-1"></i>
                        </a>
                    </div>
                  </div>
              </div>
                
        </section>
        <!-- Sidebar-->
        <?php include 'sideCart.php'?>
      </div>
    </div>