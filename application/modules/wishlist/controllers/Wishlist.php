<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Wishlist extends MX_Controller
{
    function __construct(){
        parent::__construct();
        $this->load->module('customer_activity');
    }
	function index()
	{
		$data['title']='Wish List';
		$data['module']='wishlist';
		$data['view_file']='wishlist';
		echo Modules::run('template/layout2',$data);
	}
		
    function count(){
        if(@$_SESSION['user_id']){
            echo $this->db->where('user_id',@$_SESSION['user_id'])->get('wishlist')->num_rows();
        }else echo 0;
	}
	function add($pid){
	    if(@$_SESSION['user_id']){
    	    if($pid){
    	        $data = array(
    	            'p_id'      => $pid,
    	            'user_id'     => $_SESSION['user_id']
    	        );
        		if($this->db->where($data)->get('wishlist')->num_rows()>0){
        		    echo "Item <b>Already added</b> to wishlist";die();
        		}
        		$this->customer_activity->add('Wishlist Added',array('pid'=>$pid));
        		echo $this->db->insert('wishlist',$data);
    	    }else{
    	        echo "Invalid request";
    	    }
	    }else{
	        echo 'Please <b><a href="#signin-modal" data-toggle="modal">Sign in</a></b> to add wishlists';
	    }
	}
    function delete($pid)
    {
    	$this->db->where('user_id',@$_SESSION['user_id'])->where('p_id',$pid);
       	if($this->db->delete('wishlist')){
       	    $this->customer_activity->add('Wishlist Removed',array('pid'=>$pid));
       	    $_SESSION['msg']="Product successfully remove from your wishlist";
       	    $this->session->mark_as_flash('msg');
       		redirect("users/wishlist");
       	}else{
       	    $_SESSION['msg']="Failed to remove from your wishlist";
       	    $this->session->mark_as_flash('msg');
       	    redirect("users/wishlist");
       	}
    }  
}