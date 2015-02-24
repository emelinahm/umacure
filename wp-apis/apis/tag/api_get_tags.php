<?php

/**
 * Get list of tags
 * 
 * @author NTQ-SOFT
 */
class api_get_tags extends api_response {
    /*
     * Parameter list
     */
    public $param_names = array(
        'take' => 'string',
        'skip' => 'string',
    );
    
    protected $args = array();
    
    public function __construct() {
        parent::__construct();
        
        $this->args = array(
            'number' => $this->parameters['take'], // take
            'offset' => $this->parameters['skip']
        );
    }
    
    protected function execute() {
        if (!is_numeric($this->parameters['take']) || !is_numeric($this->parameters['skip'])) {
            $this->code = self::$CODES['common']['wrong_data_format'];
            return;
        }
        $data = array();
        $tags = get_tags($this->args);
        foreach ( $tags as $tag ) {
            $data[] = array(
                'tag_id' => $tag->term_id,
                'tag_name' => $tag->name
            );
        }

        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }
}