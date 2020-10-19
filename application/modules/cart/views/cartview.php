
<div class="bg-secondary py-4">
      <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-start">
              <li class="breadcrumb-item"><a class="text-nowrap" href="<?=site_url('home')?>"><i class="czi-home"></i>Home</a></li>
              <li class="breadcrumb-item text-nowrap active" aria-current="page">Cart</li>
            </ol>
          </nav>
        </div>
        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
          <h1 class="h3 mb-0">SHOPPING CART</h1>
        </div>
      </div>
    </div>
    <!-- Page Content-->
    <!-- Contact detail cards-->
    <section class="container" id="cartForm">
      <div class="row">
        <div class="col-xl-12">
        <br>
            <?php if (isset($_GET['emsg'])){?>
<div class="alert alert-danger">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
   <?php  echo $_GET['emsg'] ?>
</div>
<?php }?>

<?php if(@$this->session->flashdata('msg')){
    echo "<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button>".$this->session->flashdata('msg')."</div>";
}?>
<?php if (isset($_GET['smsg'])){?>
<div class="alert alert-success">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
   <?php  echo $_GET['smsg'] ?>
</div>
<?php }?>
            
<?php
$coupon_disc=@$_SESSION['coupon_disc']?$_SESSION['coupon_disc']:0;
$wh['session_id']=$this->session->session_id;
$query=$this->db->select('name,img,mrp,sp,cart.qty,cart.p_id,option')->where($wh)->join('products','p_id')->get('cart');
if($query->num_rows()<1){//navDropdown required?>
            <div class="row">	
    		<div class="col-sm-3"></div>
    		<div class="col-sm-6 text-center">
    		      <br><h3>Cart is empty</h3>
    		      <img src="<?=base_url("assets/w/empty-cart.png")?>">
    		</div>
    		<div class="col-sm-3"></div>
    		</div>
    		<?php }else{
    			include 'cart.php';
    		?>
    		<div class="row">
    		  <div class="col-sm-3">
    		  
              </div>
              <div class="col-sm-2">
              
              </div>
    		   <div class="col-sm-3">
    		   <br>
    		      <form onsubmit="return false">
                      <input class="form-control" name="promo" id="promoCode" placeholder="Promo code" required value="<?=@$_SESSION['coupon']?>" style="max-width:180px;margin-bottom:5px">
                      <button class="btn btn-secondary btn-sm" id="btnpromo" type="button" onclick='checkPromo()'>Apply promo code</button>
                      <div id='pres' style="height:50px;"><?=@$coupon_disc>0?'<span class="badge badge-success">Code Applied</span>':''?></div>
                  </form>
               </div>
               <div class="col-sm-3" style="text-align: right">
                <p>Coupon(-): &#8377; <?=$coupon_disc?></p>
                <h4>Total: &#8377; <?=$gtotal-$coupon_disc?></h4>
               </div>
            </div>
  <br>
  <div style="text-align: right">
            <a href="<?=site_url("home")?>"  class="btn btn-secondary" >Continue Shopping</a>
            <span class="pull-right"><a href="cart/checkout" class="btn btn-success"><span class=" icon-shopping-cart"></span> Proceed to Checkout</a>&nbsp;&nbsp;</span>
            </div>
            <?php }?>
            
            <br><br><br><br>
        </div>
      </div>
    </section>

    <style>
    #carttable, .table td a{font-size: 15px;}
.cart_text{position:absolute;
           z-index:1001;
           padding-left:44px;
		   margin-top:62px;
           color:;
           font-size:26px;
           transform:rotate(-33deg);}
.cart_text2{position:absolute;
           z-index:1001;
           padding-left:65px;
		   margin-top:80px;
           color:#c00202;
           font-size:26px;
           transform:rotate(-36deg);}
</style>