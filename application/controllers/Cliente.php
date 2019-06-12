<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cliente extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    function index() {
        if ($this->session->userdata('cliente_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $data['page_name'] = 'dashboard';
        $data['page_title'] = get_phrase('cliente_dashboard');
        $this->load->view('backend/index', $data);
    }


    function admit_history($task = "") {
        if ($this->session->userdata('cliente_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $data['page_name'] = 'show_admit_history';
        $data['page_title'] = get_phrase('admit_history');
        $this->load->view('backend/index', $data);
    }

    function operation_history($task = "") {
        if ($this->session->userdata('cliente_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $data['page_name'] = 'show_operation_history';
        $data['page_title'] = get_phrase('operation_history');
        $this->load->view('backend/index', $data);
    }

    function profile($task = "") {
        if ($this->session->userdata('cliente_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $cliente_id = $this->session->userdata('login_user_id');
        if ($task == "update") {
                $this->crud_model->update_cliente_info($cliente_id);
                $this->session->set_flashdata('message', get_phrase('profile_info_updated_successfuly'));
                redirect(base_url() . 'index.php?cliente/profile');
        }

        if ($task == "change_password") {
            $password = $this->db->get_where('clientes', array('cliente_id' => $cliente_id))->row()->password;
            $old_password = sha1($this->input->post('old_password'));
            $new_password = $this->input->post('new_password');
            $confirm_new_password = $this->input->post('confirm_new_password');

            if ($password == $old_password && $new_password == $confirm_new_password) {
                $data['password'] = sha1($new_password);

                $this->db->where('cliente_id', $cliente_id);
                $this->db->update('clientes', $data);

                $this->session->set_flashdata('message', get_phrase('password_info_updated_successfuly'));
                redirect(base_url() . 'index.php?cliente/profile');
            } else {
                $this->session->set_flashdata('message', get_phrase('password_update_failed'));
                redirect(base_url() . 'index.php?cliente/profile');
            }
        }

        $data['page_name'] = 'edit_profile';
        $data['page_title'] = get_phrase('profile');
        $this->load->view('backend/index', $data);
    }

    /* private messaging */

    function message($param1 = 'message_home', $param2 = '', $param3 = '') {
        if ($this->session->userdata('cliente_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'send_new') {
            $message_thread_code = $this->crud_model->send_new_private_message();
            $this->session->set_flashdata('message', get_phrase('message_sent!'));
            redirect(base_url() . 'index.php?cliente/message/message_read/' . $message_thread_code, 'refresh');
        }

        if ($param1 == 'send_reply') {
            $this->crud_model->send_reply_message($param2);  //$param2 = message_thread_code
            $this->session->set_flashdata('message', get_phrase('message_sent!'));
            redirect(base_url() . 'index.php?cliente/message/message_read/' . $param2, 'refresh');
        }

        if ($param1 == 'message_read') {
            $page_data['current_message_thread_code'] = $param2;  // $param2 = message_thread_code
            $this->crud_model->mark_thread_messages_read($param2);
        }

        $page_data['message_inner_page_name'] = $param1;
        $page_data['page_name'] = 'message';
        $page_data['page_title'] = get_phrase('private_messaging');
        $this->load->view('backend/index', $page_data);
    }

}
