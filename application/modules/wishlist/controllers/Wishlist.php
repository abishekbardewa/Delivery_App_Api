<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Wishlist extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->module('customer_activity');
	}
	function index()
	{
		$data['title'] = 'Wish List';
		$data['module'] = 'wishlist';
		$data['view_file'] = 'wishlist';
		echo Modules::run('template/layout2', $data);
	}
	function view()
	{
		if (@$_SESSION['user_id']) {
			$where = $this->session->user_id;
			$this->db->join('products', 'p_id');
			$return = $this->db->select("p_id,name,img,sp")
				->where('user_id', $where)
				->order_by('id', "DESC")
				->get('wishlist');

			$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
		}
	}

	function count()
	{
		if (@$_SESSION['user_id']) {
			$return = $this->db->where('user_id', @$_SESSION['user_id'])->get('wishlist');
			$this->output->set_content_type('application/json')->set_output(json_encode($return->num_rows()));
		} else {
			echo "0";
		}
	}
	function add($pid)
	{
		if (@$_SESSION['user_id']) {
			if ($pid) {
				$data = array(
					'p_id'      => $pid,
					'user_id'     => $_SESSION['user_id']
				);
				if ($this->db->where($data)->get('wishlist')->num_rows() > 0) {
					// echo "Item <b>Already added</b> to wishlist";
					echo "3";
					die();
				}
				// $this->customer_activity->add('Wishlist Added', array('pid' => $pid));
				$this->db->insert('wishlist', $data);
				echo "1";
			} else {
				echo "0";
			}
		} else {
			echo "4";
			// echo 'Please <b><a href="#signin-modal" data-toggle="modal">Sign in</a></b> to add wishlists';
		}
	}
	function delete($pid)
	{
		$this->db->where('user_id', @$_SESSION['user_id'])->where('p_id', $pid);
		if ($this->db->delete('wishlist')) {
			// $this->customer_activity->add('Wishlist Removed', array('pid' => $pid));
			// $_SESSION['msg'] = "Product successfully remove from your wishlist";
			// $this->session->mark_as_flash('msg');
			echo "1";
		} else {
			echo "0";
		}
	}
}