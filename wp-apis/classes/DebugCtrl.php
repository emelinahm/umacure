<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of debug_ctrl
 *
 * @author Emelina
 */
class DebugCtrl {
    const DEBUG_CONTROL = true;
    const MESSAGE_TYPE = 3;
    const DESTINATION = 'debug.log';
    
    public function get_error_log($message) {
        if(self::DEBUG_CONTROL) {
            error_log($message, self::MESSAGE_TYPE, self::DESTINATION);
        }      
    }
}
