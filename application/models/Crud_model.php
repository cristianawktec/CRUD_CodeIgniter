<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crud_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function clear_cache() {
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function get_type_name_by_id($type, $type_id = '', $field = 'name') {
        $this->db->where($type . '_id', $type_id);
        $query = $this->db->get($type);
        $result = $query->result_array();
        foreach ($result as $row)
            return $row[$field];
        //return	$this->db->get_where($type,array($type.'_id'=>$type_id))->row()->$field;	
    }
  

    //////system settings//////
    function update_system_settings() {
        $data['description'] = $this->input->post('system_name');
        $this->db->where('type', 'system_name');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('system_title');
        $this->db->where('type', 'system_title');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('address');
        $this->db->where('type', 'address');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('phone');
        $this->db->where('type', 'phone');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('paypal_email');
        $this->db->where('type', 'paypal_email');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('currency');
        $this->db->where('type', 'currency');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('system_email');
        $this->db->where('type', 'system_email');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('buyer');
        $this->db->where('type', 'buyer');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('system_name');
        $this->db->where('type', 'system_name');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('purchase_code');
        $this->db->where('type', 'purchase_code');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('language');
        $this->db->where('type', 'language');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('text_align');
        $this->db->where('type', 'text_align');
        $this->db->update('settings', $data);
    }
    
    // SMS settings.
    function update_sms_settings() {
        
        $data['description'] = $this->input->post('clickatell_user');
        $this->db->where('type', 'clickatell_user');
        $this->db->update('settings', $data);
        
        $data['description'] = $this->input->post('clickatell_password');
        $this->db->where('type', 'clickatell_password');
        $this->db->update('settings', $data);
        
        $data['description'] = $this->input->post('clickatell_api_id');
        $this->db->where('type', 'clickatell_api_id');
        $this->db->update('settings', $data);
    }

    /////creates log/////
    function create_log($data) {
        $data['timestamp'] = strtotime(date('d-m-Y') . ' ' . date('H:m:s'));
        $data['ip'] = $_SERVER["REMOTE_ADDR"];
        $location = new SimpleXMLElement(file_get_contents('http://freegeoip.net/xml/' . $_SERVER["REMOTE_ADDR"]));
        $data['location'] = $location->City . ' , ' . $location->CountryName;
        $this->db->insert('log', $data);
    }

    ////////BACKUP RESTORE/////////
    function create_backup($type) {
        $this->load->dbutil();


        $options = array(
            'format' => 'txt', // gzip, zip, txt
            'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
            'add_insert' => TRUE, // Whether to add INSERT data to backup file
            'newline' => "\n"               // Newline character used in backup file
        );


        if ($type == 'all') {
            $tables = array('');
            $file_name = 'system_backup';
        } else {
            $tables = array('tables' => array($type));
            $file_name = 'backup_' . $type;
        }

        $backup = & $this->dbutil->backup(array_merge($options, $tables));


        $this->load->helper('download');
        force_download($file_name . '.sql', $backup);
    }

    /////////RESTORE TOTAL DB/ DB TABLE FROM UPLOADED BACKUP SQL FILE//////////
    function restore_backup() {
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/backup.sql');
        $this->load->dbutil();


        $prefs = array(
            'filepath' => 'uploads/backup.sql',
            'delete_after_upload' => TRUE,
            'delimiter' => ';'
        );
        $restore = & $this->dbutil->restore($prefs);
        unlink($prefs['filepath']);
    }

    /////////DELETE DATA FROM TABLES///////////////
    function truncate($type) {
        if ($type == 'all') {
            $this->db->truncate('student');
            $this->db->truncate('mark');
            $this->db->truncate('teacher');
            $this->db->truncate('subject');
            $this->db->truncate('class');
            $this->db->truncate('exam');
            $this->db->truncate('grade');
        } else {
            $this->db->truncate($type);
        }
    }

    ////////IMAGE URL//////////
    function get_image_url($type = '', $id = '') {
        if (file_exists('uploads/' . $type . '_image/' . $id . '.jpg'))
            $image_url = base_url() . 'uploads/' . $type . '_image/' . $id . '.jpg';
        else
            $image_url = base_url() . 'uploads/user.jpg';

        return $image_url;
    }
    
    function save_cliente_info()
    {
        $data['name'] 		= $this->input->post('name');
        $data['email'] 		= $this->input->post('email');
        $data['password']       = sha1($this->input->post('password'));
        $data['phone']          = $this->input->post('phone');
        
        $this->db->insert('clientes',$data);
        
        $cliente_id  =   $this->db->insert_id();
        move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/cliente_image/" . $cliente_id . '.jpg');
    }
    
    function select_cliente_info()
    {
        //echo"Cliente: <pre>";print_r($this->db->get('clientes')->result_array());echo"</pre>";exit;
        return $this->db->get('clientes')->result_array();
    }
    
    function select_cliente_info_by_cliente_id( $cliente_id = '' )
    {
        return $this->db->get_where('clientes', array('cliente_id' => $cliente_id))->result_array();
    }
            
    function update_cliente_info($cliente_id)
    {
        $data['name'] 		= $this->input->post('name');
        $data['email'] 		= $this->input->post('email');
        $data['phone']      = $this->input->post('phone');
        
        $this->db->where('cliente_id',$cliente_id);
        $this->db->update('clientes',$data);
        
        move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/cliente_image/" . $cliente_id . '.jpg');
    }
    
    function delete_cliente_info($cliente_id)
    {
        $this->db->where('cliente_id',$cliente_id);
        $this->db->delete('clientes');
    }
    
    
}
