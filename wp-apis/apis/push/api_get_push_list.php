<?php

/**
 * Description of get_posts_rank
 *
 * @author NTQ-SOFT
 */
class api_get_push_list extends api_response {

	public $param_names = array(
                                    'take' => 'string',
                                    'skip' => 'string',
                                    'token_id' => 'string'
                                );

    //put your code here
    protected function execute() {
    	if (!is_numeric($this->parameters['take']) || !is_numeric($this->parameters['skip']) || !isset($this->parameters['token_id'])) {
    		$this->code = self::$CODES['common']['wrong_data_format'];
    		return;
    	}
		// get data post
    	$args = array(
    			'posts_per_page' => $this->parameters['take'], // take
    			'offset' => $this->parameters['skip'], // skip
    			'post_status'=>'publish',
    			'orderby' => 'post_date',
    			'order' => 'desc',
    			'meta_query' => array(
                            array(
                                            'key' => 'ispushnotificaion',
                                            'value' => '1'
                            ),
    			));
    	
    	$pushs = query_posts($args);
    	
    	$post_obj = new Post();
    	$post_obj->update_post_notification($this->parameters['token_id']);
    	// Add list push notified to array
        $data = array();
        foreach ($pushs as $push) {
            $data[] = array(
                            'id' => $push->ID,
                            'title'=> $push->post_title,
                            'post_date' => $push->post_date
            );
        }

        
        $this->code = self::$CODES['common']['success'];
        $this->data = $data;
    }
    
}
