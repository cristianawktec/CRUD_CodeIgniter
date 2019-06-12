<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* 	
 * 	@author : Cristian Marques
 * 	30/04/2019
 */

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('crud_model');
        $this->load->database();
        $this->load->library('session');
        /* cache control */
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
    }

    //Default function, redirects to logged in user area
    public function index() {

        if ($this->session->userdata('admin_login') == 1)
            redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
        
        else if ($this->session->userdata('cliente_login') == 1)
            redirect(base_url() . 'index.php?cliente', 'refresh');
        
        else if ($this->session->userdata('accountant_login') == 1)
            redirect(base_url() . 'index.php?accountant', 'refresh');

        $this->load->view('backend/login');
    }

    //Ajax login function 
    function ajax_login() {
        $response = array();

        //Recieving post input of email, password from ajax request
        $email = $_POST["email"];
        $password = $_POST["password"];
        $response['submitted_data'] = $_POST;

        //Validating login
        $login_status = $this->validate_login($email, $password);
        $response['login_status'] = $login_status;
        if ($login_status == 'success') {
            $response['redirect_url'] = $this->session->userdata('last_page');
        }

        //Replying ajax request with validation response
        echo json_encode($response);
    }

    //Validating login from ajax request
    function validate_login($email = '', $password = '') {
        $credential = array('email' => $email, 'password' => sha1($password));
        //echo"<pre>"; print_r($GLOBALS);echo"</pre>";exit;
        // Checking login credential for admin
        $query = $this->db->get_where('admin', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $this->session->set_userdata('admin_login', '1');
            $this->session->set_userdata('login_user_id', $row->admin_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'admin');
            return 'success';
        }
    
        
        $query = $this->db->get_where('clientes', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $this->session->set_userdata('cliente_login', '1');
            $this->session->set_userdata('login_user_id', $row->cliente_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'clientes');
            return 'success';
        }
        
        
        $query = $this->db->get_where('accountant', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $this->session->set_userdata('accountant_login', '1');
            $this->session->set_userdata('login_user_id', $row->accountant_id);
            $this->session->set_userdata('name', $row->name);
            $this->session->set_userdata('login_type', 'accountant');
            return 'success';
        }

        return 'invalid';
    }

    /*     * *DEFAULT NOR FOUND PAGE**** */

    function four_zero_four() {
        $this->load->view('four_zero_four');
    }

    /*     * *RESET AND SEND PASSWORD TO REQUESTED EMAIL*** */

    function reset_password() {
        $account_type = $this->input->post('account_type');
        if ($account_type == "") {
            redirect(base_url(), 'refresh');
        }
        $email = $this->input->post('email');
        $result = $this->email_model->password_reset_email($account_type, $email); //SEND EMAIL ACCOUNT OPENING EMAIL
        if ($result == true) {
            $this->session->set_flashdata('flash_message', get_phrase('password_sent'));
        } else if ($result == false) {
            $this->session->set_flashdata('flash_message', get_phrase('account_not_found'));
        }

        redirect(base_url(), 'refresh');
    }

    /*     * *****LOGOUT FUNCTION ****** */

    function logout() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('logout_notification', 'logged_out');
        redirect(base_url(), 'refresh');
    }

}
