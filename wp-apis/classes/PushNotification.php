<?php

/*
 * To management push notification
 */

/**
 * Description of PushNotification
 *
 * @author Emelina
 */
class PushNotification extends Object {
    public static $instance = null;
    public $db;
    public $table;


    public function __construct() {
        global $wpdb;
        
        $this->db = $wpdb;
        $this->table = $this->db->prefix . 'post_notifications';
    }

    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new Devices();
        }
        
        return self::$instance;
    }
    
    /**
     * Update data
     * 
     * @param type $table
     * @param type $data
     * @param type $id
     */
    public function edit($data = array(), $push_token, $object_id) {
        if (!is_array($data) || empty($push_token) || empty($object_id)) {
            return;
        }
        return $this->db->update($this->table, $data, array('token' => $push_token, 'object_id' => $object_id));
    }
}
