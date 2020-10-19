<script type="text/javascript">
	$(document).ready(function(){
	$('input,textarea,select').keypress(function(){
	$('input,textarea,select').css('text-transform','uppercase');
	});
	
	 if ($('input,textarea,select').val().length > 0) {
		    $("button").prop("disabled", false);
		  }
		  else {
		    $("button").prop("disabled", true);
		  }
	
		var current = 1;
		
		widget      = $(".step");
		btnnext     = $(".next");
		btnback     = $(".back"); 
		btnsubmit   = $(".submit");

		// Init buttons and UI
		widget.not(':eq(0)').hide();
		hideButtons(current);
		setProgress(current);

		// Next button click action
		btnnext.click(function(){
			if(current < widget.length){
				widget.show();
				widget.not(':eq('+(current++)+')').hide();
				setProgress(current);
			}
			hideButtons(current);
		})

		// Back button click action
		btnback.click(function(){
			if(current > 1){
				current = current - 2;
				btnnext.trigger('click');
			}
			hideButtons(current);
		})			
	});

	// Change progress bar action
	setProgress = function(currstep){
		var percent = parseFloat(100 / widget.length) * currstep;
		percent = percent.toFixed();
		$(".progress-bar").css("width",percent+"%").html(percent+"%");		
	}

	// Hide buttons according to the current step
	hideButtons = function(current){
		var limit = parseInt(widget.length); 

		$(".action").hide();

		if(current < limit) btnnext.show();
		if(current > 1) btnback.show();
		if (current == limit) { btnnext.hide(); btnsubmit.show(); }
	}
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-19096935-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  
</script>

 <div id="maincontainer">
	<section id="product">
		<div class="container">
			<!--  breadcrumb -->
			<ul class="breadcrumb">
				<li><a href="<?php echo site_url("home")?>">Home</a> <span class="divider">/</span></li>
				<li class="active">Checkout</li>
			</ul>
			<div class="row">
<!-- Form for Guest********************* -->
				
	<div class="span9">
	<h1 class="heading1">
	<span class="maintext"><i class="icon-ok-circle"></i> Checkout</span>
	</h1>
	<div class="checkoutsteptitle" id="CO">Step 1: Checkout Options<a class="modify">Modify</a>
	</div>
	<!--  <div class="checkoutstep">
	<section class="newcustomer ">
	
	<h3 class="heading3">New Customer</h3>
	<div class="loginbox">
	<p style="text-align:justify">		By creating an account you will be able to shop faster,
			be up to date on an order  status, and keep track of the
			orders you have previously made.
	</p>
	<br /> <a href="<?php echo site_url("users/register")?>" class="btn btn-orange">Register</a>
		   <a href="#BD" class="btn btn-orange">Guest Checkout</a>
	</div>
	</form>
	</section>

	<section class="returncustomer">
	<form class="form-horizontal" id="r_form" name="r_form" action="<?php echo site_url('login/check')?>" method="post" ><h3 class="heading3">Returning Customer</h3>
	<div class="loginbox">
	<fieldset>
	<div>
	<label>username</label>
	<div>
	<input type="text" class="span2" name="email" value="<?php echo set_value('email')?>">
	</div>
	</div>
	
	<div>
	<label>Password:</label>
	<div>
	<input type="password" class="span2" name="password" value="<?php echo set_value('password')?>">
	</div>
	</div>
	<a class="" href="<?php echo site_url("login/verify")?>">Forgotten Password</a> <br /> <br /> 
	<input type="Submit" class="btn btn-orange" value="Login">
	</fieldset>
	</div>
	</form>
	</section>
	</div>-->
	
	<!-- //END Step 1 Form -->
	
<!-- Checkout form -->				
<div class="row">
<form class="form-horizontal" action="<?php echo site_url('cart/check_checkout')?>" method="post" />
<div class="step"><!-- Step One(1) -->
<div class="checkoutsteptitle">Billing Details</div>
	<div class="span4">
	<label class="control-label">First Name</label>
	<div class="controls">
	<input type="text"  name="firstname"/>
	</div>
		
	<label class="control-label">Last Name</label>
	<div class="controls">
	<input type="text" name="lastname" ng-model="s" required />
	</div>
		
	<label class="control-label">Contact Number</label>
	<div class="controls">
	<input type="text" name="telephone" ng-model="sa" required />
	</div>
		
	<label class="control-label">E-Mail</label>
	<div class="controls">
	<input type="email"  name="email" ng-model="sd" required />
	</div>


	<label class="control-label">Address 1</label>
	<div class="controls">
	<textarea name="address1" style="width: 206px" ng-model="as" required></textarea>
	</div>
</div>
	
