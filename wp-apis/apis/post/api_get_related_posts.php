<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Get related post
 *
 * @author NTQ-SOFT
 */
class api_get_related_posts extends api_response {
    
    public $param_names = array(
        'post_id'   => 'string',
        'skip'      => 'string',
        'take'      => 'string'
    );
    protected $args = array();
    
    public function __construct() {
        parent::__construct();
        
        $this->args = array(
            'limit' => $this->parameters['take'], // take
            'offset' => $this->parameters['skip'], // skip
            'category__not_in' => self::$UNCATEGORY
        );
    }
    
    protected function execute() {
        if (!isset($this->parameters['post_id']) || !is_numeric($this->parameters['post_id'])) {
            $this->code = self::$CODES['common']['wrong_data_format'];
            return;
        }
        
        if (!is_numeric($this->parameters['take']) || !is_numeric($this->parameters['skip'])) {
            $this->code = self::$CODES['common']['wrong_data_format'];
            return;
        }
        
        $post_id = $this->parameters['post_id'];

        $related_posts = yarpp_get_related($this->args, $post_id);
        
        $data = array();
        
        foreach ($related_posts as $post) {
            $image_path = Post::get_post_image($post->post_content);
            $categories = array();
            
            //Get list category names.
            $f_categories = get_the_category($post->ID);
            
            $exclude = array(api_response::$EXPLANATION_ID, api_response::$EXPLANATION_SLUG);
            foreach ($f_categories as $category) {
                if( !in_array($category->cat_ID, $exclude) && !in_array($category->slug, $exclude) && $category->category_parent == 0 ){
                    $category_name = $category->name;
                }
            }
            
            $data[] = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'image' => $image_path,
                'category_name' => $category_name,
                'created_date' => date('Y.m.d', strtotime($post->post_date))
            );
        }

        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }
}
