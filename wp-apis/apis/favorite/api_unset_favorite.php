<?php
// TODO: Get posts views
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Un-set favorite for an article.
 *
 * @author NTQ-SOFT
 */
class api_unset_favorite extends api_response {
    
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

        //Delete data from table wp_favorites
         $favorite_obj = new Favorite();
         $favorite = $favorite_obj->get_favorite($this->parameters['device_id'], $this->parameters['post_id']);
         if (empty($favorite)) {
             //favorite not exist
            $this->code = self::$CODES['common']['not_exist_data'];
            return;
         }
        $del = $favorite_obj->delete($favorite->id);
        if (!$del) {
            //Delete failed
            $this->code = self::$CODES['common']['failed'];
            return;
        }

        $data = array();
        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }

}
