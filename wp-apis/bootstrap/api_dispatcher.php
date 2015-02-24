<?php

/**
 * Description of ApiDispatcher
 *
 * @author Mr.T
 */
class api_dispatcher {
    
    const API_NOT_FOUND_CODE = 1;

    private static $instance = null;

    /*
     * Return API Dispatcher instance
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /*
     * Call coressponding API and return the JSON data
     */
    public function dispath() {
        if (isset($_GET['api'])) {
            $api_class = 'api_' . strtolower($_GET['api']);

            if (!class_exists($api_class)) {
                echo json_encode(array('code' => API_NOT_FOUND_CODE));
                exit;
            }

            $api = new $api_class;
            
            $api->api_execute();
        }
    }

}