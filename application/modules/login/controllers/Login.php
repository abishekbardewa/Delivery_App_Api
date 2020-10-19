<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Login extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('mdl_login');
		$this->load->module('customer_activity');
	}

	function sessionData()
	{
		$this->output->set_content_type('application/json')->set_output(json_encode($this->session->userdata));
	}
	function userLogin()
	{
		$_POST = json_decode(file_get_contents('php://input'), true);
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email', 'required|trim');
		$this->form_validation->set_rules('pass', 'Password', 'required|trim');

		if ($this->form_validation->run() == TRUE) {

			if ($this->mdl_login->validate() == true) {
				echo '1';
			} else {
				echo '0';
			}
		} else {
			echo validation_errors();
		}
	}
	function register()
	{
		$_POST = json_decode(file_get_contents('php://input'), true);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'First Name', 'required|trim|alpha');
		$this->form_validation->set_rules('lname', 'Last Name', 'required|trim|alpha');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('phone', 'Phone', 'required|trim|is_unique[users.phone]');
		$this->form_validation->set_rules('pass', 'Password', 'required|trim|min_length[8]|max_length[30]');
		$this->form_validation->set_rules('cPass', 'Confirm Password', 'required|trim|matches[pass]');
		if ($this->form_validation->run() == TRUE) {
			$insert['name'] = ucfirst($_POST['name']);
			$insert['lname'] = $_POST['lname'];
			$insert['email'] = $_POST['email'];
			$insert['phone'] = $_POST['phone'];
			$insert['salt'] = $salt = substr(md5(uniqid(rand(), true)), 0, 9);

			$insert['pass'] = sha1($salt . sha1($salt . sha1($_POST['pass']))); //md5($_POST['pass']);
			$this->mdl_login->register($insert);
			// 	        print_r($insert);die();
			// $name = explode(" ", $insert['name']);
			// $user_id = $this->mdl_login->register($insert);
			// $this->customer_activity->add('User Registered', array('user_id' => $user_id, 'name' => $insert['name']));
			// $this->registeration_email($name[0], $insert['email'], $user_id, $insert['phone']);
			echo 1;
		} else
			echo validation_errors();
	}

	function forgot_password()
	{
		$data['title'] = "Forgot Password " . $this->data['company'];
		$data['module'] = 'login';
		$data['view_file'] = 'forgot_password';
		echo Modules::run('template/layout2', $data);
	}
	function pwd_by_email()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		if ($this->form_validation->run() == TRUE) {
			$e = $this->db->where('email', $_POST['email'])->get('users');
			if ($e->num_rows() > 0) {
				$em = $e->result();
				$this->load->library('email', $this->lib_config);
				$this->email->set_newline("\r\n");

				$this->email->to($_POST['email']);
				$this->email->from($this->data['email']);
				$this->email->subject("Email Confirmation of " . $this->data['company']);
				$template = file_get_contents(site_url("email/forgotPassword/" . urlencode($em[0]->name) . "/" . urlencode($_POST['email']) . "/" . $em[0]->user_id . "/" . urlencode($em[0]->phone)));
				$this->email->message($template);
				//         	    echo $template;
				$res = $this->email->send();
				$_SESSION['msg'] = "<div class='alert alert-success alert-dismissible' role='alert'>
                            <button type='button' class='close' data-dismiss='alert'>
                            <span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
	                       Please check your email (" . $_POST['email'] . ") to get the password reset link</div>";
			} else {
				$_SESSION['msg'] = "<div class='alert alert-warning alert-dismissible' role='alert'>
                            <button type='button' class='close' data-dismiss='alert'>
                            <span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
	                       Sorry, <b>" . $_POST['email'] . "</b> doesn't exists in shopmarg.</div>";
			}
		} else {
			$_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible' role='alert'>
                            <button type='button' class='close' data-dismiss='alert'>
                            <span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>" . validation_errors() . "</div>";
		}
		$this->session->mark_as_flash("msg");
		redirect("login/forgot_password");
	}

	function pwd_by_phone()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('phone', 'Phone', 'required|trim|numeric|exact_length[10]');
		if ($this->form_validation->run() == TRUE) {
			$e = $this->db->where('phone', $_POST['phone'])->get('users');
			if ($e->num_rows() > 0) {
				$em = $e->result();
				$_SESSION['phone'] = $em[0]->phone;
				$_SESSION['ng-otp'] = rand(1000, 9999);
				//sms receipt
				$text = "Hi " . $em[0]->name . ", please enter OTP: " . $_SESSION['ng-otp'] . " for resetting your account password in Shopmarg";
				$this->load->module('psms');
				$this->psms->sms_send($_SESSION['phone'], $text);

				redirect("login/forgot_password?type=phone&otp=1");
			} else {
				$_SESSION['msg'] = "<div class='alert alert-warning alert-dismissible' role='alert'>
                            <button type='button' class='close' data-dismiss='alert'>
                            <span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
	                       Sorry, <b>" . $_POST['phone'] . "</b> doesn't exists.</div>";
			}
		} else {
			$_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible' role='alert'>
                            <button type='button' class='close' data-dismiss='alert'>
                            <span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>" . validation_errors() . "</div>";
		}
		$this->session->mark_as_flash("msg");
		redirect("login/forgot_password?type=phone");
	}
	function verify_phn_otp()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('otp', 'OTP', 'required|trim|numeric');
		if ($this->form_validation->run() == TRUE) {
			if (@$_SESSION['phone']) {
				if (@$_SESSION['ng-otp'] == $_POST['otp']) {
					$e = $this->db->where('phone', $_SESSION['phone'])->get('users')->result();
					$email = urldecode($e[0]->email);
					$phone = md5($this->data['company'] . $_SESSION['phone']);
					redirect("login/password_reset?req_id=" . $e[0]->user_id . "&e=$email&key=$phone");
				} else {
					redirect("login/forgot_password?type=phone&otp=1&err=1");
				}
			} else redirect("login/forgot_password?type=phone");
		} else {
			$_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible' role='alert'>
                            <button type='button' class='close' data-dismiss='alert'>
                            <span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>" . validation_errors() . "</div>";
		}
		$this->session->mark_as_flash("msg");
		redirect("login/forgot_password?type=phone&otp=1");
	}

	function registeration_email($name, $email, $id, $phone)
	{
		$this->load->library('email', $this->lib_config);
		$this->email->set_newline("\r\n");

		$this->email->to($email);
		$this->email->from($this->data['email']);
		$this->email->subject("Email Confirmation of " . $this->data['company']);
		$template = file_get_contents(site_url("email/confirm_email/" . urlencode($name) . "/" . urlencode($email) . "/$id/" . urlencode($phone)));
		$this->email->message($template);
		$this->email->send();
		return true;
	}

	function confirm_email()
	{
		if ($this->input->get('req_id') && $this->input->get('key') && $this->input->get('e')) {
			$r = $this->db->where('user_id', $this->input->get('req_id'))->where('email', $this->input->get('e'))->get('users');
			if ($r->num_rows() > 0) {
				$t = $r->result();
				$phn = md5($this->data['company'] . $t[0]->phone);
				if ($phn == $this->input->get('key')) {
					$upd['email'] = 1;
					$this->db->where('user_id', $this->input->get('req_id'))->update('user_verify', $upd);
					$msg = "Congrats ! Verification Successful.";
				} else
					$msg = "Verification Failed. Suspicious Link #909212";
			} else {
				$msg = "Verification Failed. Suspicious Link #958302";
			}
		} else $msg = "Suspicious Link #987202";
		$_SESSION['fmsg'] = $msg;
		$this->session->mark_as_flash('fmsg');
		$this->info();
	}

	function password_reset()
	{
		if (@!$_SESSION['temail']) {
			if ($this->input->get('req_id') && $this->input->get('key') && $this->input->get('e')) {
				$em = urldecode($this->input->get('e'));
				$r = $this->db->where('user_id', $this->input->get('req_id'))->where('email', $em)->get('users');
				if ($r->num_rows() > 0) {
					$t = $r->result();
					$phn = md5($this->data['company'] . $t[0]->phone);
					if ($phn == $this->input->get('key')) {
						$_SESSION['temail'] = $em;
						$_SESSION['tuser_id'] = $this->input->get('req_id');
						$_SESSION['pwd_reset'] = 1;

						$msg = "Choose a New Password";
					} else
						$msg = "Verification Failed. Suspicious Link #909212";
				} else {
					$msg = "Verification Failed. Suspicious Link #958302";
				}
			} else $msg = "Suspicious Link #987202";

			$_SESSION['msg'] = $msg;
			$this->session->mark_as_flash('msg');
		}

		$data['title'] = "Password Reset " . $this->data['company'];
		$data['module'] = 'login';
		$data['view_file'] = 'password_reset_form';
		echo Modules::run('template/layout2', $data);
	}

	function change_password()
	{
		$_POST = json_decode(file_get_contents('php://input'), true);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('pass', 'New password', 'required|trim|min_length[8]|max_length[30]');
		$this->form_validation->set_rules('cPass', 'Confirm Password', 'required|trim|matches[pass]');
		if ($this->form_validation->run() == TRUE) {
			$chk = $this->mdl_login->reset_password();
			if ($chk == true) {
				// $this->customer_activity->add('Password Reset', array('email' => $_SESSION['email']));
				// $this->load->library('email', $this->lib_config);
				// $this->email->set_newline("\r\n");

				// $this->email->to($_SESSION['email']);
				// $this->email->from($this->data['email']);
				// $this->email->subject("Email Confirmation of " . $this->data['company']);
				// $template = file_get_contents(site_url("email/pass_changed_success"));
				// $this->email->message($template);
				// $this->email->send();
				echo "1";
			} else {
				echo "0";
			}
		} else {
			echo validation_errors();
		}
	}

	function info()
	{
		$data['title'] = $this->data['company'];
		$data['module'] = 'login';
		$data['view_file'] = 'info';
		echo Modules::run('template/layout2', $data);
	}

	function check()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		$this->form_validation->set_rules('pass', 'Password', 'trim|required|min_length[6]');
		if ($this->form_validation->run() == true) {
			$this->load->model('mdl_login');
			if ($this->mdl_login->validate() == true) {
				$this->customer_activity->add('User Logged In', array('name' => $_SESSION['name']));
				echo 1;
			} else
				echo "<div class='alert alert-danger'>Unauthorise User !!! </div>";
		} else
			echo "<div class='alert alert-danger'>" . validation_errors() . "</div>";
	}

	function logout()
	{
		// $this->customer_activity->add('User Logged Out', array('name' => $_SESSION['name']));
		$this->session->set_userdata('');
		$this->session->sess_destroy();
		echo "1";
	}
}