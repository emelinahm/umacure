<?php
// TODO: Get posts views
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Get list post of favorite
 *
 * @author NTQ-SOFT
 */
class api_get_favorites extends api_response {
    
    /*
     * Parameter list
     */
    public $param_names = array(
        'take' => 'string',
        'skip' => 'string',
        'device_id' => 'string'
    );

    protected function execute() {
        if (!is_numeric($this->parameters['take']) 
                || !is_numeric($this->parameters['skip'])
                || !isset($this->parameters['device_id'])) {
            $this->code = self::$CODES['common']['wrong_data_format'];
            return;
        }
        
        global $wpdb;
        $data = array();
        
        $sql  = "SELECT f.post_id ";
        $sql .= "FROM {$wpdb->prefix}favorites AS f ";
        $sql .= "WHERE f.device_id = '". $this->parameters['device_id'] . "' ";
        $sql .= "ORDER BY f.updated_at DESC ";
        $sql .= "LIMIT ". $this->parameters['skip'] . ', '.$this->parameters['take'];
        
        $favorites = $wpdb->get_results($sql, OBJECT);
        
        if (!empty($favorites)) {
            foreach ($favorites as $key => $favorite) {
                $obj_posts = get_post($favorite->post_id);
                //get each post
                if (!empty($obj_posts)) {
                    //Get first image of the post
                    $image_path = Post::get_post_image($obj_posts->post_content);
                    $content = strip_tags($obj_posts->post_content);
                    $desc = mb_substr($content, 0, Post::$DESC_CHAR_COUNT).'...';

                    //build response data
                    $post_info = array(
                        'id' => $obj_posts->ID,
                        'create_date' => date('Y.m.d', strtotime($obj_posts->post_date)),
                        'category_name' => self::$FAVORITE_NAME,
                        'title' => $obj_posts->post_title,
                        'image' => $image_path,
                        'description' => $desc
                    );

                    $data[] = $post_info;
                }
            }
        }

        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }

}
