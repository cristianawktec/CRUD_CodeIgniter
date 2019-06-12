<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* 	
 * 	@author : Joyonto Roy
 * 	date	: 1 August, 2014
 * 	http://codecanyon.net/user/Creativeitem
 * 	http://creativeitem.com
 */

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        /* cache control */
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    /*     * *default function, redirects to login page if no admin logged in yet** */

    public function index() {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($this->session->userdata('admin_login') == 1)
            redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
    }

    /*     * *ADMIN DASHBOARD** */

    function dashboard() {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name'] = 'dashboard';
        $page_data['page_title'] = get_phrase('admin_dashboard');
        $this->load->view('backend/index', $page_data);
    }

    /*     * ***LANGUAGE SETTINGS******** */

    function manage_language($param1 = '', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');

        if ($param1 == 'edit_phrase') {
            $page_data['edit_profile'] = $param2;
        }
        if ($param1 == 'update_phrase') {
            $language = $param2;
            $total_phrase = $this->input->post('total_phrase');
            for ($i = 1; $i < $total_phrase; $i++) {
                //$data[$language]	=	$this->input->post('phrase').$i;
                $this->db->where('phrase_id', $i);
                $this->db->update('language', array($language => $this->input->post('phrase' . $i)));
            }
            redirect(base_url() . 'index.php?admin/manage_language/edit_phrase/' . $language, 'refresh');
        }
        if ($param1 == 'do_update') {
            $language = $this->input->post('language');
            $data[$language] = $this->input->post('phrase');
            $this->db->where('phrase_id', $param2);
            $this->db->update('language', $data);
            $this->session->set_flashdata('message', get_phrase('settings_updated'));
            redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
        }
        if ($param1 == 'add_phrase') {
            $data['phrase'] = $this->input->post('phrase');
            $this->db->insert('language', $data);
            $this->session->set_flashdata('message', get_phrase('settings_updated'));
            redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
        }
        if ($param1 == 'add_language') {
            $language = $this->input->post('language');
            $this->load->dbforge();
            $fields = array(
                $language => array(
                    'type' => 'LONGTEXT'
                )
            );
            $this->dbforge->add_column('language', $fields);

            $this->session->set_flashdata('message', get_phrase('settings_updated'));
            redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
        }
        if ($param1 == 'delete_language') {
            $language = $param2;
            $this->load->dbforge();
            $this->dbforge->drop_column('language', $language);
            $this->session->set_flashdata('message', get_phrase('settings_updated'));

            redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
        }
        $page_data['page_name'] = 'manage_language';
        $page_data['page_title'] = get_phrase('manage_language');
        //$page_data['language_phrases'] = $this->db->get('language')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    /*     * ***SITE/SYSTEM SETTINGS******** */

    function system_settings($param1 = '', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');

        if ($param1 == 'do_update') {
            $this->crud_model->update_system_settings();
            $this->session->set_flashdata('message', get_phrase('settings_updated'));
            redirect(base_url() . 'index.php?admin/system_settings/', 'refresh');
        }
        if ($param1 == 'upload_logo') {
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/logo.png');
            $this->session->set_flashdata('message', get_phrase('settings_updated'));
            redirect(base_url() . 'index.php?admin/system_settings/', 'refresh');
        }
        $page_data['page_name'] = 'system_settings';
        $page_data['page_title'] = get_phrase('system_settings');
        $page_data['settings'] = $this->db->get('settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    // SMS settings.
    function sms_settings($param1 = '') {

        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');

        if ($param1 == 'do_update') {
            $this->crud_model->update_sms_settings();
            $this->session->set_flashdata('message', get_phrase('settings_updated'));
            redirect(base_url() . 'index.php?admin/sms_settings/', 'refresh');
        }

        $page_data['page_name'] = 'sms_settings';
        $page_data['page_title'] = get_phrase('sms_settings');
        $this->load->view('backend/index', $page_data);
    }

    /*     * ****MANAGE OWN PROFILE AND CHANGE PASSWORD** */

    function manage_profile($param1 = '', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');

        if ($param1 == 'update_profile_info') {
            $data['name'] = $this->input->post('name');
            $data['email'] = $this->input->post('email');

            $this->db->where('admin_id', $this->session->userdata('login_user_id'));
            $this->db->update('admin', $data);

            $this->session->set_flashdata('message', get_phrase('profile_info_updated_successfuly'));
            redirect(base_url() . 'index.php?admin/manage_profile');
        }
        if ($param1 == 'change_password') {
            $current_password_input = sha1($this->input->post('password'));
            $new_password = sha1($this->input->post('new_password'));
            $confirm_new_password = sha1($this->input->post('confirm_new_password'));

            $current_password_db = $this->db->get_where('admin', array('admin_id' =>
                        $this->session->userdata('login_user_id')))->row()->password;

            if ($current_password_db == $current_password_input && $new_password == $confirm_new_password) {
                $this->db->where('admin_id', $this->session->userdata('login_user_id'));
                $this->db->update('admin', array('password' => $new_password));

                $this->session->set_flashdata('message', get_phrase('password_info_updated_successfuly'));
                redirect(base_url() . 'index.php?admin/manage_profile');
            } else {
                $this->session->set_flashdata('message', get_phrase('password_update_failed'));
                redirect(base_url() . 'index.php?admin/manage_profile');
            }
        }
        $page_data['page_name'] = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $page_data['edit_data'] = $this->db->get_where('admin', array('admin_id' => $this->session->userdata('login_user_id')))->result_array();
        $this->load->view('backend/index', $page_data);
    }


    function cliente($task = "", $cliente_id = "") {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        if ($task == "create") {
            $email = $_POST['email'];
            $cliente = $this->db->get_where('clientes', array('email' => $email))->row()->name;
            if ($cliente == null) {
                $this->crud_model->save_cliente_info();
                $this->session->set_flashdata('message', get_phrase('cliente_info_saved_successfuly'));
            } else {
                $this->session->set_flashdata('message', get_phrase('duplicate_email'));
            }
            redirect(base_url() . 'index.php?admin/cliente');
        }

        if ($task == "update") {
                $this->crud_model->update_cliente_info($cliente_id);
                $this->session->set_flashdata('message', get_phrase('cliente_info_updated_successfuly'));
                redirect(base_url() . 'index.php?admin/cliente');
        }

        if ($task == "delete") {
            $this->crud_model->delete_cliente_info($cliente_id);
            redirect(base_url() . 'index.php?admin/cliente');
        }

        $data['cliente_info'] = $this->crud_model->select_cliente_info();
        $data['page_name'] = 'manage_cliente';
        $data['page_title'] = get_phrase('clientes');
        $this->load->view('backend/index', $data);
    }

    function accountant($task = "", $accountant_id = "") {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        if ($task == "create") {
            $email = $_POST['email'];
            $accountant = $this->db->get_where('accountant', array('email' => $email))->row()->name;
            if ($accountant == null) {
                $this->crud_model->save_accountant_info();
                $this->session->set_flashdata('message', get_phrase('accountant_info_saved_successfuly'));
            } else {
                $this->session->set_flashdata('message', get_phrase('duplicate_email'));
            }
            redirect(base_url() . 'index.php?admin/accountant');
        }

        if ($task == "update") {
                $this->crud_model->update_accountant_info($accountant_id);
                $this->session->set_flashdata('message', get_phrase('accountant_info_updated_successfuly'));
                redirect(base_url() . 'index.php?admin/accountant');
        }

        if ($task == "delete") {
            $this->crud_model->delete_accountant_info($accountant_id);
            redirect(base_url() . 'index.php?admin/accountant');
        }

        $data['accountant_info'] = $this->crud_model->select_accountant_info();
        $data['page_name'] = 'manage_accountant';
        $data['page_title'] = get_phrase('accountant');
        $this->load->view('backend/index', $data);
    }

}
