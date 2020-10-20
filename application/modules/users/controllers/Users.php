<?php class Users extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->module('customer_activity');
        // if (!$this->session->userdata('user_id')) {
        //     redirect('home');
        // }
        $this->load->model('register_mdl');
    }
    function index()
    {
        $data['title'] = 'Dashboard';
        $data['module'] = 'users';
        $data['view_file'] = 'dashboard';
        echo Modules::run('template/layout2', $data);
    }

    function view()
    {
        $where['user_id'] = $this->session->user_id;
        if (@$_GET['data'])
            $select = $_GET['data'];
        else
            $select = '*';
        $return = $this->register_mdl->view_data($where, $select);
        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }

    function changepassword()
    {
        $data['title'] = 'Change Password';
        $data['module'] = 'users';
        $data['view_file'] = 'changepassword';
        echo Modules::run('template/layout2', $data);
    }

    function wishlist()
    {
        $data['title'] = 'Wishlist';
        $data['module'] = 'users';
        $data['view_file'] = 'wishlist';
        echo Modules::run('template/layout2', $data);
    }
    function viewAddress()
    {
        $where['user_id'] = $this->session->user_id;
        $this->db->join('oc_country', 'country_id');
        $this->db->join('zone_master', 'zone_id');
        $select = "oc_country.name as cname,zone_master.name as zname,users_address.addr_id,users_address.name,users_address.lname,users_address.address,users_address.city,users_address.pin";

        $return = $this->register_mdl->view_address($where, $select);
        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
    function viewCountry()
    {
        $select = '*';
        $return = $this->register_mdl->view_country($select);

        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }

    function viewZone()
    {
        // $this->db->join('oc_country','country_id');
        // $select='oc_country.name as cname,oc_country.iso_code_2 as c_code,zone_master.name as zname,zone_master.code ';
        $select = '*';
        $return = $this->register_mdl->view_zone($select);
        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
    function shipping_address()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        // print_r($_POST);die();
        if ($this->input->get('addr_id')) {
            $wh['user_id'] = $this->session->userdata('user_id');
            $wh['addr_id'] = $_GET['addr_id'];

            if ($this->input->get('del')) {
                // echo $this->input->get('del');die();
                $this->db->where($wh)->delete('users_address');
                // $_SESSION['msg'] = "Address Deleted Succesfully";
                // $this->session->mark_as_flash('msg');
                echo "1";
            } else {
                $pp = $this->db->where($wh)->get('users_address')->result();
                $r = json_decode(json_encode($pp[0]), true); //converting the stdClass Objects to array
                $_POST = $r;
            }
        }
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
            // print_r($_POST);die();
            if (@$_POST['addr_id'] && $_POST['addr_id']) {
                // $this->customer_activity->add('Address Updated', array('addr_id' => $this->input->post('addr_id')));
                $where['user_id'] = $this->session->userdata('user_id');
                $where['addr_id'] = $_POST['addr_id'];
                $chk = $this->db->where($where)->update('users_address', $data);
            } else {
                // $this->customer_activity->add('Address Added', array('addr_id' => $this->input->post('addr_id')));
                $chk = $this->db->insert('users_address', $data);
            }
            if ($chk == true) {
                // $_SESSION['msg'] = "Address Saved Succesfully";
                // $this->session->mark_as_flash('msg');
                echo "1";
            } else {
                // $_SESSION['msg'] = "Some problem with data";
                // $this->session->mark_as_flash('msg');
                echo "0";
            }
        } else {
            echo validation_errors();
        }
    }
    function support()
    {
        $data['title'] = 'Support Tickets';
        $data['module'] = 'users';
        $data['view_file'] = 'support';
        echo Modules::run('template/layout2', $data);
    }
    function check_password()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('currentpwd', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('newpwd', 'new password', 'required|trim');
        $this->form_validation->set_rules('confirmpwd', 'Confirm Password', 'required|trim|matches[newpwd]');
        if ($this->form_validation->run() == TRUE) {
            $this->load->model('register_mdl');
            $chk = $this->register_mdl->reset_password();
            if ($chk == true) {
                $this->customer_activity->add('Password Changed', array('name' => $_SESSION['name']));
                $_SESSION['msg'] = "Password changed succesfully";
                $this->session->mark_as_flash('msg');
                redirect("users/changepassword");
            } else {
                $_SESSION['msg'] = "Some problem with data";
                $this->session->mark_as_flash('msg');
                redirect("users/changepassword");
            }
        } else {
            $this->changepassword();
        }
    }
    function profile()
    {
        // $_FILES = json_decode(file_get_contents('php://input'), true);
        // // print_r($_FILES);
        // // die();
        if (!$this->input->post()) {
            $pp = $this->db->where('user_id', $this->session->userdata('user_id'))->get('users')->result();
            $r = json_decode(json_encode($pp[0]), true); //converting the stdClass Objects to array
            $_POST = $r;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim');
        $this->form_validation->set_rules('gender', 'Gender', 'required|trim');
        $this->form_validation->set_rules('dob', 'Date of birth', 'trim');
        if ($this->form_validation->run() == TRUE) {
            $data['name'] = $_POST['name'];
            $data['phone'] = $_POST['phone'];
            $data['email'] = $_POST['email'];
            $data['gender'] = $_POST['gender'];
            $data['dob'] = $_POST['dob'];
            if (!empty($_FILES['image']['name'])) {
                $data['img'] = $this->image_upload($data['name']);
                if ($_POST['old_image']) {
                    $this->remove_image($_POST['old_image']);
                }
            }

            $chk = $this->db->where('user_id', $this->session->userdata('user_id'))->update('users', $data);
            if ($chk == true) {
                // $this->customer_activity->add('Profile updated', array('name' => $_SESSION['name']));
                if (!empty($_FILES['image']['name']))
                    $_SESSION['img'] = $data['img'];
                echo "1";
            } else {
                echo "0";
            }
        } else {
            echo validation_errors();
        }
    }
    function image_upload($title)
    {

        // upload coder starts here
        $config['upload_path'] = './assets/temp';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['new_image'] = './assets/uploads/users/';
        $config['min_width'] = 100;

        $rand_number = mt_rand(0, 9999);
        $timestamp = time();
        $title = str_replace(" ", "_", $title);
        $config['file_name'] = $title . "_" . $rand_number;

        $config['overwrite'] = false;
        $config['remove_spaces'] = true;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('image')) {
            echo $this->upload->display_errors();
            die();
        } else {
            $image = $this->upload->data();
            // image manipulation resizing 1
            $config['source_image'] = './assets/temp/' . $image['file_name'];
            $config['maintain_ratio'] = TRUE;
            if ($image['image_width'] > 720)
                $config['width'] = 720;
            $this->load->library('image_lib', $config);
            $this->image_lib->initialize($config);

            if (!$this->image_lib->resize()) {
                echo $this->image_lib->display_errors();
                die();
            }

            $this->image_lib->clear();
            // image manipulation resizing 2
            $config['source_image'] = './assets/temp/' . $image['file_name'];
            $config['new_image'] = './assets/uploads/users/thumb/';
            $config['file_name'] = $title . "_" . $rand_number;
            $config['maintain_ratio'] = TRUE;
            if ($image['image_width'] > 100) {
                $config['width'] = 100;
            }
            $config['overwrite'] = FALSE;
            $this->load->library('image_lib', $config);
            $this->image_lib->initialize($config);
            if (!$this->image_lib->resize()) {
                echo $this->image_lib->display_errors();
                die();
            } else {
                unlink($config['source_image']);
                return $image['file_name'];
            }
        }
    }
    function remove_image($name)
    {
        $path1 = "./assets/uploads/users/" . $name;
        unlink($path1);
        $path2 = "./assets/uploads/users/thumb/" . $name;
        unlink($path2);
    }
    function order_history()
    {
        if (@!$_SESSION['cart_total'] || $_SESSION['cart_total'] == 0) {
            unset($_SESSION['cart_total'],
            $_SESSION['shipping'],
            $_SESSION['zone_id'],
            $_SESSION['addr_id'],
            $_SESSION['order_id'],
            $_SESSION['pay_method']);
        }
        $data['title'] = 'Order History';
        $data['module'] = 'users';
        $data['view_file'] = 'order_history';
        echo Modules::run('template/layout2', $data);
    }

    function order_details($order_no)
    {
        $data['order_no'] = $order_no;
        $data['title'] = 'Order Details';
        $data['module'] = 'users';
        $data['view_file'] = 'view_order_details';
        echo Modules::run('template/layout2', $data);
    }

    function cancel_order()
    {
        $order_no = $_POST['orderno'];
        $data = array(
            'status' => 'Cancelled',
            "order_date" => date('d/m/Y')
        );
        $this->db->where('order_no', $order_no);
        if ($this->db->update('order_details', $data) == true) {
            $msg = "Order ($order_no) is Successfully Cancelled";
            redirect("users/order_history?cancelledmsg=$msg");
        } else {
            $msg = "Order Could Not Cancelled";
            redirect("users/order_history?cancelledmsg=$msg");
        }
    }

























    //     function myaccount()
    // 	{
    // 		$data['title']='User My Account';
    // 		$data['module']='users';
    // 		$data['view_file']='myaccounts';
    // 		echo Modules::run('template/layout2',$data);
    // 	}

    // 	function success()
    // 	{

    // 		$data['title']='User My Account Success ';
    // 		$data['module']='users';
    // 		$data['view_file']='success';
    // 		echo Modules::run('template/layout2',$data);
    // 	}

    // 	function update()
    // 	{

    // 		$data['title']='User My Account Success Update ';
    // 		$data['module']='users';
    // 		$data['view_file']='update';
    // 		echo Modules::run('template/layout2',$data);
    // 	}

    // 	function list_generate()
    // 	{
    // 		$state=$_POST['state'];

    // 		$this->db->select('city');
    // 		$this->db->where('state',$state);
    // 		$qry=$this->db->get('india');
    // 		echo "<select class='form-control' name='city'>";
    // 		echo "<option></option>";
    // 		foreach ($qry->result() as $row)
    // 		{
    // 			echo "<option value='$row->city_name'>$row->city</option>";

    // 		}
    // 		echo "<option>Other</option>";
    // 		echo "</select>";
    // 	}

    // 	function register()
    // 	{
    // 		$data['title']='User Register';
    // 		$data['module']='users';
    // 		$data['view_file']='register';
    // 		echo Modules::run('template/layout2',$data);
    // 	}

    // 	function editaccount()
    // 	{
    // 		$data['title']='Accounts | Eladela Largests online Shpooing ';
    // 		$data['module']='users';
    // 		$data['view_file']='editaccount';
    // 		echo Modules::run('template/layout2',$data);
    // 	}

    // 	function check_register()
    // 	{
    // 		$this->load->library('form_validation');

    // 		$this->form_validation->set_rules('firstname', 'First Name', 'required|alpha|trim');
    // 		$this->form_validation->set_rules('lname', 'Last Name', 'required|alpha|trim');
    // 		$this->form_validation->set_rules('email', 'Email', 'required|trim|is_unique[user.email]');
    // 		$this->form_validation->set_rules('telephone', 'Telephone', 'required|trim');
    // 		$this->form_validation->set_rules('address1', 'Address1', 'required|trim');
    // 		$this->form_validation->set_rules('address2', 'address2', 'trim');
    // 		$this->form_validation->set_rules('postcode', 'Post Code', 'required|trim');
    // 		$this->form_validation->set_rules('city', 'City', 'required|trim');
    // 		$this->form_validation->set_rules('state', 'State', 'required|trim');
    // 		$this->form_validation->set_rules('country', 'Country', 'required|trim');
    // 		$this->form_validation->set_rules('username', 'username', 'required|trim|is_unique[user.username]');
    // 		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]');
    // 		$this->form_validation->set_rules('passwordconfirm', 'Password Confirm', 'required|trim|matches[password]');
    // 		$this->form_validation->set_rules('privacypolicy', 'Privacy Policy', 'required|trim');

    // 		if ($_POST['city']=="Other")
    // 		{
    // 		$this->form_validation->set_rules('other', 'Other', 'required|trim');
    // 		}
    // 		else
    // 		 {
    // 		 	$this->form_validation->set_rules('other', 'Other', 'trim');
    // 		}

    // 		if ($this->form_validation->run() == TRUE)
    // 		{
    // 			$this->load->model('register_mdl');
    // 			$chk=$this->register_mdl->check_register();
    // 			if($chk==true)

    // 			{

    // 			redirect('users/success');

    // 			}

    // 			else
    // 			{
    // 				echo 'Sorry data is not insert';

    // 			}

    // 		}
    // 		else
    // 		{
    // 			$this->register();

    // 		}
    // 	}

    // 	function login()
    // 	{
    // 		$data['title']='User Login';
    // 		$data['module']='users';
    // 		$data['view_file']='login';
    // 		echo Modules::run('template/layout2',$data);
    // 	}

    // 	function update_account()
    // 	{
    // 		$this->load->library('form_validation');

    // 	    $this->form_validation->set_rules('firstname', 'First Name', 'required|alpha|trim');
    // 		$this->form_validation->set_rules('lname', 'Last Name', 'required|alpha|trim');
    // 		$this->form_validation->set_rules('email', 'Email', 'required|trim');
    // 		$this->form_validation->set_rules('telephone', 'Telephone', 'required|trim');
    // 		$this->form_validation->set_rules('address1', 'Address1', 'required|trim');
    // 		$this->form_validation->set_rules('address2', 'address2', 'required|trim');
    // 		$this->form_validation->set_rules('postcode', 'Post Code', 'required|trim');
    // 		$this->form_validation->set_rules('city', 'City', 'required|trim');
    // 		$this->form_validation->set_rules('state', 'State', 'required|trim');
    // 		$this->form_validation->set_rules('country', 'Country', 'required|trim');


    // 		if ($this->form_validation->run() == TRUE)
    // 		{
    // 			$this->load->model('register_mdl');
    // 			$chk=$this->register_mdl->update_account();
    // 			if($chk==true)
    // 			{
    // 				$msg="Account updated succesfully";
    // 				redirect("users/myaccount?msg=$msg");
    // 			}
    // 			else
    // 			{
    // 				echo 'Sorry data is not insert';
    // 			}

    // 		}
    // 		else
    // 		{
    // 			$this->editaccount();
    // 		}
    // 	}

    // 	function checklogin()
    // 	{
    // 		$this->load->library('form_validation');

    // 		$this->form_validation->set_rules('email', 'E-Mail', 'required|trim');
    // 		$this->form_validation->set_rules('password', 'Password', 'required|trim');

    // 		if ($this->form_validation->run() == TRUE)
    // 		{
    // 			$this->load->model('register_mdl');
    // 			$chk=$this->register_mdl->checklogin();
    // 			if($chk==true)
    // 			{
    // 				echo "<font color='green'><b>Update Successfully.
    // 				<br>Thank You</b></font>";
    // 			}
    // 			else
    // 			{
    // 				echo 'Sorry data is not insert';
    // 			}

    // 		}
    // 		else
    // 		{
    // 			$this->login();
    // 	    }
    // 	}


    function return_form($order_no)
    {
        $this->db->where('order_no', $order_no);
        $query = $this->db->get('order_details');
        $count = $query->num_rows();
        if ($count > 0) {
            $data['order_no'] = $order_no;
            $data['title'] = 'Return Form | Largest Online Shoe Mega Store ';
            $data['module'] = 'users';
            $data['view_file'] = 'return_form';
            echo Modules::run('template/layout2', $data);
        } else {
            redirect("users/order_history");
        }
    }
    function process_return()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('method', 'Process', 'required|alpha|trim');
        $this->form_validation->set_rules('reason', 'Reason', 'required|trim');
        if ($_POST['reason'] == "Other") {
            $this->form_validation->set_rules('otherreason', 'Other', 'required|trim');
        } else {
            $this->form_validation->set_rules('otherreason', 'Other', 'trim');
        }

        if ($this->form_validation->run() == TRUE) {
            $this->load->model('register_mdl');
            $chk = $this->register_mdl->process_return();
            if ($chk == true) {
                redirect("users/return_success");
            } else {
                echo 'Sorry data is not insert';
            }
        } else {
            $order_no = $_POST['id'];
            $this->db->where('order_no', $order_no);
            $query = $this->db->get('order_details');
            $count = $query->num_rows();
            if ($count > 0) {
                $data['order_no'] = $order_no;
                $data['title'] = 'Return Form | Largest Online Shoe Mega Store ';
                $data['module'] = 'users';
                $data['view_file'] = 'return_form';
                echo Modules::run('template/layout2', $data);
            } else {
                redirect("users/order_history");
            }
        }
    }

    function return_success()
    {
        $data['title'] = 'Return Success | Largest Online Shoe Mega Store | Ladela Online Shopping ';
        $data['module'] = 'users';
        $data['view_file'] = 'return_success';
        echo Modules::run('template/layout2', $data);
    }

    function return_pdf_form()
    {
        $data['title'] = 'Return pdf form| Largest Online Shoe Mega Store | Ladela Online Shopping ';
        $data['module'] = 'users';
        $data['view_file'] = 'return_pdf_form';
        echo Modules::run('template/layout2', $data);
    }

    function fetch_billing()
    {
        $this->db->where('username', $this->session->userdata('username'));
        $qry = $this->db->get('users');
        $num = $qry->num_rows();

        echo "[";
        $i = 0;
        foreach ($qry->result() as $row) {
            //echo "<pre>";
            //print_r($row);
            echo $data = json_encode($row);

            //print_r($data);
            $i++;

            if ($i == $num)
                echo "";
            else
                echo ",";
        }
        echo "]";
    }
}