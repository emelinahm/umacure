<?php

/**
 * Description of get_posts_rank
 *
 * @author NTQ-SOFT
 */
class api_get_push_list_new extends api_response {

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

		// get device
		global $wpdb;
		$table_device = $wpdb->prefix.'devices';
		$table_post_notification = $wpdb->prefix.'post_notifications';

		$token_id = $this->parameters['token_id'];

		$devices = $wpdb->get_results("SELECT * FROM $table_device where type = 1 and notified = 1 and token = '$token_id';", OBJECT);

		if (isset($devices) && count($devices) > 0) {
			$skip = $this->parameters['skip'];
			$take = $this->parameters['take'];
			$device_token = $devices[0]->token;
			// get list of unread posts
			$unreads = $wpdb->get_results("SELECT object_id FROM $table_post_notification WHERE token = '$device_token';", OBJECT);

			$unreadIds = array();

			foreach($unreads as $post) {
				$unreadIds[] = (int)$post->object_id;
			}

			// get list of posts
			$posts = query_posts(array(
					'posts_per_page' => $this->parameters['take'],
					'offset' => $this->parameters['skip'],
					'post__in' => $unreadIds,
					'orderby' => 'post_date',
					'order' => 'desc',
					'post_status'=> 'publish',
					'meta_query' => array(
							array(
									'key' => 'ispushnotification',
									'value' => '1'
							)
					),
					'ignore_sticky_posts' => 1
			));

			$data = array();

			$readIds = array();

			foreach($posts as $post) {
				$data[] = array(
						'id' => $post->ID,
						'title'=> $post->post_title,
						'post_date' => $post->post_date
				);

				$readIds[] = $post->ID;
			}

			// mark as read
			$wpdb->query("UPDATE $table_post_notification SET is_read = 1 WHERE object_id IN (".join(",", $readIds). ") AND token='$device_token';");

			// return
			$this->code = self::$CODES['common']['success'];
			$this->data = $data;
		} else {
			$this->code = self::$CODES['common']['invalid_token'];
		}
	}

}
