<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Seller extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('mdl_seller');
	}
	function view()
	{

		$where = null;
		if (@($_GET['id']))
			$where['s_id'] = $_GET['id'];
		if (@($_GET['c_id'])) {
			$where['cat_id'] = $_GET['c_id'];
			$this->db->join('seller_cat', 's_id');
		}

		if (@($_GET['data']))
			$select = $_GET['data'];
		else $select = '*';


		$return = $this->mdl_seller->view_data($where, $select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
}
