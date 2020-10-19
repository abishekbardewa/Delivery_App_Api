 <div id="maincontainer">
	<section id="product">
		<div class="container">
			
			<div class="row">
			
		<!-- Sidebar End-->
		<div class="span8 offset2">
		
		<?php 
		if (isset($_GET['cancelledmsg']))
		{
		?>
		<div class="alert alert-danger span4 offset1">
		<center><?php echo $_GET['cancelledmsg'];?></center>
		</div>
		
		<div class="span4 offset1">
		<center><img src="<?php echo base_url("assets/logo/cancelled.jpg")?>"></center>
		<br><br><br><br><br><br>
		</div>
		<?php }
		else {
		?>
		<h3>Order is Confirmed<br> Your order No is:<?php if (isset($_GET['order_no'])) echo $_GET['order_no']?></h3>
		<?php echo $this->load->view('cart/view_order');?>
		<?php }?>
		</div>
			</div>
		</div>
	</section>
</div>