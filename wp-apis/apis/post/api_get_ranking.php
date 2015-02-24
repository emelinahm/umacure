<?php
// TODO: Get posts views
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Get list ranking by week
 *
 * @author NTQ-SOFT
 */
class api_get_ranking extends api_response {
    /*
     * Take count
     */
    public static $RANKING_COUNT = 20;
    /*
     * Parameter list
     */
    public $param_names = array(
        'take' => 'string'
    );
    
    protected $args = array();
    
    public function __construct() {
        parent::__construct();
    }

    protected function execute() {
        $take = self::$RANKING_COUNT;
        if (is_numeric($this->parameters['take'])) {
            $take = $this->parameters['take'];
        }
        $data = array();

        global $wpdb;

        $sql  = "SELECT t.postid ";
        $sql .= "FROM {$wpdb->prefix}popularpostsdata AS t ";
        $sql .= "WHERE t.last_viewed >= '". date('Y-m-d', strtotime('-7 days')) . "' ";
        $sql .= "ORDER BY t.pageviews DESC";
        $popular_posts = $wpdb->get_results($sql, OBJECT);
        $post_cnt = 0;
        if (!empty($popular_posts)) {
            foreach ($popular_posts as $key => $popular_post) {
                //get post
                if ($post_cnt === self::$RANKING_COUNT) {
                    break;
                }
                $post = get_post($popular_post->postid);
                $categories = get_the_category($popular_post->postid);
                $category_name = '';
                foreach($categories as $category){
                    if (isset($category->name) && $category->name != "") {
                        $category_name = $category->name;
                        break;
                    }
                }
                if ($category_name !== '' && $post) {
                    $image_path = Post::get_post_image($post->post_content);
                    //build response data
                    $data[] = array(
                        'id' => $post->ID,
                        'create_date' => date('Y.m.d', strtotime($post->post_date)),
                        'category_name' => $category_name,
                        'title' => $post->post_title,
                        'image' => $image_path
                    );
                    $post_cnt++;
                }
            }
        }

        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }

}
