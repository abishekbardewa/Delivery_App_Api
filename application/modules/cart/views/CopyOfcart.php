<?php if(!$this->cart->contents()){//navDropdown required?>
<b>Cart is empty</b>
<?php }else{?>
<div class="table-responsive">
<table class="table table-striped" id="carttable">
<tr>
<th>Product</th>
<th>Image</th>
<th style="text-align:right;">Price</th>
<th style="text-align:right;">Qty</th>
<th style="text-align:right;">Total</th>
<th style="text-align:right;">Remove</th>
</tr>
<?php 
$i = 1;
foreach ($this->cart->contents() as $items): 
$product_id=$items['id'];
$product_link=site_url("item/view/$product_id" );
echo form_hidden($i.'[rowid]', $items['rowid']); ?>
<tr>
  <td>
	<?="<a href='$product_link' title='($product_id)'><b>".character_limiter($items['name'],30)."</a></b> <small>(#ID: P20$product_id)</small>"; ?>
	<small><br><?=@$items['options']['note']?></small>
  </td>
  <td><a href="<?=$product_link?>"><img src="<?=base_url("assets/uploads/products/".$items['options']['image'])?>" class="img-responsive" height="100px" width="75px"></a></td>
  <td style="text-align:right">Rs <?=$this->cart->format_number($items['price']); ?></td>
  <td style="text-align:right">
	  <input type="hidden" name="<?=$i.'[pid]'?>" value="<?=$product_id?>">
	  <input type="number" name="<?=$i.'[qty]';?>" value="<?=$items['qty'];?>" maxlength="2" style="width:65px" class="form-control" />
  </td>
  <td style="text-align:right">Rs. <?=$this->cart->format_number($items['subtotal']); ?></td>
  <td style="text-align:right"><a href="<?=site_url();?>/cart/delete?id=<?=$items['rowid'];?>" class="btn btn-danger btn-sm"><span class="czi-trash"></span> </a>&nbsp;&nbsp;</td>
</tr>
<?php 
$i++; 
endforeach;?>
</table>
</div>
<style>
.table td a{font-size: 12px;}
</style>
<?php }?>