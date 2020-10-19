<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Email extends MX_Controller
{
	private $logo;
	private $companyName;
	private $comapnyAddress;
	private $comapnyEmail;
	private $companyWebsite;
	private $phone;
	function __construct()
	{
		parent::__construct();
		$this->logo = "https://www.shopmarg.com/image/catalog/Shopmarg-Logo.png";
		$this->companyName = "Shopmarg";
		$this->companyAddress = "Siliguri,West Bengal-734001, India";
		$this->comapnyEmail = "support@shopmarg.com";
		$this->companyWebsite = "https://www.shopmarg.com/";
		$this->phone = "9851373439 / 0353-2541451";
	}

	function welcome()
	{
		$this->load->view('welcome');
	}
}
