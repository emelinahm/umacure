<?php
/**
 * Plugin Name: Push notification
 * Plugin URI: http://umacure.net
 * Description: Perform push notification
 * Version: 1.0
 * Author: NTQ-SOFT
 * Author NTQ-SOFT
 * License: GPLv2 or later
 */
include('APNS_Notification.class.php');
include('GCM_Notification.class.php');
//DEFINE( 'GOOGLE_API_KEY', 'AIzaSyBWROK0SqqH2nWSN2m12BLB886LGsnx7xQ' );
//DEFINE('GOOGLE_API_KEY', 'AIzaSyAEsR1A9jWF7tBPxq81fSrmCJS78O9M2Sw');
//DEFINE('GOOGLE_API_KEY', 'AIzaSyBW4w0eRONFvFNyvrPZ5djdcluS8-o-2aE');
DEFINE('GOOGLE_API_KEY', 'AIzaSyCDi0WBrr6ZMji8Tn4YHXNhPLH2PJuq1yg');

add_action('save_post','push_notification',11,1);
/**
 * Check push notification status
 * 1: Push notification
 * 2: Noting
 * @param unknown $id
 */
function push_notification($id) {
	global $post;

	// Get status push notification from postmeta
	// 1: Pushed
	// 2: None
	$post_meta = get_post_meta($id);
	$is_push = $post_meta['ispushnotification']['0'];
	// Check push status
	// 1: Push to new device
	// 2: None
	if ($is_push == 1) {
		// Execute push notification iOS
		  executeSendiOS($post->post_title, $post->ID);
		// Execute push notfication android
		  executeSendAndroid($post->post_title, $post->ID);
	}
}


/**
 * Push notification for iOS devices
 */
function executeSendiOS($message, $post_id) {
	global $wpdb;
	$table_device = $wpdb->prefix.'devices';
	$table_post_notification = $wpdb->prefix.'post_notifications';

	$notif = new APNS_Notification();

	// Get list device need to push notification
	$devices = $wpdb->get_results('SELECT * FROM '.$table_device.' where type = 0 and notified = 1 and token not in (select token from '.$table_post_notification.' where object_id = '.$post_id.');', OBJECT);
	foreach ($devices as $device) {
		$device_token = $device->token;
		$args = array();
		$args['object_id'] = $post_id;
		$args['token'] = $device_token;
		$args['is_read'] = 0;
                $time = date('Y-m-d H:i:s', time());
                $args['created_at'] = $time;
		
                $wpdb->insert($table_post_notification, $args);
                $badge = get_unread_count($device->token);
                $other_param = array(
                    'article_id' => $post_id
                );
		$notif->sendMessageToDeviceComponent($device_token, $message, $badge, $other_param);
	}

}
/**
 * Push notification for android devices
 */
function executeSendAndroid($message, $post_id) {
	global $wpdb;
	$table_device = $wpdb->prefix.'devices';
	$table_post_notification = $wpdb->prefix.'post_notifications';

	$notif = new GCM_Notification();

	// Get list device need to push notification
	$devices = $wpdb->get_results('SELECT * FROM '.$table_device.' where type = 1 and notified = 1 and token not in (select token from '.$table_post_notification.' where object_id = '.$post_id.');', OBJECT);

	// prep the bundle
	$msg = array(
		'message' => $message,
                'badge'=> 1,
		'vibrate'=> 1,
		'sound'	=> 1,
	);

	foreach ($devices as $device) {
		$registatoin_ids = array($device->token);
		// Insert data to post notification table
		$args = array();
		$args['object_id'] = $post_id;
		$args['token'] = $device->token;
		$args['is_read'] = 0;
                $time = date('Y-m-d H:i:s', time());
                $args['created_at'] = $time;
		$wpdb->insert($table_post_notification, $args);
		// Get list device need to push notification
		//$devices = $wpdb->get_results('SELECT * FROM '.$table_device.' where type = 1 and notified = 1 and token not in (select token from '.$table_post_notification.' where object_id = '.$post_id.');', OBJECT);

                $msg['badge'] = get_unread_count($device->token);
                $msg['article_id'] =  $args['object_id'];
		$notif->send_notification($registatoin_ids, $msg, GOOGLE_API_KEY);
	}
        
}

function get_unread_count($device_token) {
    error_log("\n****** Function get_unread_count :" . $device_token, 3,WP_CONTENT_DIR . '/umacure_debug.log' );
    $count = 0;
    $unreadIds = array();
    global $wpdb;
    $table_device = $wpdb->prefix.'devices';
    $table_post_notification = $wpdb->prefix.'post_notifications';
    
    $unreads = $wpdb->get_results("SELECT object_id FROM $table_post_notification WHERE token = '$device_token' AND is_read = 0;", OBJECT);

    foreach($unreads as $post) {
            $unreadIds[] = (int)$post->object_id;
    }
    if (count($unreadIds) === 0) {
            $count = 1;
    } else {
        foreach ($unreadIds as $value) {
            $meta_values = get_post_meta( ($value + 1), 'ispushnotification' ); 
            error_log("\n $value has ispushnotification is " . json_encode($meta_values), 3,WP_CONTENT_DIR . '/umacure_debug.log' );
            if($meta_values[0] == "1") $count++;
        }
    }
    error_log(json_encode($unreadIds), 3,WP_CONTENT_DIR . '/umacure_debug.log' );
    
    return $count;
}

?>