<div class="page-title-overlap bg-dark pt-4">
  <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
    <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
          <li class="breadcrumb-item"><a class="text-nowrap" href="<?=site_url('home')?>"><i class="czi-home"></i>Home</a></li>
          <li class="breadcrumb-item text-nowrap"><a href="<?=site_url('cart')?>">Cart</a>
          </li>
          <li class="breadcrumb-item text-nowrap active" aria-current="page">Payment</li>
        </ol>
      </nav>
    </div>
    <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
      <h1 class="h3 text-light mb-0">Payment</h1>
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
              <div class="step-label"><i class="czi-package"></i>Shipping</div></a><a class="step-item active current" href="<?=site_url('cart/payment')?>">
              <div class="step-progress"><span class="step-count">4</span></div>
              <div class="step-label"><i class="czi-card"></i>Payment</div></a><a class="step-item" href="javascript:void(0)">
              <div class="step-progress"><span class="step-count">5</span></div>
              <div class="step-label"><i class="czi-check-circle"></i>Review & Confirm</div></a></div>
          
               <?php 
               if(validation_errors()){echo '<div class="alert alert-danger">'.validation_errors()."</div>";}
               ?>
               <?php if(@$this->session->flashdata('msg')){
                    echo "<div class='alert alert-info'>".$this->session->flashdata('msg')."</div>";
                }
                $coupon_disc=@$_SESSION['coupon_disc']?$_SESSION['coupon_disc']:0;
                /*
                $wh['session_id']=$this->session->session_id;
                $totalqry=$this->db->select('sp,cart.qty')->where($wh)->join('products','p_id')->get('cart')->result();
                $ctotal=0;
                foreach ($totalqry as $c){
                    $ctotal+=round($c->sp*$c->qty,2);
                }
                $_SESSION['cart_total']=$ctotal;*/
                ?>
                <table class="table table-hover" id="paytable">
                    <tr><td>Order Total</td><th>&#8377; <?=$_SESSION['cart_total']?></th></tr>
                    <tr><td>Shipping Cost +</td><th>&#8377; <?=$_SESSION['shipping']?></th></tr>
                    <tr><td>Coupon -</td><th>&#8377; <?=$coupon_disc?></th></tr>
                    <tr><td>Grand Total</td><th><h5>&#8377; <?=$_SESSION['cart_total']+$_SESSION['shipping']-$coupon_disc?></h5></th></tr>
                </table>
                
              <h2 class="h6 pt-1 pb-3 mb-3 border-bottom">Select Payment Method</h2>
              
              <form action="<?=site_url('cart/payment_method')?>" method="post">
              <div class="bg-secondarys rounded-lg px-4 pt-4 pb-2 table-responsive">
              <table class="table table-hover font-size-sm border-bottom">
                  <thead class="thead-dark">
                    <tr>
                      <th class="align-middle">Action</th>
                      <th class="align-middle">Payment Method</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="custom-control custom-radio mb-4">
                          <input class="custom-control-input" type="radio" id="pay1" name="payment[]" value="cod">
                          <label class="custom-control-label" for="pay1"></label>
                        </div>
                      </td>
                      <td ><span class="text-dark font-weight-medium"><label for="pay1">&#8377; <?=$_SESSION['cart_total']+$_SESSION['shipping']-$coupon_disc?> <br>Cash on Delivery </label></span><img src="<?=base_url('assets/w/cash.png')?>" style="width:50px;height:50px"></td>
                    </tr>
                    <tr>
                      <td>
                        <div class="custom-control custom-radio mb-4">
                          <input class="custom-control-input" type="radio" id="pay2" name="payment[]" value="online" checked="checked">
                          <label class="custom-control-label" for="pay2"></label>
                        </div>
                      </td>
                      <td ><span class="text-dark font-weight-medium"><label for="pay2">&#8377; <?=$_SESSION['cart_total']+$_SESSION['shipping']-$coupon_disc?> 
                      <br>Pay Online &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </label></span><img src="<?=base_url('assets/w/img/cards-alt.png')?>" style="height:30px"></td>
                    </tr>
                  </tbody>
               </table>
               </div>
               <div class="col-sm-12">
                  <div class=" d-lg-flex pt-4 mt-3 row">
                    <div class="w-50 pr-3">
                        <a class="btn btn-secondary btn-block" href="<?=site_url('cart/shipping')?>">
                            <i class="czi-arrow-left mt-sm-0 mr-1"></i><span class="d-none d-sm-inline">Back to Shipping</span>
                            <span class="d-inline d-sm-none">Back</span>
                        </a>
                    </div>
                    <div class="w-50 pl-2">
                        <button class="btn btn-primary btn-block" type="submit">
                            <span class="d-none d-sm-inline">Proceed to Confirm</span><span class="d-inline d-sm-none">Next</span><i class="czi-arrow-right mt-sm-0 ml-1"></i>
                        </button>
                    </div>
                  </div>
              </div>
                </form>
        </section>
        <!-- Sidebar-->
        <?php include 'sideCart.php'?>
      </div>
    </div>
    <style>
#paytable th, #paytable td {padding: 2px;font-size: 14px;}
</style>