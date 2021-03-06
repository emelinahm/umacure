<?php
// TODO: Get posts views
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Get list post for TOP page
 *
 * @author NTQ-SOFT
 */
class api_get_top extends api_response {
    /*
     * Parameter list
     */
    public $param_names = array(
        'take' => 'string',
        'skip' => 'string',
    );
    
    protected $args = array();
    
    public function __construct() {
        parent::__construct();
        
        $this->args = array(
            'posts_per_page' => $this->parameters['take'], // take
            'offset' => $this->parameters['skip'], // skip
            'category__not_in' => self::$UNCATEGORY,    //exclude uncategoried posts
            'post_status' => 'publish',
            'orderby'    => 'post_date',
            'order'      => 'DESC'
        );
    }

    protected function execute() {
        if (!is_numeric($this->parameters['take']) || !is_numeric($this->parameters['skip'])) {
            $this->code = self::$CODES['common']['wrong_data_format'];
            return;
        }
        //Posts data
        $posts = get_posts($this->args);
        $data = array();
        
        if (!empty($posts)) {
            foreach ($posts as $obj_posts) {
                $categories = get_the_category($obj_posts->ID);
                $category_name = '';
                $category_slug = '';
                foreach($categories as $category) {
                    if ($category->name !== '') {
                        $category_name = $category->name;
                        $category_slug = $category->slug;
                        break;
                    }
                }

                //Get first image of the post
                $image_path = Post::get_post_image($obj_posts->post_content);
                $content = strip_tags($obj_posts->post_content);
                $desc = mb_substr($content, 0, Post::$DESC_CHAR_COUNT).'...';
                
                //get list of tag
                $tag_list = wp_get_post_tags( $obj_posts->ID, array( 'fields' => 'names' ) );

                //build response data
                $post_info = array(
                    'id' => $obj_posts->ID,
                    'create_date' => date('Y.m.d', strtotime($obj_posts->post_date)),
                    'category_name' => $category_name,
                    'category_slug' => $category_slug,
                    'title' => $obj_posts->post_title,
                    'image' => $image_path,
                    'description' => $desc,
                    'list_of_tags' => $tag_list
                );
                $data[] = $post_info;
            }
        }

        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }

}
