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

class api_set_push_setting extends api_response {

    public $param_names = array(
        'device_id' => 'string',
        'setting' => 'string'
    );

    protected function execute() {
        if (!isset($this->parameters['device_id']) ||
                !isset($this->parameters['setting']) ||
                !is_numeric($this->parameters['setting']) ||
                (intval($this->parameters['setting']) !== 0 && intval($this->parameters['setting']) !== 1)) {
            $this->code = self::$CODES['common']['wrong_data_format'];
            return;
        }
        $device_obj = new Devices();
        $setting_data = array('notified' => $this->parameters['setting']);
        $ret = $device_obj->edit($setting_data, $this->parameters['device_id']);
        
        if (!$ret) {
            $this->code = self::$CODES['common']['failed'];
            return;
        }
        $data = array();
        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }
   
}
