 <?php 
	 if (isset($_GET['order_no']))
	 { 
	 	$order_no=$_GET['order_no'];
	 ?>				 
				 
 <table class="table table-condensed table-hover">
 <tbody>
 <tr class="warning">
 <th>Product Name</th>
 <th>Colour</th>
 <th>Size</th>
 <th>Quantity</th>
 <th>Sub-Total</th>
 </tr>
 
 <?php
 $total=0; 
 $this->db->where('order_no',$order_no);
 $order=$this->db->get('order_details');
 foreach ($order->result() as $row)
 {
 	$total=$total+ ($row->unit_price*$row->quantity);
 ?>
 <tr>
 <td><?php echo $row->product_name?></td>
 <td><?php echo $row->color?></td>
 <td><?php echo $row->size?></td>
 <td><?php echo $row->quantity?></td>
 <td><?=$row->unit_price*$row->quantity?></td>
 
 </tr>
 
 <?php }?>
 <script>
function cancel_request() {
    var pass=confirm("Are you sure you want delete");
     if(pass==true)
	{
	return true;
	}
	else
	{
	return false;
	}
}
</script>
 <tr class="warning">
 
 <td>Total Amount</td>
 <td></td>
 <td></td>
 <td></td>
 <td colspan="4"><?php echo $total?> /-</td>
 </tr>
 
 <tr>
 <td><a href="track/" style="color:red">Track Order</a></td>
 <td colspan="2"><td>
 <td>
  <form action="<?php echo site_url("cart/cancel_order")?>" method="post">
  <input type="hidden" name="orderno" value="<?php echo $order_no?>">
  <input type="submit" class="btn btn-info btn-small" onclick="return cancel_request()"value="Cancel Order">
  </form>
  </td>
 <td></td>
 </tr>
 </tbody>
</table>
</div>
<?php }
else {
	echo "<div class='alert alert-danger'>Order ID not found...</div>";
}
?>