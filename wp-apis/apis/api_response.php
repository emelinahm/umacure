<?php

/**
 * Description of __base_respone_data
 *
 * @author NTQ-SOFT
 */

require_once("../wp-config.php");
require_once("../wp-includes/functions.php");

/*
 * Base API class for all APIs
 */
abstract class api_response {
    /**
     * API token
     * sha1('marinax_umacure')
     */
    protected static $token = 'b752dc0dcf5ae7c8eb9e03d43602f2f2b93778c4'; 

    /*
     * API response code
     */
    protected $code;
    
    /*
     * API response data
     */
    protected $data = array();
    
    /*
     * API request parameter names
     */
    protected $param_names = array();
    
    /*
     * API request parameters
     */
    protected $parameters = array();
    
    protected static $CODES = array(
        'common' => array(
            'success' => 0,
            'failed' => 1,
            'invalid_token' => 2,
            'wrong_data_format' => 3,
            'exist_data'=> 4,
            'not_exist_data' => 5
        ),
        'post' => array(),
        'category' => array()
    );
    // uncategories id post
    protected static $UNCATEGORY = 1;
    //recommend category
    protected static $RECOMMENDATION_CATE = 10;
    //explanation category
    protected static $EXPLANATION_ID = 9;
    protected static $EXPLANATION_SLUG = 'explanation';
    //favorite category お気に入り
    protected static $FAVORITE_NAME = 'お気に入り';
    
    /*
     * Constructor
     */
    public function __construct() {
        $this->init_api_params();
    }
    
    protected abstract function execute();
    
    public function api_execute() {
        //TODO: Need to check api token here
        ob_start();
        try {
            if (!isset($this->parameters['token']) || $this->parameters['token'] != self::$token) {
                $this->code = self::$CODES['common']['invalid_token'];
            } else {
                $this->execute();
            }
        } catch (Exception $ex) {
            $this->error(self::$CODES['common']['failed'], $ex->getMessage());
        }
        ob_end_clean();
        $this->response_json();
    }
    
    /*
     * Set error code & error message
     */
    protected function error($code, $message = '') {
        $this->code = $code;
        $this->data = array(
            'error' => $message
        );
    }

    /*
     * Convert api response data to json format and return to client
     */
    protected function response_json() {
        $return = array();

        $return['code'] = $this->code;

        if (isset($this->data)) {
            $return['data'] = $this->data;
        }

        header('Content-type: application/json');
        echo json_encode($return);
        exit;
    }
    
    /*
     * Initialize the api parameters variable
     */
    protected function init_api_params() {
        $source = $_REQUEST;
        $this->param_names['token'] = isset($source['token']) ? $source['token'] : null;

        foreach ($this->param_names as $param_name => $type) {
            if (isset($source[$param_name])) {
                $value = $source[$param_name];

                $this->parameters[$param_name] = $type === 'int' ? intval($value) : $value;
            }
        }
    }

    /*
     * Print debug info
     */
    protected function pr($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
    
}