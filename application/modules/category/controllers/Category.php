<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Category extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('mdl_category');
	}
	function view()
	{
		$where['status'] = 1;
		if (isset($_GET['id']))
			$where['cat_id'] = $_GET['id'];
		if (@$_GET['parentid']) {
			$where['parent'] = @$_GET['parentid'];
		}
		if (isset($_GET['parent']))
			$where['parent'] = '0';
		if (isset($_GET['data']))
			$select = $_GET['data'];
		else $select = "*";

		$return = $this->mdl_category->view_data($where, $select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
}
