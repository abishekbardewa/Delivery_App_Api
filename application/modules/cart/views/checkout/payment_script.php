<?php
$MERCHANT_KEY = "gUduy2Hz";
$SALT = "48BJi1Y6xB";
// Merchant Key and Salt as provided by Payu.
$PAYU_BASE_URL = "https://sandboxsecure.payu.in";		// For Sandbox Mode
//$PAYU_BASE_URL = "https://secure.payu.in";			// For Production Mode
$action = '';
$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
//$hash = '';
// $posted = array();
$usr=$this->db->where('user_id',$_SESSION['user_id'])->get('users')->result();
$coupon_disc=@$_SESSION['coupon_disc']?$_SESSION['coupon_disc']:0;
$posted['key']=$MERCHANT_KEY;
$posted['txnid']=$txnid;
$posted['amount']=$_SESSION['cart_total']+$_SESSION['shipping']-$coupon_disc;
$wh['session_id']=$this->session->session_id;
$query=$this->db->select('p_id,name,cart.qty as qty,sp as value,cart.option')->where($wh)->join('products','p_id')->get('cart')->result();
$head_info=array("addr_id"=>@$_SESSION['addr_id'],"coupon_disc"=>@$_SESSION['coupon_disc'],"shipping"=>@$_SESSION['shipping'],'session_id'=>$this->session->session_id);
$qq=array();
foreach ($query as $r){
    array_push($qq,array('p_id'=>$r->p_id,
        "name"=>urlencode(word_limiter($r->name,3)),
        'qty'=>$r->qty,
        "sp"=>$r->value,
        'total'=>$r->qty*$r->value,
        'option'=>urlencode($r->option)));
}
// echo json_encode($qq);die();
$posted['productinfo']=json_encode(array("head"=>$head_info,"products"=>$qq));
$posted['firstname']=$_SESSION['name'];
$posted['lastname']=$_SESSION['lname'];
$posted['email']=$_SESSION['email'];
$posted['phone']=$usr[0]->phone;
$posted['surl']=base_url("payu/pay_success.php?id=$order_id");
$posted['furl']=base_url("payu/pay_failed.php?id=$order_id");
// $posted['curl']=site_url("cart/pay_failed/$order_id");
$posted['service_provider']="payu_paisa";
$posted['udf1']=@$order_id;
$posted['udf2']=@$_SESSION['user_id'];
// $posted['udf2']=@$_SESSION['coupon_disc']?$_SESSION['coupon_disc']:0;
// $posted['udf3']=@$_SESSION['shipping']?$_SESSION['shipping']:0;
// echo "<pre>";print_r($posted);die();
$formError = 0;
$hash = '';
// Hash Sequence
//$hashSequence = "key|txnid|amount|firstname|email|phone|productinfo|surl|udf1|udf2|udf3|udf6|udf7|udf8|udf9|udf10";
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
if(empty($posted['hash']) && sizeof($posted) > 0) {
  if(
          empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
          || empty($posted['surl'])
          || empty($posted['furl'])
		  || empty($posted['service_provider'])
  ) {
    $formError = 1;
  } else {
    //$posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
	$hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';	
	foreach($hashVarsSeq as $hash_var) {
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }
//     echo $hashSequence."<br>".$hash_string;die();
    $hash_string .= $SALT;
    $hash = strtolower(hash('sha512', $hash_string));
    $action = $PAYU_BASE_URL . '/_payment';
  }
} elseif(!empty($posted['hash'])) {
  $hash = $posted['hash'];
  $action = $PAYU_BASE_URL . '/_payment';
}
?>
  <script>
    var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }
  </script>
  <body onload="submitPayuForm()">
    <?php if($formError) { ?>
      <span style="color:red">Please fill all mandatory fields.</span>
      <br/>
      <br/>
    <?php } ?>
    <div class="container" style="text-align:center">
        <br><br>
        <img src="<?=base_url('assets/images/progress/ajax-loader9.gif')?>" alt="loader">
        <br><p>Redirecting to payment gateway...</p>
    </div>
    <form action="<?php echo $action; ?>" method="post" name="payuForm" style="visibility: hidden">
      <input type="hiddend" name="key" value="<?php echo $MERCHANT_KEY ?>" />
      <input type="hiddend" name="hash" value="<?php echo $hash ?>"/>
      <input type="hiddend" name="txnid" value="<?php echo $txnid ?>" />
      <input name="amount" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>" />
      <input name="firstname" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" />
      <input name="lastname" id="lastname" value="<?php echo (empty($posted['lastname'])) ? '' : $posted['lastname']; ?>" />
      <input name="email" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" />
      <input name="phone" value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" />
      <textarea name="productinfo"><?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?></textarea>
      <input name="surl" value="<?php echo (empty($posted['surl'])) ? '' : $posted['surl'] ?>" size="64" />
      <input name="furl" value="<?php echo (empty($posted['furl'])) ? '' : $posted['furl'] ?>" size="64" />
      <input type="hidden" name="service_provider" value="payu_paisa" size="64" />
       
         <input name="curl" value="<?php echo (empty($posted['curl'])) ? '' : $posted['curl'] ?>" size="64" />
        <input name="udf1" value="<?php echo (empty($posted['udf1'])) ? '' : $posted['udf1']; ?>" />
         <input name="udf2" value="<?php echo (empty($posted['udf2'])) ? '' : $posted['udf2']; ?>" />
        <input name="udf3" value="<?php echo (empty($posted['udf3'])) ? '' : $posted['udf3']; ?>" />
          <?php if(!$hash) { ?>
            <input type="submit" value="Submit" />
          <?php } ?>
    </form>
  </body>