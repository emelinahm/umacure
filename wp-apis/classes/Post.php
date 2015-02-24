<?php

class Post extends Object {
    /*
     * Number of character in post description
     */
    public static $DESC_CHAR_COUNT = 40;
    /**
     * Get post's image path
     * 
     * @param type $post_id
     * @param type $type: default is 'full' size
     */
    public static function get_post_image_path($post_id, $type = 'full') {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $type);
        
        return $image[0];
    }
    /**
     * Get first image
     * @return string
     */
    public static function get_post_image($content) {
        $first_img = '';
        $cnt = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches, PREG_SET_ORDER);
        if ($cnt > 0 && is_array($matches)) {
            $first_img = $matches [0] [1];
        }
        if(empty($first_img)){
            //no-image
            $first_img = '';
        }
        return $first_img;
    }
    
    /**
     * 
     * @param type $post
     * @return type
     */
    public static function get_post_next_previous($post) {
        $result = get_posts(array(
            'category__in' => wp_get_post_categories($post->ID),
            'posts_per_page'  => -1
        ));

        $ids = array();
        foreach ($result as $key => $post_obj) {
            $ids[] = $post_obj->ID;
        }

        $thisindex = array_search($post->ID, $ids);

        // order by cre_date DESC => pre + 1, next - 1
        return array(
            'next' => $ids[$thisindex - 1],
            'prev' => $ids[$thisindex + 1]
        );
    }
    
    public function update_post_notification ($token_id) {
    	global $wpdb;
    	$table_post_notification = $wpdb->prefix.'post_notifications';
    	$data = array();
    	$data['is_read'] = 1;
    	$wpdb->update($table_post_notification, $data, array('token'=>$token_id));
    }
}