<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Product extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('mdl_product');
	}
	function view()
	{
		$where['status'] = 1;
		if (isset($_GET['id']))
			$where['p_id'] = $_GET['id'];
		if (isset($_GET['s_id']))
			$where['s_id'] = $_GET['s_id'];
		if (isset($_GET['c_id'])) {
			$where['cat_id'] = $_GET['c_id'];
			$this->db->join('product_cat', 'p_id');
		}


		if (isset($_GET['data']))
			$select = $_GET['data'];
		else $select = "*";

		$return = $this->mdl_product->view_data($where, $select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
}
