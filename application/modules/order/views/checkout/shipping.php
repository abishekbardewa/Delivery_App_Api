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
              <div class="step-label"><i class="czi-cart"></i>Cart</div></a><a class="step-item active" href="<?=site_url('cart/checkout?show=1')?>">
              <div class="step-progress"><span class="step-count">2</span></div>
              <div class="step-label"><i class="czi-user-circle"></i>Your details</div></a><a class="step-item active current" href="javascript:void(0)">
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
              <h2 class="h6 pt-1 pb-3 mb-3 border-bottom">Select Shipping Address</h2>
                <?php include 'select_address.php';?>
          
          
        </section>
        <!-- Sidebar-->
        <?php include 'sideCart.php'?>
      </div>
    </div>