<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of api_get_post_details
 *
 * @author NTQ-SOFT
 */
class api_get_post_details extends api_response {

    public $param_names = array(
        'post_id' => 'string',
        'device_id' => 'string'
    );

    //put your code here
    protected function execute() {
        if (!isset($this->parameters['post_id'])
                || !is_numeric($this->parameters['post_id'])
                || !isset($this->parameters['device_id'])) {
            $this->code = self::$CODES['common']['wrong_data_format'];
            return;
        }
        
        $post = get_post($this->parameters['post_id']);
        
        $data = array();

        if (!empty($post)) {
            // next, previous
            $redirect = Post::get_post_next_previous($post);
            
            // Get parent category
            $cats = get_the_category($post->ID);
            $cat_id = '';
            $category_name = '';
            foreach($cats as $cat) {
                if ($cat->name !== '') {
                    $cat_id = $cat->term_id;
                    $category_name = $cat->name;
                    break;
                }
            }
            $image_path = Post::get_post_image($post->post_content);
            $content_desc = strip_tags($post->post_content);
            $desc = mb_substr($content_desc, 0, Post::$DESC_CHAR_COUNT).'...';
            //$content = wpautop(replace_url_image($post->post_content),true);
            $content = wpautop($post->post_content,false);
            $content = apply_filters('the_content', $content);            
            $content = str_replace(']]>', ']]>', $content);            
            $content = $this->convertToIframe($content);
            //check is favorite
            $obj_favorite = new Favorite();
            $favorite = $obj_favorite->get_favorite($this->parameters['device_id'], $post->ID);
            $is_favorite = false;
            if (!empty($favorite)) {
                $is_favorite = true;
            }
            $tag_list = wp_get_post_tags( $post->ID, array( 'fields' => 'names' ) );
            $data = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'body' => '<div style="line-height: 1.5;">'.$content.'</div>',
                'list_of_tags' => $tag_list,
                'create_date' => date('Y.m.d', strtotime($post->post_date)),
                'prev_id' => intval($redirect['prev']),
                'next_id' => intval($redirect['next']),
            	'category_id' => $cat_id,
                'category_name' => $category_name,
                'description' => $desc,
                'image' => $image_path,
                'is_favorite' => $is_favorite,
				'facebook_link' => get_permalink($post->ID),
                'twitter_link' => get_permalink($post->ID)
            );
            
            // increasing views for this posts
            $this->postViewCount($post);
        }
        
        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }
    
    /**
     * increasing views when view a posts
     * 
     * @global type $timings
     * @global type $bawpvc_options
     * @param type $post
     */
    private function postViewCount($post) {
        global $timings, $bawpvc_options;
        $IP = substr( md5( getenv( 'HTTP_X_FORWARDED_FOR' ) ? getenv( 'HTTP_X_FORWARDED_FOR' ) : getenv( 'REMOTE_ADDR' ) ), 0, 16 );
		$time_to_go = $bawpvc_options['time']; // Default: no time between count 
		if( (int)$time_to_go == 0 || !get_transient( 'baw_count_views-' . $IP . $post->ID ) ) {
			foreach( $timings as $time=>$date )
			{
				if( $time != 'all' )
					$date = '-' . date( $date );
				// Filtered meta key name
				//$meta_key_filtered = apply_filters( 'baw_count_views_meta_key', '_count-views_' . $time . $date, $time, $date );
                                $meta_key_filtered = apply_filters( 'baw_count_views_meta_key', 'post_views_count' . $time . $date, $time, $date );
				$count = (int)get_post_meta( $post->ID, $meta_key_filtered, true );
				$count++;
				update_post_meta( $post->ID, $meta_key_filtered, $count );
				// Normal meta key name
				$meta_key = 'post_views_count';
				if( $meta_key_filtered != $meta_key ):
					$count = (int)get_post_meta( $post->ID, $meta_key, true );
					$count++;
					update_post_meta( $post->ID, $meta_key, $count );
				endif;
				//// I update 2 times with 2 different meta names because i need to keep my own count too, in bonus of hacked/filtered count.
			}
			if( (int)$time_to_go > 0 )
				set_transient( 'baw_count_views-' . $IP . $post->ID, $IP, $time_to_go );
		}
    }
    
    /**
     * Convert youtube linke to youtube iframe 
     * 
     * @example path http://www.youtube.com/watch?v=iwGFalTRHDA become <iframe width="560" height="315" src="//www.youtube.com/embed/tgvo9_pRfHE" frameborder="0" allowfullscreen></iframe>
     * @example path http://www.youtube.com/watch?v=iwGFalTRHDA to <object>....</object>
     * 
     * @param string $content String want to find out and replace.
     * @param string $type 'iframe' or 'object'
     * 
     * @return html Return html code with Youtobe iframe embed or Youtube object embed.
     */
    private function convertToIframe($content, $type='iframe') {
        //            $content = preg_replace_callback('#(?:https?://\S+)|(?:www.\S+)|(?:\S+\.\S+)#', function($arr)
        //return html string with youtube object
        if($type == 'object') {
            return $content = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i"," <object width=\"100%\" height=\"344\"><param name=\"movie\" value=\"http://www.youtube.com/v/$1&hl=en&fs=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param><embed src=\"http://www.youtube.com/v/$1&hl=en&fs=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" width=\"100%\" height=\"450\"></embed></object>  ",$content);
        }
//        $content = preg_replace('/<a>(.*?)<\/a>/s', '', $content);
//        $content = preg_replace('/<img(.*?)\/>/s', '', $content);
//        $content = $arr_str[0];
//        $content = preg_replace_callback('#\s((https?:\/\/\S+)|(www.\S+)|(?:youtube.com))#', function($arr)  
        /*
        return  preg_replace(
                "/((?!)\w*[[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*))/i",
                " <iframe class=\"youtube-player\" src=\"http://www.youtube.com/embed/$1\" allowfullscreen></iframe>",
                $content);
         * 
         */
//        $content = preg_replace_callback('#(\s)((http:\/\/\S+)|(www.\S+)|(youtube.com))#', function($arr)  
        $content = preg_replace_callback('/\b(?<!=")(https?|ftp|file|http):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*((youtube.com\/watch\?v=)|(youtu\\.be\/))([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)(?!.*".*>)(?!.*<\/a>)/i', function($arr)        
            {
                $width = "";
                $height = "";
                $frameborder = "0";//String : 0|1
                $str = $arr[0];
                strip_tags($arr[0]);
                if(strpos($arr[0], 'http://') !== 0)
                {
                    $arr[0] = 'http://' . $arr[0];
                }
                $url = parse_url($arr[0]);
                // youtube
                if(in_array($url['host'], array('www.youtube.com', 'youtube.com', 'youtu.be'))
                  && $url['path'] == '/watch'
                  && isset($url['query']))
                {
                    parse_str($url['query'], $query);
                    return sprintf('<iframe class="youtube-player" src="http://www.youtube.com/embed/%s" allowfullscreen frameborder="'. $frameborder .'" ></iframe>', $query['v']);
                }
                if(in_array($url['host'], array('youtu.be')))
                {
                    $video_id = substr($url['path'], 1);
                    return sprintf('<iframe class="youtube-player" src="http://www.youtube.com/embed/%s" allowfullscreen frameborder="'. $frameborder .'"></iframe>', $video_id);
                }
                //links
                return $str;
            }, $content);
        return $content;
    }
}
