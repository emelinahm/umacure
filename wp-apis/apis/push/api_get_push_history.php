<?php
// TODO: replace data dummy
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Get push history list for a device
 *
 * @author NTQ-SOFT
 */

class api_get_push_history extends api_response {

    public $param_names = array(
        'push_token' => 'string',
        'skip' => 'string',
        'take' => 'string'
    );

    protected function execute() {
        if (!isset($this->parameters['push_token']) ||
                !isset($this->parameters['skip']) ||
                !isset($this->parameters['take']) ||
                !is_numeric($this->parameters['skip']) ||
                !is_numeric($this->parameters['take'])) {
            $this->code = self::$CODES['common']['wrong_data_format'];
            return;
        }
        //get push notification history of this device
        global $wpdb;
        $data = array();
        
        $sql  = "SELECT t.object_id, t.created_at ";
        $sql .= "FROM {$wpdb->prefix}post_notifications AS t ";
        $sql .= "WHERE t.token = '". $this->parameters['push_token'] . "' ";
        $sql .= "ORDER BY t.created_at DESC ";
        $sql .= "LIMIT ". $this->parameters['skip'] . ', '.$this->parameters['take'];
        
        $pushes = $wpdb->get_results($sql, OBJECT);
        
        if (!empty($pushes)) {
            foreach ($pushes as $key => $push) {
                $obj_posts = get_post($push->object_id);
                //get each post
                if (!empty($obj_posts)) {
                    //build response data
                    $post_info = array(
                        'article_id' => $obj_posts->ID,
                        'created_date' => date('Y.m.d', strtotime($push->created_at)),
                        'title' => $obj_posts->post_title,
                    );

                    $data[] = $post_info;
                }
            }
        }
        $post_obj = new Post();
    	$post_obj->update_post_notification($this->parameters['push_token']);
        
        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }
   
}
