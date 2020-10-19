<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Cart extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->module('customer_activity');
		$this->load->model('mdl_cart');
	}
	function index()
	{
		echo "<pre>";
		print_r($_SESSION);
		echo "</pre>";
		die();
		$data['title'] = " Cart";
		$data['module'] = 'cart';
		$data['view_file'] = 'cartview';
		echo Modules::run('template/layout2', $data);
	}
	function navCartRender()
	{
		$this->load->view('mdl_cart');
	}
	function fetchJson()
	{
		// 		echo $this->session->session_id;
		// 		die();
		$wh['session_id'] = $this->session->session_id;
		//$wh['user_id'] = $this->session->user_id;
		$totalqry = $this->db->select('sp,cart.qty')->where($wh)->join('products', 'p_id')->get('cart')->result();
		$ctotal = 0;
		$i = 0;
		foreach ($totalqry as $c) {
			$ctotal += round($c->sp * $c->qty, 2);
			$i += $c->qty;
		}
		$_SESSION['cart_total'] = $ctotal;

		$d['total'] = 	$_SESSION['cart_total'];
		$d['rows'] = $i;
		$this->output->set_content_type('application/json')->set_output(json_encode($d));
	}
	function view()
	{

		$where['session_id'] = $this->session->session_id;
		$this->db->join('products', 'p_id');
		if (@$_GET['data'])
			$select = $_GET['data'];
		else
			$select = '*';
		$return = 	$this->mdl_cart->view_data($where, $select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
	function add($pid, $qty = 1, $note = '')
	{
		// echo $pid;
		// die();
		if ($pid && $qty) {
			$pq = $this->db->where('p_id', $pid)->get('products');
			if ($pq->num_rows() > 0) {
				$p = $pq->result(); //pname used to insert in customer activity
				$user_id = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : 0;
				$note = $note ? json_encode(array('note' => urldecode($note))) : '';
				if ($note == 'null' || $note == "undefined") $note = '';
				$data = array(
					'user_id' => $user_id,
					'session_id' => $this->session->session_id,
					'p_id' => $pid,
					'qty' => $qty,
					'option' => $note
				);

				//check if already added in cart, then only qty will be increased
				$wh['p_id'] = $pid;
				$wh['session_id'] = $this->session->session_id;
				$query = $this->db->where($wh)->get('cart');
				if ($query->num_rows() > 0) { //update
					$t = $query->result();
					$udata['qty'] = $qty + $t[0]->qty; //add user qty with previous cart qty
					$udata['option'] = $note;
					echo $this->db->where($wh)->update('cart', $udata);
				} else { //add
					//         		    $this->customer_activity->add('Cart Add',array('pid'=>$pid,'name'=>$p[0]->name));
					echo $this->db->insert('cart', $data);
				}
			} else {
				echo "Invalid Product ID";
			}
		} else {
			echo "Invalid request";
		}
	}

	public function update()
	{
		// 	    echo "<pre>";print_r($_POST);die();
		$wh['session_id'] = $this->session->session_id;
		$query = $this->db->where($wh)->get('cart');
		foreach ($query->result() as $c) {
			$udata['qty'] = $_POST["qty" . $c->p_id];
			$wh['p_id'] = $c->p_id;
			$this->db->where($wh)->update('cart', $udata);
		}
		$_SESSION['msg'] = "Cart Updated Successfully";
		$this->session->mark_as_flash('msg');
		redirect("cart#cartForm");
	}

	public function delete()
	{
		$id = $_GET['id'];
		if (@$id) {
			$pq = $this->db->where('p_id', $id)->get('products');
			if ($pq->num_rows() > 0) {
				$p = $pq->result(); //pname used to insert in customer activity
				// $this->customer_activity->add('Cart Delete', array('pid' => $id, 'name' => $p[0]->name));
				$data = array(
					'p_id' => $id,
					'session_id'   => $this->session->session_id
				);
				$this->db->where($data)->delete('cart');
				echo "1";
			} else {
				echo '0';
			}
		} else {
			echo "Invalid Request";
		}
	}

	public function deleteCart()
	{
		if ($this->session->session_id) {
			$data = $this->session->session_id;
			$this->db->where('session_id', $data)->delete('cart');
			echo "1";
		} else {
			echo "0";
		}
	}

	function tests()
	{
		$m = "GATE 2020 Electrical Engineering 3033";
		echo trim(word_limiter($m, 3), "&#8230;");
	}
	function checkout()
	{
		// 	    echo $data;die();
		if ($this->session->userdata('user_id') && $this->input->get('show') != 1) {
			redirect('cart/shipping');
		}
		if (!$this->input->post()) { //to fill the values from db
			if ($this->session->userdata('user_id')) {
				$pp = $this->db->where('user_id', $this->session->userdata('user_id'))->get('users')->result();
				$r = json_decode(json_encode($pp[0]), true); //converting the stdClass Objects to array
				$_POST = $r;
			}
		} else {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'Name', 'required|trim');
			$this->form_validation->set_rules('gender', 'Gender', 'required|trim');


			if (!$this->session->userdata('user_id')) {
				$this->form_validation->set_rules('phone', 'Phone', 'required|trim|numeric|is_unique[users.phone]');
				$this->form_validation->set_rules('email', 'Email', 'required|trim|is_unique[users.email]');
				//         	    $this->form_validation->set_rules('dob', 'Date of birth', 'trim');
				//         	    $this->form_validation->set_rules('pin', 'Pin', 'required|trim');
				//         	    $this->form_validation->set_rules('city', 'City', 'required|trim');
				//         	    $this->form_validation->set_rules('state', 'State', 'required|trim');
				//         	    $this->form_validation->set_rules('country', 'Country', 'required|trim');
				//         	    $this->form_validation->set_rules('address', 'Address', 'required|trim');
				$data['j_date'] = date("d/m/Y");
				$data['pass'] = md5($_POST['pass']);

				//         	    $ship['pin']=$_POST['pin'];
				//         	    $ship['city']=$_POST['city'];
				//         	    $ship['state']=$_POST['state'];
				//         	    $ship['country']=$_POST['country'];
				//         	    $ship['address']=$_POST['address'];
			} else {
				$this->form_validation->set_rules('phone', 'Phone', 'required|trim|numeric');
				$this->form_validation->set_rules('email', 'Email', 'required|trim');
			}
			if ($this->form_validation->run() == TRUE) {
				$data['name'] = $_POST['name'];
				$data['gender'] = $_POST['gender'];
				$data['phone'] = $_POST['phone'];
				$data['email'] = $_POST['email'];

				if (!$this->session->userdata('user_id')) {
					$this->db->insert('users', $data);
					$user_id = $this->db->insert_id();
					//     	            $ship['user_id']=$user_id;
					//     	            $this->db->insert('users_address',$ship);
					$this->load->module('login');
					$this->login->registeration_email($_POST['email'], $user_id, $_POST['phone']);
					$ses_data = array(
						'name' => $_POST['name'],
						'email' => $_POST['email'],
						'img' => 'default.png',
						'user_id' => $user_id
					);
					$this->session->set_userdata($ses_data);
					redirect('cart/shipping');
				} else {

					$wh['user_id'] = $this->session->userdata('user_id');
					$this->db->where($wh)->update('users', $data);
					redirect("cart/shipping");
				}
			}
		}

		$data['title'] = "Checkout Cart";
		$data['module'] = 'cart';
		$data['view_file'] = 'checkout';
		echo Modules::run('template/layout2', $data);
	}
	function shipping_add()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'First Name', 'required|trim');
		$this->form_validation->set_rules('lname', 'Last Name', 'required|trim');
		$this->form_validation->set_rules('pin', 'Pin', 'required|trim|numeric|exact_length[6]');
		$this->form_validation->set_rules('city', 'City', 'required|trim');
		$this->form_validation->set_rules('zone_id', 'State', 'required|trim');
		$this->form_validation->set_rules('country_id', 'Country', 'required|trim');
		$this->form_validation->set_rules('address', 'Address', 'required|trim|max_length[200]');
		if ($this->form_validation->run() == TRUE) {
			$data['name'] = $_POST['name'];
			$data['lname'] = $_POST['lname'];
			$data['pin'] = $_POST['pin'];
			$data['city'] = $_POST['city'];
			$data['zone_id'] = $_POST['zone_id'];
			$data['country_id'] = $_POST['country_id'];
			$data['address'] = $_POST['address'];
			$data['user_id'] = $this->session->userdata('user_id');

			if ($this->input->post('addr_id')) { //update
				$this->customer_activity->add('Address Update', array('addr_id' => $this->input->post('addr_id')));
				$chk = $this->db->where('addr_id', $this->input->post('addr_id'))->update('users_address', $data);
				$_SESSION['addr_id'] = $this->input->post('addr_id');
				$_SESSION['zone_id'] = $_POST['zone_id'];
				$this->view_shipping_cost($_POST['zone_id'], $_POST['pin'], 'php');
			} else { //add
				$this->customer_activity->add('Address Add', array('pin' => $_POST['pin']));
				$chk = $this->db->insert('users_address', $data);
				$_SESSION['addr_id'] = $this->db->insert_id();
				$_SESSION['zone_id'] = $_POST['zone_id'];
				$this->view_shipping_cost($_POST['zone_id'], $_POST['pin'], 'php');
			}


			echo 1;
		} else {
			echo validation_errors();
		}
	}
	function shipping_del()
	{
		if ($this->input->get('id')) {
			$this->customer_activity->add('Address Delete', array('addr_id' => $this->input->get('id'), 'pin' => $_POST['pin']));
			$chk = $this->db->where('addr_id', $this->input->get('id'))->where('user_id', $this->session->userdata('user_id'))->delete('users_address');
			$_SESSION['msg'] = "Shipping Address Deleted";
			$this->session->mark_as_flash('msg');
			redirect('cart/shipping');
		}
	}
	function shipping()
	{
		$data['shipqry'] = $this->db->select('users_address.name,lname,pin,zone_id,zone_master.name as zname,address,city,addr_id')->where('user_id', $_SESSION['user_id'])->join('zone_master', 'zone_id')->get('users_address');
		if (@$_POST) {
			// 	        echo "<pre>";print_r($_POST);die();
			$i = 0;
			foreach ($data['shipqry']->result() as $s) {
				if (@$_POST['addr'][0] == $s->addr_id) {

					$_SESSION['addr_id'] = $_POST['addr'][0];
					$_SESSION['zone_id'] = $s->zone_id;

					$this->view_shipping_cost($s->zone_id, $s->pin, 'php');
					redirect('cart/payment');
				}
				$i++;
				if ($data['shipqry']->num_rows() == $i) {
					$_SESSION['msg'] = "Please select a Shipping Address";
					$this->session->mark_as_flash('msg');
					redirect('cart/shipping');
				}
			}
		}
		$data['title'] = "Shipping Checkout Cart";
		$data['module'] = 'cart';
		$data['view_file'] = 'checkout/shipping';
		echo Modules::run('template/layout2', $data);
	}
	function view_shipping_cost($zone, $pincode = '', $res = 'txt')
	{
		if ($pincode) {
			$rr = $this->db->like('codes', $pincode, 'both')->get('pin_master');
			if ($rr->num_rows() > 0) {
				$c = $rr->result();
				$cost = $c[0]->rate;

				$_SESSION['shipping'] = $cost; //set the session
				if ($res == 'txt') echo "<p><strong>&#8377; $cost</strong></p>";
				else return $cost;
			} else {
				if ($zone != 1) {
					$rr = $this->db->where('zone_id', $zone)->get('zone_master')->result();
					$cost = $rr[0]->rate;

					$_SESSION['shipping'] = $cost; //set the session
					if ($res == 'txt') echo "<p><strong>&#8377; $cost</strong></p>";
					else return $cost;
				} else {
					echo "<small style='color:red'>Select State or Pincode</small>";
				}
			}
		} else {
			if ($zone != 1) {
				$rr = $this->db->where('zone_id', $zone)->get('zone_master')->result();
				$cost = $rr[0]->rate;

				$_SESSION['shipping'] = $cost; //set the session
				if ($res == 'txt') echo "<p><strong>&#8377; $cost</strong></p>";
				else return $cost;
			} else {
				echo "<small style='color:red'>Select State or Pincode</small>";
			}
		}
	}

	function payment()
	{
		$data['shipqry'] = $this->db->where('user_id', $_SESSION['user_id'])->get('users_address');
		if (@$_POST) {
			$i = 0;
			foreach ($data['shipqry']->result() as $s) {
				if (@$_POST['addr' . $s->addr_id] && $_POST['addr' . $s->addr_id]) {
					$_SESSION['addr_id'] = $s->addr_id;
					redirect('cart/payment');
				}
				$i++;
				if ($data['shipqry']->num_rows() == $i) {
					$_SESSION['msg'] = "Please select a Shipping Address";
					$this->session->mark_as_flash('msg');
					redirect('cart/shipping');
				}
			}
		}
		$data['title'] = "Payment Checkout Cart";
		$data['module'] = 'cart';
		$data['view_file'] = 'checkout/payment';
		echo Modules::run('template/layout2', $data);
	}

	function payment_method()
	{
		$data['view_file'] = 'checkout/payment';
		if (@$_POST) {
			if ($this->input->post('payment')[0] == "online") { //online
				$_SESSION['pay_method'] = "online";
				$data['order_id'] = $this->create_order();
				$this->customer_activity->add('Order Payment Initiated', array('order_id' => $data['order_id'], 'amt' => $_SESSION['cart_total'] + $_SESSION['shipping'] - (@$_SESSION['coupon_disc'] ? $_SESSION['coupon_disc'] : 0)));
				$data['view_file'] = 'checkout/payment_script';
			} else { //cod
				$_SESSION['pay_method'] = "cod";
				redirect('cart/review');
			}
		}
		$data['title'] = "Payment Checkout Cart";
		$data['module'] = 'cart';
		echo Modules::run('template/layout2', $data);
	}
	function testme()
	{
		// 	    $a["productinfo"] = '{"head":{"addr_id":"2699","coupon_disc":null,"shipping":"0","session_id":"56egc4kersf8e7lm0kcfn403dorokd78"},"products":[{"p_id":"854","description":"1","value":"110"}]}';
		// 	    $d=json_decode($a["productinfo"],TRUE);
		// 	    print_r($d);
		// 	    echo urlencode("High School English Grammar & Composition Ray & Martin (New Edition)");
	}

	function pay_failed($order_id = null)
	{
		// 	    echo "<pre>";print_r($_SESSION);print_r($_POST);die();
		if (@$order_id && @$_POST['udf1'] == $order_id) {
			$status = $_POST["status"];
			$firstname = $_POST["firstname"];
			$amount = $_POST["amount"];
			$txnid = $_POST["txnid"];
			$posted_hash = $_POST["hash"];
			$key = $_POST["key"];
			$productinfo = $_POST["productinfo"];
			$email = $_POST["email"];
			$udf1 = $_POST['udf1']; //order_id
			$udf2 = $_POST['udf2']; //user_id
			$salt = "48BJi1Y6xB";
			// Salt should be same Post Request

			if (isset($_POST["additionalCharges"])) {
				$additionalCharges = $_POST["additionalCharges"];
				$retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||' . $udf2 . '|' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
			} else {
				$retHashSeq = $salt . '|' . $status . '|||||||||' . $udf2 . '|' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
			}
			$hash = hash("sha512", $retHashSeq);
			if ($hash != $posted_hash) {
				$this->customer_activity->add('Payment Failed', array('order_id' => $udf1, 'amt' => $amount, 'txnid' => $_POST["txnid"]));

				$wh['order_id'] = $udf1;
				$wh['user_id'] = $udf2;
				$wh['ip'] = $_SERVER['REMOTE_ADDR'];
				$this->db->where($wh)->delete('order_details');

				$res['ip'] = $_SERVER['REMOTE_ADDR'];
				$res['user_id'] = $udf2;
				$res['txnid'] = $_POST["txnid"];
				$res['response'] = json_encode($_POST);
				$res['type'] = 0; //failed
				$res['date'] = date('d/m/Y');
				$this->db->insert('payment_response', $res);

				//                 session_destroy();//clear unanomous user
				//                 session_unset();
				redirect('cart/payment_method?msg=payment failed. invalid transaction');
			} else {
				$this->customer_activity->add('Payment Failed 2', array('order_id' => $udf1, 'amt' => $amount, 'txnid' => $_POST["txnid"]));
				$wh['order_id'] = $order_id;
				$wh['user_id'] = $udf2;
				$wh['ip'] = $_SERVER['REMOTE_ADDR'];
				$this->db->where($wh)->delete('order_details');

				$res['ip'] = $_SERVER['REMOTE_ADDR'];
				$res['user_id'] = $udf2;
				$res['txnid'] = $_POST["txnid"];
				$res['response'] = json_encode($_POST);
				$res['type'] = 0; //failed
				$res['date'] = date('d/m/Y');
				$this->db->insert('payment_response', $res);

				redirect('cart/payment_method?msg=payment failed with errors');
			}
		}
	}

	function pay_success($order_id = null)
	{
		// 	    echo "<pre>";print_r($_SESSION);print_r($_POST);die();
		if (@$order_id && @$_POST['udf1'] == $order_id) {

			$status = $_POST["status"];
			$firstname = $_POST["firstname"];
			$amount = $_POST["amount"];
			$txnid = $_POST["txnid"];
			$posted_hash = $_POST["hash"];
			$key = $_POST["key"];
			$productinfo = $_POST["productinfo"];
			$email = $_POST["email"];
			$udf1 = $_POST['udf1']; //order_id
			$udf2 = $_POST['udf2']; //user_id
			$salt = "48BJi1Y6xB";
			// Salt should be same Post Request

			if (isset($_POST["additionalCharges"])) {
				$additionalCharges = $_POST["additionalCharges"];
				$retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||' . $udf2 . '|' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
			} else {
				$retHashSeq = $salt . '|' . $status . '|||||||||' . $udf2 . '|' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
			}
			$hash = hash("sha512", $retHashSeq);
			if ($hash != $posted_hash) { //clear unanomous user
				$this->customer_activity->add('Payment Success Invalid', array('order_id' => $udf1, 'amt' => $amount, 'txnid' => $_POST["txnid"]));
				$wh['order_id'] = $udf1;
				$wh['user_id'] = $udf2;
				$wh['ip'] = $_SERVER['REMOTE_ADDR'];
				$this->db->where($wh)->delete('order_details');

				$res['ip'] = $_SERVER['REMOTE_ADDR'];
				$res['user_id'] = $udf2;
				$res['txnid'] = $_POST["txnid"];
				$res['response'] = json_encode($_POST);
				$res['type'] = 0; //failed
				$res['date'] = date('d/m/Y');
				$this->db->insert('payment_response', $res);

				//                 session_destroy();
				//                 session_unset();
				redirect('cart?msg=Invalid Transaction.');
			} else {
				$this->customer_activity->add('Payment Success', array('order_id' => $udf1, 'amt' => $amount, 'txnid' => $_POST["txnid"]));
				echo "<h3>Thank You. Your order status is " . $status . ".</h3>";
				echo "<h4>Your Transaction ID for this transaction is " . $txnid . ".</h4>";
				echo "<h4>We have received a payment of Rs. " . $amount . ". Your order will soon be shipped.</h4>";
				$wh['order_id'] = $udf1;
				$wh['user_id'] = $udf2;
				$wh['ip'] = $_SERVER['REMOTE_ADDR'];
				$this->db->where($wh)->update('order_details', array('pay_status' => 1, 'txnid' => $txnid));

				$res['ip'] = $_SERVER['REMOTE_ADDR'];
				$res['user_id'] = $udf2;
				$res['txnid'] = $_POST["txnid"];
				$res['response'] = json_encode($_POST);
				$res['type'] = 1; //success
				$res['date'] = date('d/m/Y');
				$this->db->insert('payment_response', $res);
				$this->complete_online($order_id, json_decode($productinfo, TRUE));
			}
		}
	}







	function failed_payment()
	{
		if ($this->input->get('res')) {
			$posted = json_decode(urldecode($this->input->get('res')), TRUE);
			$wh['order_id'] = $posted['udf1'];
			$wh['user_id'] = $posted['udf2'];
			$wh['ip'] = $_SERVER['REMOTE_ADDR'];
			$this->db->where($wh)->delete('order_details');

			$res['ip'] = $_SERVER['REMOTE_ADDR'];
			$res['user_id'] = $posted['udf2'];
			$res['txnid'] = $posted["txnid"];
			$res['response'] = json_encode($posted);
			$res['type'] = 0; //failed
			$res['date'] = date('d/m/Y');
			$this->db->insert('payment_response', $res);

			$this->customer_activity->add('Payment Failed', array('order_id' => $posted['udf1'], 'amt' => $posted['amount'], 'txnid' => $posted["txnid"]));

			redirect('cart/payment_method?msg=' . $this->input->get('msg'));
		}
	}
	function success_payment()
	{
		if ($this->input->get('res')) {
			$posted = json_decode(urldecode($this->input->get('res')), TRUE);
			$wh['order_id'] = $posted['udf1'];
			$wh['user_id'] = $posted['udf2'];
			$wh['ip'] = $_SERVER['REMOTE_ADDR'];
			$this->db->where($wh)->update('order_details', array('pay_status' => 1, 'txnid' => $posted['txnid']));

			$res['ip'] = $_SERVER['REMOTE_ADDR'];
			$res['user_id'] = $posted['udf2'];
			$res['txnid'] = $posted["txnid"];
			$res['response'] = json_encode($posted);
			$res['type'] = 1; //success
			$res['date'] = date('d/m/Y');
			$this->db->insert('payment_response', $res);
			$this->customer_activity->add('Payment Success', array('order_id' => $posted['udf1'], 'amt' => $posted['amount'], 'txnid' => $posted["txnid"]));

			$this->complete_online($posted);
		}
	}

	function review()
	{
		$data['title'] = "Review Checkout Cart";
		$data['module'] = 'cart';
		$data['view_file'] = 'checkout/review';
		echo Modules::run('template/layout2', $data);
	}

	function checkPromo()
	{
		if ($this->input->get('promoCode')) {
			$pqry = $this->db->where('code', $this->input->get('promoCode'))->get('promo_code');
			if ($pqry->num_rows() > 0) {
				$p = $pqry->result();
				$datefrom = $p[0]->date_from;
				$dateto = $p[0]->date_to;
				$datefrom = DateTime::createFromFormat('d/m/Y', $datefrom);
				$dateto = DateTime::createFromFormat('d/m/Y', $dateto);
				$today = new DateTime();
				if ($today->getTimestamp() >= $datefrom->getTimestamp() && $today->getTimestamp() <= $dateto->getTimestamp()) {
					$disc = ($_SESSION['cart_total'] * $p[0]->percent) / 100;
					$_SESSION['coupon_disc'] = $disc;
					$_SESSION['coupon'] = $this->input->get('promoCode');
					echo '1';
				} else {
					$_SESSION['coupon_disc'] = 0;
					$_SESSION['coupon'] = '';
					echo '<span class="badge badge-warning">Code Expired</span>';
				}
			} else {
				$_SESSION['coupon_disc'] = 0;
				$_SESSION['coupon'] = '';
				echo '<span class="badge badge-danger">Invalid Code</span>';
			}
		} else {
			$_SESSION['coupon_disc'] = 0;
			$_SESSION['coupon'] = '';
			echo '<span class="badge badge-danger">Enter Coupon Code</span>';
		}
	}

	function create_order()
	{
		$uqry = $this->db->where('user_id', $this->session->userdata('user_id'))->get('users')->result();

		$ord['user_id'] = $this->session->userdata('user_id');
		$ord['telephone'] = $uqry[0]->phone;
		$ord['payment_code'] = $_SESSION['pay_method'];
		$ord['comment'] = '';
		$ord['amt'] = $_SESSION['cart_total'];
		$ord['shipping'] = $_SESSION['shipping'];
		$ord['coupon'] = @$_SESSION['coupon'];
		$ord['coupon_disc'] = @$_SESSION['coupon_disc'] ? $_SESSION['coupon_disc'] : 0;
		$ord['total'] = $_SESSION['cart_total'] + $_SESSION['shipping'] - $ord['coupon_disc'];
		$ord['order_status_id'] = 1;
		$ord['ip'] = $_SERVER['REMOTE_ADDR'];
		$ord['date_added'] = date('Y-m-d H:i:s');
		$ord['date_modified'] = date('Y-m-d H:i:s');
		$or = $this->db->insert('order_details', $ord);
		$_SESSION['order_id'] = $this->db->insert_id();
		return $_SESSION['order_id'];
	}

	function complete_online($posted = '')
	{ //payment gateway
		$ctotal = 0;
		$i = 0;
		$products = "";
		$order_id = $posted['udf1'];
		$pinfo = json_decode($posted['productinfo'], TRUE);
		foreach ($pinfo['products'] as $c) {
			$ctotal += round($c['sp'] * $c['qty'], 2);
			$i += $c['qty'];

			$ordp['order_id'] = $order_id;
			$ordp['p_id'] = $c['p_id'];
			$ordp['name'] = urldecode($c['name']);
			$ordp['quantity'] = $c['qty'];
			$products .= trim(urldecode($c['name']), "&#8230;") . " Qty " . $c['qty'] . " "; //used for sms
			$ordp['price'] = $c['sp'];
			$ordp['total'] = $c['total'];
			$ordp['tax'] = 0;
			$ordp['reward'] = 0;
			$ordp['options'] = @$c['option'];
			$or = $this->db->insert('order_product', $ordp);

			//update stock
			$iq = $this->db->where('p_id', $c['p_id'])->get('product_info')->result();
			$info['qty'] = ($iq[0]->qty) - ($c['qty']);
			$or = $this->db->where('p_id', $c['p_id'])->update('product_info', $info);
		}

		//search ship address
		$adwhere['user_id'] = $posted['udf2'];
		$adwhere['addr_id'] = $pinfo['head']['addr_id'];
		$adr = $this->db->select('users_address.name as fname,lname,address,city,pin,users_address.country_id,users_address.zone_id,zone_master.name as zone')->where($adwhere)->join('zone_master', 'zone_id')->get('users_address')->result();

		//insert ship address
		$ship['order_id'] = $order_id;
		$ship['fname'] = $adr[0]->fname;
		$ship['lname'] = $adr[0]->lname;
		$ship['address_1'] = $adr[0]->address;
		$ship['address_2'] = '';
		$ship['city'] = $adr[0]->city;
		$ship['postcode'] = $adr[0]->pin;
		$ship['country_id'] = $adr[0]->country_id;
		$ship['zone_id'] = $adr[0]->zone_id;
		$ship['zone'] = $adr[0]->zone;
		$ship['shipping_code'] = '';
		$this->db->insert('order_shipping', $ship);

		$wh['session_id'] = $pinfo['head']['session_id'];
		$this->db->where($wh)->delete('cart');

		//sms receipt
		/*
         $text="Hi ".$adr[0]->fname.", Thank you for placing your Order with Order_id ($order_id) $products Shopmarg.com";
         $this->load->module('psms');
         $this->psms->sms_send($uqry[0]->phone,$text);
        */
		//email receipt


		$_SESSION['msg'] = "Order Placed Successfully. Order ID: <b>$order_id</b>";
		$this->session->mark_as_flash('msg');
		redirect('users/order_history#ordersArea');
	}

	function complete($order_id = null)
	{
		$this->db->trans_begin(); // Transaction Begins Here...

		if (!$order_id) //in online payment order id already generated
			$order_id = $this->create_order();

		$wh['session_id'] = $this->session->session_id;
		// 	    $wh['user_id']=$this->session->userdata('user_id');//commented as user can add products without login also then user_id=0...so it dnt appear in order_products but shows in cart
		$totalqry = $this->db->select('sp,cart.qty,name,p_id,s_id')->where($wh)->join('products', 'p_id')->get('cart')->result();
		$ctotal = 0;
		$i = 0;
		$products = "";
		foreach ($totalqry as $c) {
			$ctotal += round($c->sp * $c->qty, 2);
			$i += $c->qty;

			$ordp['order_id'] = $order_id;
			$ordp['p_id'] = $c->p_id;
			$ordp['s_id'] = $c->s_id;
			$ordp['name'] = $c->name;
			$ordp['quantity'] = $c->qty;
			$products .= trim(word_limiter($c->name, 3), "&#8230;") . " Qty " . $c->qty . " "; //used for sms
			$ordp['price'] = $c->sp;
			$ordp['total'] = $c->sp * $c->qty;
			$ordp['tax'] = 0;
			$ordp['reward'] = 0;
			$ordp['options'] = @json_encode($c->option);
			$or = $this->db->insert('order_product', $ordp);

			//update stock
			$iq = $this->db->where('p_id', $c->p_id)->get('product_info')->result();
			$info['qty'] = ($iq[0]->qty) - ($c->qty);
			$or = $this->db->where('p_id', $c->p_id)->update('product_info', $info);
		}

		//search ship address
		$adwhere['user_id'] = $this->session->userdata('user_id');
		$adwhere['addr_id'] = $_SESSION['addr_id'];
		$adr = $this->db->select('users_address.name as fname,lname,address,city,pin,users_address.country_id,users_address.zone_id,zone_master.name as zone')->where($adwhere)->join('zone_master', 'zone_id')->get('users_address')->result();

		//insert ship address
		$ship['order_id'] = $order_id;
		$ship['fname'] = $adr[0]->fname;
		$ship['lname'] = $adr[0]->lname;
		$ship['address_1'] = $adr[0]->address;
		$ship['address_2'] = '';
		$ship['city'] = $adr[0]->city;
		$ship['postcode'] = $adr[0]->pin;
		$ship['country_id'] = $adr[0]->country_id;
		$ship['zone_id'] = $adr[0]->zone_id;
		$ship['zone'] = $adr[0]->zone;
		$ship['shipping_code'] = '';
		$this->db->insert('order_shipping', $ship);

		$this->db->where($wh)->delete('cart');
		unset($_SESSION['order_id'],
		$_SESSION['shipping'],
		$_SESSION['zone_id'],
		$_SESSION['addr_id'],
		$_SESSION['pay_method']);
		if ($this->db->trans_status() == false) {
			$this->db->trans_rollback();
			echo "Oops Something went wrong during the Transaction.";
		} else {
			$disc = @$_SESSION['coupon_disc'] ? $_SESSION['coupon_disc'] : 0;
			$this->customer_activity->add('COD Order', array('order_id' => $order_id, 'amt' => $_SESSION['cart_total'] + $_SESSION['shipping'] - $disc));
			$this->db->trans_commit(); //success
			//sms receipt
			/*
	        $text="Hi ".$adr[0]->fname.", Thank you for placing your Order with Order_id ($order_id) $products Shopmarg.com";
	        $this->load->module('psms');
	        $this->psms->sms_send($uqry[0]->phone,$text);
	        */
			//email receipt


			$_SESSION['msg'] = "Order Placed Successfully. Order ID: <b>$order_id</b>";
			$this->session->mark_as_flash('msg');
			redirect('users/order_history#ordersArea');
		}
	}
}