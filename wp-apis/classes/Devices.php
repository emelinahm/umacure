<?php

/**
 * Description of db_action
 *
 * @author NTQ-SOFT
 */
class Devices {
    public static $instance = null;
    public $db;
    public $table;


    public function __construct() {
        global $wpdb;
        
        $this->db = $wpdb;
        $this->table = $this->db->prefix . 'devices';
    }

    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new Devices();
        }
        
        return self::$instance;
    }
    /**
     * Insert data into database
     * 
     * @param type $table
     * @param type $data
     * @return type
     */
    public function add($data = array()) {
        if (!is_array($data) || empty($data)) {
            return;
        }
        
        return $this->db->insert($this->table, $data);

    }
    
    /**
     * Update data
     * 
     * @param type $table
     * @param type $data
     * @param type $id
     */
    public function edit($data = array(), $device_id) {
        if (!is_array($data) || empty($device_id)) {
            return;
        }
        
        return $this->db->update($this->table, $data, array('device_id' => $device_id));
    }
    
    /**
     * Update data
     * 
     * @param type $table
     * @param type $data
     * @param type $id
     */
    public function editIOS($data = array(), $token) {
        if (!is_array($data) || empty($token)) {
            return;
        }
        
        return $this->db->update($this->table, $data, array('token' => $token));
    }
    
    /**
     * Delete Data
     * 
     * @param type $table
     * @param type $id
     */
    public function delete($id) {
        if (!is_numeric($id) > 0) {
            return;
        }

        return $this->db->delete($this->table, array('ID' => $id));
    }
    
    /**
     * Return an device object
     * 
     * @param type $device_id
     */
    public function get_device_by_device_id($device_id) {
        if (empty($device_id)) {
            return;
        }
        
        return $this->db->get_row('SELECT * FROM ' . $this->table . ' WHERE device_id = "' . $device_id . '"', OBJECT);
    }
    
    /**
     * Get all devices
     */
    public function get_all_devices() {
        return $this->db->get_results('SELECT * FROM ' . $this->table, OBJECT);
    }
    
    /**
     * Return true if duplicate device esle false
     * 
     * @param type $device
     * @return boolean
     */
    public function check_duplicate_device($token, $device_id) {
        if (empty($token) || empty($device_id)) {
            return false;
        }
        
        $data = $this->db->get_row('SELECT * FROM ' . $this->table . ' WHERE token = "' . $token . '" and device_id = "' . $device_id . '"', OBJECT);
        if (empty($data)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Return true if duplicate device esle false
     * 
     * @param type $device
     * @return boolean
     */
    public function check_duplicate_token($token) {
        if (empty($token)) {
            return false;
        }
        
        $data = $this->db->get_row('SELECT * FROM ' . $this->table . ' WHERE token = "' . $token . '"', OBJECT);
        
        if (empty($data)) {
            return false;
        }
        
        return true;
    }
    /**
     * Return true if duplicate device esle false
     * 
     * @param type $device
     * @return boolean
     */
    public function check_duplicate_device_id($device) {
        if (empty($device)) {
            return false;
        }
        
        $data = $this->db->get_row('SELECT * FROM ' . $this->table . ' WHERE device_id = "' . $device . '"', OBJECT);
        
        if (empty($data)) {
            return false;
        }
        
        return true;
    }
}
