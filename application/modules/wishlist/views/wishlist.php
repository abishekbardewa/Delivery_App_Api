<?php if(@$this->session->flashdata('msg')){
    echo "<div class='alert alert-info'>".$this->session->flashdata('msg')."</div>";
}?>
<?php 
$this->db->join('products','p_id');
$wq=$this->db->where('user_id',$_SESSION['user_id'])->get('wishlist');
if($wq->num_rows()>0){?>
<table class="table table-striped" id="carttable">
<tr>
<th>Product</th>
<th>Image</th>
<th style="text-align:right;">Price</th>
<th style="text-align:right;">Remove</th>
</tr>
<?php 
foreach ($wq->result() as $w): 
$product_link=site_url("item/view/$w->p_id");?>
<tr>
  <td>
	<?php echo "<a href='$product_link' title='($w->p_id)'><b>".$w->name."</a></b> <small>(#ID: P20$w->p_id)</small>"; ?>
  </td>
  <td><a href="<?php echo $product_link?>"><img src="<?=base_url("assets/uploads/products/".$w->img)?>" class="img-responsive" height="100px" width="75px"></a></td>
  <td style="text-align:right">Rs.<?=$w->sp?></td>
  <td style="text-align:right"><a href="<?=site_url('wishlist/delete/'.$w->p_id);?>" class="btn btn-danger btn-sm"><span class="czi-trash"></span> </a>&nbsp;&nbsp;</td>
</tr>
<?php 
endforeach;?>
</table>
<?php }else{?>
<h2>No items in your Wishlist</h2>
<?php }?>