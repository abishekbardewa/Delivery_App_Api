<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_login extends CI_Model
{
    private $table;
    function __construct()
    {
        parent::__construct();
    }

    function register($insert)
    {
        $nl['email'] = $insert['email'];
        $this->db->insert('newsletter', $nl);

        $this->db->insert('users', $insert);
        $id = $this->db->insert_id();

        $uv['user_id'] = $id;
        $this->db->insert('user_verify', $uv);
        return $id;
    }
    function reset_password()
    {
        $where = array('user_id' => $this->session->userdata('user_id'), 'email' => $this->session->userdata('email'));
        $this->db->where($where);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            $q = $query->result();
            $salt = $q[0]->salt;
            $newpass = sha1($salt . sha1($salt . sha1($_POST['pass'])));
            $this->db->where($where);
            $this->db->update('users', array('pass' => $newpass));

            //delete old session for reset pass
            unset($_SESSION['email'],
            $_SESSION['user_id'],
            $_SESSION['pwd_reset']);

            $this->generate_session($q[0]);
            return true;
        } else {
            return false;
        }
    }
    function generate_session($res)
    {
        $ses_data = array(
            'name' => $res->name,
            'lname' => $res->lname,
            'email' => $res->email,
            'user_id' => $res->user_id
        );
        $ses_data['img'] = @$res->img ? $res->img : 'default.png';

        $this->session->set_userdata($ses_data);
    }

    public function validate()
    {
        // print_r($_POST);
        // die();
        $sql = "SELECT * FROM users WHERE LOWER(email) = '" . $this->input->post('email') . "' AND (pass = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->input->post('pass') . "'))))) OR pass = '" . md5($this->input->post('pass')) . "') AND status = '1'";

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $res = $query->result();
            $this->generate_session($res[0]);
            return true;
        } else {
            return false;
        }
    }













    /*
    function change_pwd()
    {
        $where['username']=$this->session->userdata('username');
        $old_password=$_POST['currentpass'];
        $new_password=$_POST['newpass'];

        $this->load->helper('security');
        $new_pwd=do_hash($new_password,'md5');
        $old_pwd=do_hash($old_password,'md5');

        $where['password']=$old_pwd;

        $this->db->where($where);
        $query=$this->db->get('admin_profile');
        $row = $query->num_rows();
        if($row>0)
        {
            $data=array(
                "password"=>$new_pwd
            );
            $this->db->where($where);
            return $this->db->update('admin_profile',$data);
        }
        else
        {
            return false;
        }
    }
*/
}