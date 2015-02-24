<?php
// TODO: Get posts views
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Set favorite for an article.
 *
 * @author NTQ-SOFT
 */
class api_set_favorite extends api_response {
    
    /*
     * Parameter list
     */
    public $param_names = array(
        'post_id' => 'string',
        'device_id' => 'string'
    );

    protected function execute() {
        if (!is_numeric($this->parameters['post_id'])
                || !is_numeric($this->parameters['post_id'])
                || !isset($this->parameters['device_id'])) {
            $this->code = self::$CODES['common']['wrong_data_format'];
            return;
        }
        //Add this article to category favorite.
        $favorite_obj = new Favorite();
        $favorite = $favorite_obj->check_duplicate_favorite($this->parameters['device_id'], $this->parameters['post_id']);
        
        if ($favorite) {
            //duplicated
            $this->code = self::$CODES['common']['exist_data'];
            return;
        } else {
            $args = array();
            $args['device_id'] = $this->parameters['device_id'];
            $args['post_id'] = $this->parameters['post_id'];
            $time = date('Y-m-d H:i:s', time());
            $args['updated_at'] = $time;
            $setFavorite = $favorite_obj->add($args);
            if (!$setFavorite) {
                $this->code = self::$CODES['common']['failed'];
                return;
            } else {
                //Register favorite success, Add this post to favorite category
//                $ret = wp_set_post_terms($this->parameters['post_id'], array(self::$FAVORITE_CATE), 'category', true );
//                if (is_wp_error($ret) || $ret === false) {
//                    $this->code = self::$CODES['common']['failed'];
//                    return;
//                }
            }
        }
        $data = array();
        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }

}
