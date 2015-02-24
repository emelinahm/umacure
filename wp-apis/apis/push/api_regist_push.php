<?php
// TODO: replace data dummy
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Register push notification
 *
 * @author NTQ-SOFT
 */

class api_regist_push extends api_response {

    public $param_names = array(
        'device_id' => 'string',
    	'push_token' => 'string',
    	'type'=> 'string'
    );

    protected function execute() {
        if (!isset($this->parameters['push_token']) 
                || !isset($this->parameters['type']) 
                || !is_numeric($this->parameters['type'])
                || !isset($this->parameters['device_id'])) {
            $this->code = self::$CODES['common']['wrong_data_format'];
            return;
        }
        $device_obj = new Devices();
        $device = $device_obj->check_duplicate_device($this->parameters['push_token'], $this->parameters['device_id']);
        
        if ($device) {
            $this->code = self::$CODES['common']['exist_data'];
            return;
        } else {
            $args = array();
            $args['device_id'] = $this->parameters['device_id'];
            $args['token'] = $this->parameters['push_token'];
            $args['type'] = $this->parameters['type'];
            
            $time = date('Y-m-d H:i:s', time());
            $args['updated_at'] = $time;
            $args['notified'] = 1;
            if( $args['type'] == 0 ) {//IOS
                if($device_obj->check_duplicate_token($this->parameters['push_token'])) {
                    $setDevice = $device_obj->editIOS($args, $this->parameters['push_token']);
                }else {
                    $setDevice = $device_obj->add($args);
                }              
            }else {
                if($device_obj->check_duplicate_device_id($this->parameters['device_id'])) {
                    $setDevice = $device_obj->edit($args, $this->parameters['device_id']);
                }else {
                    $setDevice = $device_obj->add($args);
                }
            }
            
            if (!$setDevice) {
                $this->code = self::$CODES['common']['failed'];
                return;
            }
        }
        $data = array();
        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
        
    }
   
}
