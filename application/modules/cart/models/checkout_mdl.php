<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Checkout_mdl extends CI_Model
{
	function check_checkout($order_no)
	{
		$return_id=Modules::run("return_id");
		$this->db->trans_begin();
		
		//Billing Details Insert	
		$firstname=$_POST['firstname'];
		$email=$_POST['email'];
		$address1=$_POST['address1'];
		$postcode=$_POST['postcode'];
		$lastname=$_POST['lastname'];
		$telephone=$_POST['telephone'];
		$address2=$_POST['address2'];
		$country=$_POST['country'];
		$state=$_POST['state'];
		$city=$_POST['city'];
		$other_city=$_POST['other'];
		
		if ($this->session->userdata('username'))
		{
			$username=$this->session->userdata('username');
		}
		else
		{
			$username="guest";
		}
		$data=array(
				'order_no'=>$order_no,
				'product_return_id'=>$return_id,
				'firstname'=>$firstname,
				'username'=>$username,
				'email'=>$email,
				'address1'=>$address1,
				'postcode'=>$postcode,
				'lastname'=>$lastname,
				'telephone'=>$telephone,
				'address2'=>$address2,
				'country'=>$country,
				'city'=>$city,
				'other_city_name'=>$other_city,
				'state'=>$state
				);
				
			$this->db->insert('billingdetails',$data);
			
			//Delevery Details
			$firstname=$_POST['dfirstname'];
			$email=$_POST['demail'];
			$address1=$_POST['daddress1'];
			$postcode=$_POST['dpostcode'];
			$lastname=$_POST['dlastname'];
			$telephone=$_POST['dtelephone'];
			$address2=$_POST['daddress2'];
			$city=$_POST['dcity'];
			$other_city2=$_POST['dother'];
			$country=$_POST['dcountry'];
			$state=$_POST['dstate'];

			$data=array(
			'order_no'=>$order_no,
			'product_return_id'=>$return_id,
			'firstname'=>$firstname,
			'email'=>$email,
			'address1'=>$address1,
			'postcode'=>$postcode,
			'lastname'=>$lastname,
			'telephone'=>$telephone,
			'address2'=>$address2,
			'city'=>$city,
			'other_city_name'=>$other_city2,
			'country'=>$country,
			'state'=>$state
				);
			
			$delevery=$this->db->insert('deliverydetails',$data);
		
			//Inserting to cart//Order_details
			$comment=$_POST['comment'];
			$cart=$this->cart->contents();
		 	foreach ($cart as $items)
		 	{
		 		if ($this->session->userdata('username'))
		 				{
		 					$username=$this->session->userdata('username');
		 				}
		 				else
		 				{
		 					$username="guest";
		 				}
			 	$data=array(
			 			"order_no"=>$order_no,
			 			"product_return_id"=>$return_id,
			 			'firstname'=>$_POST['firstname'],
			 			'username'=>$username,
			 			"product_id"=>$items['id'],
			 			"quantity"=>$items['qty'],
			 			"product_name"=>$items['name'],
			 			"unit_price"=>$items['price'],
			 			"color"=>$items['options']['colour'],
			 			"colorcode"=>$items['options']['colorcode'],
			 			"size"=>$items['options']['size'],
			 			"order_date"=>date('d/m/Y'),
			 			"comment"=>$comment
			 			);
			 	
			 	$pid=$items['id'];
			 	$cart_qty=$items['qty'];
			 		
			 	$this->db->where('product_id',$pid);
			 	foreach ($this->db->get('stock')->result() as $row)
			 	{
			 		$stock_qty=$row->quantity;
			 		$new_qty=$stock_qty-$cart_qty;
			 		
			 		$data2=array(
			 				'quantity'=>$new_qty
			 				);
			 		
			 		$this->db->where('product_id',$pid);
			 		$this->db->update('stock',$data2);
			 		
			 		$order=$this->db->insert('order_details',$data);
			 	}
		 	}
		 	
		 	if ($this->db->trans_status() === FALSE)
		 	{
		 		$this->db->trans_rollback();
		 		return false;
		 	}
		 	else
		 	{	
		 		$this->db->trans_commit();
				$this->email_to_customer($order_no);
				$this->cart->destroy();
				return true;
		 	}			
	}
	
	function email_to_customer($order_no)
	{
		//Send Email
		$this->load->library('email');
		$this->email->set_mailtype("html");
		
		$a=$_POST['firstname'];
		$c=$_POST['lastname'];
		$b=$_POST['email'];
		 
		$message=$this->load->view('cart/view_order');
		
		$company_mail="eladela@support.com";
		//clients mail
		$this->email->to($b);
		$this->email->from($company_mail);
		$this->email->subject('Order Details');
		$this->email->message($message);
		$this->email->send();
			
		//Sinet mail
		$this->email->to($company_mail);
		$this->email->from($b);
		$this->email->subject('Order Message');
		$this->email->message($message);
		$this->email->send();
		
		//END Send Email
		$message=Modules::run("cart/success?order=$order_no");
		echo $message;
		
	}
	
}