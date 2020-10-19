<aside class="col-lg-4 pt-4 pt-lg-0">
  <div class="cz-sidebar-static rounded-lg box-shadow-lg ml-lg-auto">
    <div class="widget mb-3">
      <h2 class="widget-title text-center">Order summary</h2>
      <?php 
      $coupon_disc=@$_SESSION['coupon_disc']?$_SESSION['coupon_disc']:0;
      $wh['session_id']=$this->session->session_id;
      $query=$this->db->select('name,img,mrp,sp,cart.qty,cart.p_id,option')->where($wh)->join('products','p_id')->get('cart');
      $gtotal=0;
      foreach ($query->result() as $c){
          $rtotal=0;
          $product_id=$c->p_id;
          $product_link=site_url("item/view/$product_id" );?>
          <div class="media align-items-center pb-2 border-bottom"><a class="d-block mr-2" href="<?=$product_link?>">
            <img width="64" src="<?=base_url("assets/uploads/products/".$c->img)?>" alt="<?=$c->name?>"></a>
            <div class="media-body">
              <h6 class="widget-product-title"><a href="<?=$product_link?>"><?=character_limiter($c->name,30)."</a></b> <small>(#ID: P20$product_id)</small>"?></a></h6>
              <div class="widget-product-meta"><span class="text-accent mr-2"><?=round($c->sp,2);?></span><span class="text-muted">x <?=$c->qty?> = &#8377; <?=$rtotal=$c->qty*$c->sp;?></span></div>
            </div>
          </div>
      <?php $gtotal+=$rtotal;}?>
    </div>
    <ul class="list-unstyled font-size-sm pb-2 border-bottom">
      <li class="d-flex justify-content-between align-items-center"><span class="mr-2">Subtotal:</span><span class="text-right"><?=$gtotal?></span></li>
      <li class="d-flex justify-content-between align-items-center"><span class="mr-2">Shipping:</span><span class="text-right"><?=@$_SESSION['shipping']?></span></li>
      <li class="d-flex justify-content-between align-items-center"><span class="mr-2">Coupon Discount:</span><span class="text-right">- <?=@$coupon_disc?></span></li>
    </ul>
    <h3 class="font-weight-normal text-center my-4">Rs <?=$gtotal+@$_SESSION['shipping']-$coupon_disc?></h3>
    <form  method="post" >
      <div class="form-group">
        <input class="form-control" name="promo" id="promoCode" placeholder="Promo code" required value="<?=@$_SESSION['coupon']?>">
      </div>
      <button class="btn btn-outline-primary btn-block" id="btnpromo" type="button" onclick='checkPromo()'>Apply promo code</button>
      <div id='pres' style="height:50px;"><?=@$coupon_disc>0?'<span class="badge badge-success">Code Applied</span>':''?></div>
    </form>
  </div>
</aside>
<script>

</script>