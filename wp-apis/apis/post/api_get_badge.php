<?php

/**
 * Description of get_posts_rank
 *
 * @author NTQ-SOFT
 */
class api_get_badge extends api_response {
	public $param_names = array(
		'token_id' => 'string'
	);

	//put your code here
	protected function execute() {
		global $wpdb;
		$table_device = $wpdb->prefix.'devices';
		$table_post_notification = $wpdb->prefix.'post_notifications';

		$token_id = $this->parameters['token_id'];
                
		$devices = $wpdb->get_results("SELECT * FROM $table_device where notified = 1 and token = '$token_id';", OBJECT);
		if (isset($devices) && count($devices) > 0) {
			$device_token = $devices[0]->token;
			$unreads = $wpdb->get_results("SELECT object_id FROM $table_post_notification WHERE token = '$device_token' AND is_read = 0;", OBJECT);

			$unreadIds = array();

			foreach($unreads as $post) {
				$unreadIds[] = (int)$post->object_id;
			}
			if (count($unreadIds) === 0) {
				$this->data = 0;
			} else {
                            
                            $count = 0;
                            // get list of posts
                            $posts = query_posts(array(
                                            'post__in' => $unreadIds,
                                            'post_status'=> 'publish',
                                            'posts_per_page' => -1,
                                            'meta_query' => array(
                                                            array(
                                                                'key' => 'ispushnotificaion',
                                                                'value' => '2'
                                                            )
                                            ),
                                            'ignore_sticky_posts' => 1
                            ));
                            foreach ($unreadIds as $value) {
                                $meta_values = get_post_meta( $value, 'ispushnotification' ); 
                                if($meta_values[0] == "1") $count++;
                            }
                            $this->data = $count;
			}
			$this->code = self::$CODES['common']['success'];
		} else {
			$this->code = self::$CODES['common']['invalid_token'];
		}
	}
}