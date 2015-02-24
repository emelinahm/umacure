<?php

/**
 * Get Header
 * Return header of post detail page.
 * 
 * @author Emelina
 */
class api_get_header extends api_response {

    protected function execute() {

        //Get first post to link to this post detail.
        $args = array('posts_per_page' => 1, 'offset' => 1, 'category' => 1);
        $myposts = get_posts($args);
        if(count($myposts) > 0) {
            $url = $myposts[0]->guid;
        }else {
            $url = get_site_url();
        }
        
        //Get content from link of the post
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Internet Explorer");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        ob_start();
        curl_exec($ch);
        curl_close($ch);
        $data = ob_get_contents();
        ob_end_clean();
        
        //Regex to get header
        $regex = '#<head>(.*)</head>#is';
        preg_match($regex, $data, $match);
        $this->code = self::$CODES['common']['success'];
        $this->data = $match[0];
    }

}
