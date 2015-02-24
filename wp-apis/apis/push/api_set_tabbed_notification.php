<?php
// TODO: replace data dummy
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Setting to receive push notification or not.
 *
 * @author NTQ-SOFT
 */

class api_set_tabbed_notification extends api_response {

    public $param_names = array(
        'push_token' => 'string',
        'object_id' => 'string'
    );

    protected function execute() {
        if (!isset($this->parameters['push_token']) || !isset($this->parameters['object_id'])) {
            $this->code = self::$CODES['common']['wrong_data_format'];
            return;
        }
        $pushNotivicationObj = new PushNotification();
        $setting_data = array(
            'is_tab' => true,
            'tab_datetime' => date("Y-m-d H:i:s")
        );
        
        $ret = $pushNotivicationObj->edit($setting_data, $this->parameters['push_token'],$this->parameters['object_id']);
        if (!$ret) {
            $this->code = self::$CODES['common']['failed'];
            return;
        }
        $data = array();
        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }
   
}
