<?php
// TODO: replace data dummy
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Get put notification setting of device
 *
 * @author NTQ-SOFT
 */

class api_get_push_setting extends api_response {

    public $param_names = array(
        'device_id' => 'string'
    );

    protected function execute() {
        if (!isset($this->parameters['device_id'])) {
            $this->code = self::$CODES['common']['wrong_data_format'];
            return;
        }
        $device_obj = new Devices();
        $device = $device_obj->get_device_by_device_id($this->parameters['device_id']);
        if (!$device) {
            $this->code = self::$CODES['common']['failed'];
            return;
        }
        $data = array('setting' => intval($device->notified));
        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }
   
}
