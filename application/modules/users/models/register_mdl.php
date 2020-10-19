<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Register_mdl extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function view_data($where, $select)
	{
		$this->db->select($select)
			->where($where)
			->order_by('user_id', "desc");
		// 		echo $this->db->get_compiled_select('users');
		// 		die();
		return $this->db->get('users');
	}
	function userLogin()
	{
		// $sql = "SELECT * FROM users WHERE LOWER(email) = '" . $this->input->post('email') . "' AND (pass = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->input->post('pass') . "'))))) OR pass = '" . md5($this->input->post('pass')) . "') AND status = '1'";
		// $query = $this->db->query($sql);
		// //         echo $sql;die();
		// //         $this->db->get('use')
		// //         $where=array(
		// //             "email"=>$this->input->post('email'),
		// //             "pass"=>SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->input->post('pass') . "')))),
		// //             "status"=>1
		// //         );
		// //         $query=$this->db->where($where)->get('users');
		// if ($query->num_rows() > 0) {
		// 	$res = $query->result();
		// 	$this->generate_session($res[0]);
		// 	return true;
		// } else {
		// 	return false;
		// }
		$data = array(
			"email" => $this->input->post('username'),
			"pass" => md5($this->input->post('password')),
		);
		$query = $this->db->where($data)->get('users');
		if ($query->num_rows() > 0) {
			$res = $query->result();
			$ses_data = array(
				'name' => $res[0]->name,
				'lname' => $res[0]->lname,
				'user_id' => $res[0]->user_id,
				'email' => $res[0]->email,
			);
			$this->session->set_userdata($ses_data);
// 			$r=array('name'=>$res[0]->name,'email'=>$res[0]->email,)
			return true;
		} else {
			return false;
		}
	}
	function view_address($where = null, $select)
	{
		$this->db->select($select);
		if ($where)
			$this->db->where($where);
		$this->db->order_by('addr_id', 'DESC');
		// echo $this->db->get_compiled_select('users_address');
		// die();
		return $this->db->get('users_address');
	}

	function view_country($select)
	{
		$this->db->select($select);
		$this->db->order_by('country_id', 'DESC');
		return $this->db->get('oc_country');
	}

	function view_zone($select)
	{
		$this->db->select($select);
		$this->db->order_by('zone_id', 'ASCE');
		return $this->db->get('zone_master');
	}

	////////////////////////////////////EDIED Formate Class////////////////////
	function update()
	{

		$firstname = $_POST['firstname'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$telephone = $_POST['telephone'];
		$fax = $_POST['fax'];

		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$postcode = $_POST['postcode'];
		$country = $_POST['country'];
		// 		$username=$_POST['username'];
		$password = $_POST['password'];
		$id = $_POST['id'];


		$data = array(
			'name' => $lname,
			'email' => $email,
			'phone' => $telephone,
			'fax' => $fax,
			'address1' => $address1,
			'address2' => $address2,
			'city' => $city,
			'state' => $state,
			'postcode' => $postcode,
			'country' => $country,
			'user_id' => $this->session->userdata('user_id'),
			'pass' => $password,
		);

		$this->db->where('auto_id', $id);
		$pass = $this->db->update('users', $data);
		if ($pass == TRUE) {
			return true;
		} else {
			return false;
		}
	}

	function update_account()
	{

		$firstname = $_POST['firstname'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$telephone = $_POST['telephone'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$postcode = $_POST['postcode'];
		$country = $_POST['country'];
		// 	$username=$_POST['username'];

		$data = array(
			'firstname' => $firstname,
			'lname' => $lname,
			'email' => $email,
			'telephone' => $telephone,
			'address1' => $address1,
			'address2' => $address2,
			'city' => $city,
			'state' => $state,
			'postcode' => $postcode,
			'country' => $country
		);

		$this->load->module('newsletters');
		$this->newsletters->update($email);

		$this->db->where('user_id', $this->session->userdata('user_id'));
		$pass = $this->db->update('users', $data);
		if ($pass == TRUE) {
			return true;
		} else {
			return false;
		}
	}

	function reset_password()
	{
		$this->load->helper('security');

		// 		$username=$this->session->userdata('username');
		$oldpass = $_POST['currentpwd'];
		$newpass = $_POST['newpwd'];

		$oldpass = do_hash($oldpass, 'md5');
		$newpass = do_hash($newpass, 'md5');

		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('pass', $oldpass);
		$query = $this->db->get('users');
		$numrows = $query->num_rows();
		if ($numrows > 0) {
			$data = array(
				'pass' => $newpass
			);

			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->where('pass', $oldpass);

			$pass = $this->db->update('users', $data);

			if ($pass == TRUE) {
				return true;
			} else {
				return false;
			}
		} else {
			$msg = "Invalid current password";
			redirect("users/changepassword?msg=$msg");
		}
	}
	function check_register()
	{
		$this->load->helper('security');

		$firstname = $_POST['firstname'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$telephone = $_POST['telephone'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];
		$country = $_POST['country'];
		$state = $_POST['state'];
		$city = $_POST['city'];
		$other = $_POST['other'];
		$postcode = $_POST['postcode'];

		// 		$this->session->userdata('user_id')=$_POST['user_id'];
		$password = $_POST['password'];
		$password = do_hash($password, 'md5');

		$data = array(

			'firstname' => $firstname,
			'lname' => $lname,
			'email' => $email,
			'telephone' => $telephone,
			'address1' => $address1,
			'address2' => $address2,
			'city' => $city,
			'state' => $state,
			'other_city_name' => $other,
			'postcode' => $postcode,
			'country' => $country,
			'user_id' => $this->session->userdata('user_id'),
			'pass' => $password,


		);
		$pass = $this->db->insert('user', $data);
		if ($_POST['newsletter'] == "newsletter") {
			$this->load->module('newsletters');
			$this->newsletters->add($email);
		} else {
			$this->load->module('newsletters');
			$this->newsletters->add($email);
		}
		if ($pass == TRUE) {

			$link = site_url("newsletters/confirm_subscribe?e=$email");

			$this->load->library('email');
			$this->email->set_mailtype("html");
			$this->email->to($email);
			$this->email->from('eladela.com');
			$this->email->subject('Confirmation for Subscribe to eladela.com');
			$this->email->message('Hi,<br>please Click this link  to Subscribe<br>' . '<a href="' . $link . '"></a>');
			$this->email->send();
			return true;
		} else {
			return false;
		}


		////////////////////////////////////EDIED Formate Class////////////////////
	}
	function process_return()
	{

		//mail to user
		// 		$username=$this->session->userdata('username');
		$this->db->where('user_id', $this->session->userdata('user_id'));
		foreach ($this->db->get('user')->result() as $row) {
			$email = $row->email;
		}

		$this->load->library('email');
		$this->email->set_mailtype("html");
		$this->email->to($email);
		$this->email->from('support@eladela.com');
		$this->email->subject('Product Return');
		$this->email->message('');
		$this->email->send();


		$method = $_POST['method'];
		$order_id = $_POST['id'];
		$reason = $_POST['reason'];
		$other = $_POST['otherreason'];

		$data = array(
			'type' => $method,
			'order_id' => $order_id,
			'reason' => $reason,
			'other_reason' => $other,
			'return_submit_date' => date('d/m/y')
		);
		$data2 = array(
			'status' => 'Return'
		);

		$this->db->where('order_no', $order_id);
		$this->db->update('order_details', $data2);

		$pass = $this->db->insert('return_details', $data);
		if ($pass == TRUE) {
			return true;
		} else {
			return false;
		}
	}
}