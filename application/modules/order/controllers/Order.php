<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Order extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('mdl_order');
	}
	function index()
	{
		echo "Welcome<br>Order Api DeliMarg!";
	}
	function view()
	{
		// $where = null;
		// if (@$_GET['id']) {
		$where['user_id'] = $this->session->user_id;
		$this->db->join('order_status', 'order_status_id');
		// }
		if (@$_GET['o_id']) {
			$where['order_id'] = $_GET['o_id'];
			$this->db->join('order_product', 'order_id');
		}
		if (@$_GET['data'])
			$select = $_GET['data'];
		else
			$select = "*";
		$return = $this->mdl_order->view_data($where, $select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
}
