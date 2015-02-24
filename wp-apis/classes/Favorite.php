<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Favorite
 *
 * @author NTQ-Soft
 */
class Favorite {
    public static $instance = null;
    public $db;
    public $table;


    public function __construct() {
        global $wpdb;
        
        $this->db = $wpdb;
        $this->table = $this->db->prefix . 'favorites';
    }

    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new Favorite();
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
     * Get a favorite data
     * 
     * @param type $device_id
     * @param type $post_id
     * @return type
     */
    public function get_favorite($device_id, $post_id) {
        if (!isset($device_id) || !isset($post_id)) {
            return;
        }
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE device_id = "' . $device_id. '" AND post_id = '. $post_id;
        return $this->db->get_row($sql, OBJECT);
    }
    
    /**
     * Check if this item is favorite already.
     * @param type $device_id
     * @param type $post_id
     * @return boolean
     */
    public function check_duplicate_favorite($device_id, $post_id) {
        if (!isset($device_id) || !isset($post_id)) {
            return false;
        }
        
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE device_id = "' . $device_id. '" AND post_id = '. $post_id;
        $data = $this->db->get_row($sql, OBJECT);
        
        if (empty($data)) {
            return false;
        }
        
        return true;
    }
}
