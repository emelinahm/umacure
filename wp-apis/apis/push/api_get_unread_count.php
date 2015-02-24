<?php
// TODO: replace data dummy
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Get number of un-read notification
 *
 * @author NTQ-SOFT
 */

class api_get_unread_count extends api_response {

    public $param_names = array(
        'push_token' => 'string'
    );

    protected function execute() {
        if (!isset($this->parameters['push_token'])) {
            $this->code = self::$CODES['common']['wrong_data_format'];
            return;
        }
        $count = 0;
        global $wpdb;
        $table_device = $wpdb->prefix.'devices';
        $table_post_notification = $wpdb->prefix.'post_notifications';

        $unreads = $wpdb->get_results(
                "SELECT object_id "
                . "FROM $table_post_notification "
                . "WHERE token = '". $this->parameters['push_token'] ."' "
                    . "AND is_read = 0;"
                , OBJECT);
        foreach($unreads as $post) {
                $unreadIds[] = (int)$post->object_id;
        }
        if (count($unreadIds) === 0) {
                $this->data = 0;
        } else {
            foreach ($unreadIds as $value) {
                $meta_values = get_post_meta( $value, 'ispushnotification' ); 
                if($meta_values[0] == "1") $count++;
            }
        }
        $data = array('count' => $count);
        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }
   
}
