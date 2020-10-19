<?php 
$wh['session_id']=$this->session->session_id;
$query=$this->db->select('name,img,mrp,sp,cart.qty,cart.p_id,option')->where($wh)->join('products','p_id')->get('cart');
if($query->num_rows()<1){//navDropdown required?>
<b>Cart is empty</b>
<?php }else{?>
<form action="<?=site_url('cart/update')?>" method="post" id="cartForm1">
    <div class="table-responsive">
    <table class="table table-striped" id="carttable1">
    <tr style="background: #e2e2e2">
    <th>Product</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Total</th>
    <th></th>
    </tr>
    <?php 
    $gtotal=0;
    foreach ($query->result() as $c):
        $rtotal=0;
        $p_id=$c->p_id;
        $product_link=site_url("item/view/$p_id"); ?>
        <tr>
          <td>
          <a href="<?=$product_link?>"><img src="<?=base_url("assets/uploads/products/".$c->img)?>" class="img-responsive" height="100px" width="75px"></a>
        	<?="<a href='$product_link' title='($p_id)'><b>".character_limiter($c->name,30)."</a></b> <small>(#P20$p_id)</small>";?>
        	<small><br><?=@$c->options?></small>
          </td>
          <td>&#8377;<?=round($c->sp,2); ?></td>
          <td>
        	  <input type="number" name="<?="qty$p_id"?>" value="<?=$c->qty?>" maxlength="2" class="form-control qtyinput" />
          </td>
          <td>&#8377;<?=$rtotal=$c->qty*$c->sp;?></td>
          <td><a href="<?=site_url();?>/cart/delete?id=<?=$c->p_id;?>" class="btn btn-danger btn-sm"><span class="czi-trash"></span> </a>&nbsp;&nbsp;</td>
        </tr>
        <?php 
        $gtotal+=$rtotal;
    endforeach;?>
    </table>
    </div>
    <button type="submit" class='btn btn-warning btn-sm'>Update Cart</button>
</form>
<style>
#carttable1, .table td a{font-size: 12px;}
#carttable1 tr td,#carttable1 tr th{padding: .35rem;}
.qtyinput{width: 44px;padding: 0px 5px;}
#carttable1 img{margin-right:5px;width: 50px;height: 50px;}
#cartForm .btn-sm, .btn-group-sm>.btn {padding: .25rem .35rem;}
</style>
<?php }?>