<div class="span4">	
	<label class="control-label">Address 2</label>
	<div class="controls">
	<textarea name="address2" style="width: 206px" ng-model="aas" required></textarea>
	</div>
		
	<label for="select01" class="control-label">Country
	</label>
	<div class="controls">
	<input type="text"  name="country" ng-model="sfdsa" required>
	</div>
					
		
	<label class="control-label">State</label>
	<div class="controls">
	<select id="Form1Field8" name="state" ng-model="sad" required>
	<option></option>
        	<?php 
               $this->db->select('state');
	           $this->db->distinct('state');
	           foreach ($this->db->get('india')->result() as $row2)
	           {
		     ?>
	    		<option><?php echo $row2->state ?></option>
            <?php }?>
	</select>
	</div>
		
	<label class="control-label">City</label>
	<div class="controls">
	<select  name="city" ng-model="ccs" required>
	<option></option>
	<?php 
               $this->db->select('city');
	           $this->db->distinct('city');
	           foreach ($this->db->get('india')->result() as $row2)
	           {
		     ?>
	    		<option><?php echo $row2->city ?></option>
            <?php }?>
	</select>
	<div class="col-lg-12" id="result"></div>
	</div>
	
	<label class="control-label">Post Code</label>
	<div class="controls">
	<input type="text"  name="postcode" ng-model="dsd" required />
	</div>
	</div>
</div><!-- //END Step One(1) -->
<div class="step"><!-- Step Two(2) -->
<div class="checkoutsteptitle">Delivery Details</div>
			<!-- Delivery Details form -->
	<div class="span4">
	<label class="control-label">First Name</label>
	<div class="controls">
	<input type="text"  name="firstname"/>
	</div>
		
	<label class="control-label">Last Name</label>
	<div class="controls">
	<input type="text" name="lastname" ng-model="s" required />
	</div>
		
	<label class="control-label">Contact Number</label>
	<div class="controls">
	<input type="text" name="telephone" ng-model="sa" required />
	</div>
		
	<label class="control-label">E-Mail</label>
	<div class="controls">
	<input type="email"  name="email" ng-model="sd" required />
	</div>


	<label class="control-label">Address 1</label>
	<div class="controls">
	<textarea name="address1" style="width: 206px" ng-model="as" required></textarea>
	</div>
</div>
	
<div class="span4">	
	<label class="control-label">Address 2</label>
	<div class="controls">
	<textarea name="address2" style="width: 206px" ng-model="aas" required></textarea>
	</div>
		
	<label for="select01" class="control-label">Country
	</label>
	<div class="controls">
	<input type="text"  name="country" ng-model="sfdsa" required>
	</div>
					
		
	<label class="control-label">State</label>
	<div class="controls">
	<select id="Form1Field8" name="state" ng-model="sad" required>
	<option></option>
        	<?php 
               $this->db->select('state');
	           $this->db->distinct('state');
	           foreach ($this->db->get('india')->result() as $row2)
	           {
		     ?>
	    		<option><?php echo $row2->state ?></option>
            <?php }?>
	</select>
	</div>
		
	<label class="control-label">City</label>
	<div class="controls">
	<select  name="city" ng-model="ccs" required>
	<option></option>
	<?php 
               $this->db->select('city');
	           $this->db->distinct('city');
	           foreach ($this->db->get('india')->result() as $row2)
	           {
		     ?>
	    		<option><?php echo $row2->city ?></option>
            <?php }?>
	</select>
	<div class="col-lg-12" id="result"></div>
	</div>
	
	<label class="control-label">Post Code</label>
	<div class="controls">
	<input type="text"  name="postcode" ng-model="dsd" required />
	</div>
	</div>
	</form>
		</div><!-- //END Step two(2) -->
		
		<div class="step"><!-- Step three(3) -->
<div class="checkoutsteptitle">Shopping Cart Details</div>
			<div class="cart-info">
	<?php 
		$this->load->view('cart/cart');
		?>
		</div>
		
		</div><!-- //END step three(3) -->
		<div class="step"><!-- Step four(4) -->
<div class="checkoutsteptitle">Confirm Billing</div>
	<div class="span2 pull-left">
	<div class="control">
	Use Voucher
	<input type="text" class="required" id="name" value="" name="name" />
	<input type="submit" class="btn btn-orange pull-right" value="Go" />
	</div>
	</div>
	
</div><!-- //EDN step four(4) -->

<!-- Button -->
<div class="span3 pull-right">
<br>
		<button class="action back btn btn-info">Back</button>
		<button class="action next btn btn-info">Next</button>
		<input  type="submit" class="action submit  btn btn-success" value="Confirm Order">
		<br><br><br><br><br>
</div>
</form>
<!-- //Button -->
</div>

	</div>
				<!-- Guest form //END -->							
				
				<!-- Sidebar Start***************-->
				<div class="span3">
					<aside>
						<div class="sidewidt">
							<h2 class="heading2">
								<span><i class="icon-list-ol"></i> Checkout Steps</span>
							</h2>
							<ul class="nav nav-list categories">
								<li><a class="active" href="#CO">Checkout Options</a></li>
								<li><a href="#BD">Billing Details</a></li>
								<li><a href="#DD">Delivery Details</a></li>
								<li><a href="#PM"> Payment Method</a></li>
							</ul>
						</div>
					</aside>
				</div>
				<div class="span3">
					<aside>
						<!-- ADD panel goes here -->
					</aside>
				</div>
				<!-- Sidebar End-->
			</div></div>
			</section>
		</div>

<!-- <script>
function fetch_data($scope,$http) 
{
	$http.get('<?php echo site_url('users/fetch_billing')?>')
	.success (function(data) {$scope.results=data;});
}
</script>-->