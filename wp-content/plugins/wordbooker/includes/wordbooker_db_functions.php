<?php
/*
Extension Name: Wordbooker Posting Options
Extension URI: http://wordbooker.tty.org.uk
Version: 2.2
Description: Posting Options for the WordBooker Plugin
Author: Steve Atty
*/
function wordbooker_options() {
	return get_option(WORDBOOKER_SETTINGS);
}

function wordbooker_set_options($options) {
	update_option(WORDBOOKER_SETTINGS, $options);
}

function wordbooker_get_option($key) {
	$options = wordbooker_options();
	return isset($options[$key]) ? $options[$key] : null;
}

function wordbooker_set_option($key, $value) {
	$options = wordbooker_options();
	$options[$key] = $value;
	wordbooker_set_options($options);
}


function wordbooker_delete_user($user_id,$level) {
	global $wpdb,$blog_id;
	$errors = array();
//	$unlinked=wordbooker_get_option("wordbooker_unlink");
//	$andlogic="";
//	if (isset($unlinked)){
	//	$andlogic=' and blog_id='.$blog_id;
//	}
	$andlogic='';
	$table_array[1]=array(WORDBOOKER_USERDATA);
	$table_array[2]=array(WORDBOOKER_USERDATA,WORDBOOKER_USERSTATUS);
	$table_array[3]=array(WORDBOOKER_USERDATA,WORDBOOKER_USERSTATUS,WORDBOOKER_FB_FRIENDS,WORDBOOKER_FB_FRIEND_LISTS);
	foreach ($table_array[$level] as $tablename) {
		if ($tablename==WORDBOOKER_USERDATA ||$tablename==WORDBOOKER_USERSTATUS ) {$result = $wpdb->query('DELETE FROM ' . $tablename . ' WHERE user_ID = ' . $user_id . $andlogic);}
		else
		{$result = $wpdb->query('DELETE FROM ' . $tablename . ' WHERE user_ID = ' . $user_id . $andlogic);}
	}
	if ($errors) {
		echo '<div id="message" class="updated fade">' . "\n";
		foreach ($errors as $errormsg) {
			_e("$errormsg<br />\n", 'wordbooker');
		}
		echo "</div>\n";
	}
}

function wordbooker_get_userdata($user_id) {
	global $wpdb,$blog_id;
	if (!isset($user_id)) {return null;}
	$sql=$wpdb->prepare("SELECT onetime_data,facebook_error,secret,session_key,user_ID,access_token,facebook_id,pages,name FROM " . WORDBOOKER_USERDATA . " WHERE user_ID =%d",$user_id) ;
	//$unlinked=wordbooker_get_option("wordbooker_unlink");
//	if (isset($unlinked)){
//		$sql='SELECT onetime_data,facebook_error,secret,session_key,user_ID,access_token,facebook_id,pages,name FROM ' . WORDBOOKER_USERDATA . ' WHERE user_ID = ' . $user_id . ' and blog_id='.$blog_id;
//	}
	$rows = $wpdb->get_results($sql);
	if ($rows) {
		$rows[0]->onetime_data = unserialize($rows[0]->onetime_data);
		$rows[0]->facebook_error = unserialize($rows[0]->facebook_error);
		$rows[0]->secret = unserialize($rows[0]->secret);
		$rows[0]->session_key = unserialize($rows[0]->session_key);
		$rows[0]->access_token = unserialize($rows[0]->access_token);
		$rows[0]->pages = unserialize($rows[0]->pages);
		return $rows[0];
	}
	return null;
}

function wordbooker_set_userdata($onetime_data, $facebook_error,$secret, $session,$facebook_id) {
	global $user_ID, $wpdb,$blog_id;
	wordbooker_delete_userdata();
	$sql= $wpdb->prepare("INSERT INTO " . WORDBOOKER_USERDATA . " (user_ID, onetime_data, facebook_error, secret, session_key, uid, expires, access_token, sig,blog_id,facebook_id	) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,%d,%s)", $user_ID, serialize($onetime_data), serialize($facebook_error),  serialize($secret),  serialize($session->session_key), serialize($session->uid), serialize($session->expires), serialize($session->access_token), serialize($session->sig), $blog_id,$facebook_id);
	$result = $wpdb->query($sql);
}

function wordbooker_set_userdata2( $onetime_data, $facebook_error, $secret, $session_key,$user_ID) {
	global $wpdb,$blog_id;
	$sql= $wpdb->prepare("Update " . WORDBOOKER_USERDATA . " set onetime_data = %s, facebook_error = %s	, secret = %s, session_key =%s where user_id=%d",serialize($onetime_data) , serialize($facebook_error),serialize($secret),serialize($session_key) ,$user_ID);
	$result = $wpdb->query($sql);
}

function wordbooker_update_userdata($wbuser) {
	return wordbooker_set_userdata2( $wbuser->onetime_data, $wbuser->facebook_error, $wbuser->secret, $wbuser->session_key,$wbuser->user_ID);
}

function wordbooker_remove_user(){
	global $user_ID;
	# Delete the user's meta
	$wordbooker_user_settings_id="wordbookuser".$blog_id;
	delete_usermeta( $user_ID, $wordbooker_user_settings_id);
	# Then go and delete their data from the tables
	wordbooker_delete_user($user_ID,3);
}

function wordbooker_delete_userdata() {
	global $user_ID;
	wordbooker_delete_user($user_ID,2);
}

function wordbooker_trim_errorlogs() {
	global $user_ID, $wpdb,$blog_id;
	$sql=$wpdb->prepare('DELETE FROM ' . WORDBOOKER_ERRORLOGS . ' WHERE timestamp < DATE_SUB(CURDATE(), INTERVAL 2 DAY)  and blog_id =%d',$blog_id);
	$result = $wpdb->query($sql);
}

function wordbooker_clear_errorlogs() {
	global $user_ID, $wpdb,$blog_id;
	$sql=$wpdb->prepare('DELETE FROM ' . WORDBOOKER_ERRORLOGS . ' WHERE user_ID = %d and error_code > -1  and blog_id =%d',$user_ID,$blog_id);
	$result = $wpdb->query($sql);
	if ($result === false) {
		echo '<div id="message" class="updated fade">';
		_e('Failed to clear error logs.', 'wordbooker');
		echo "</div>\n";
	}
}

function wordbooker_clear_diagnosticlogs() {
	global $user_ID, $wpdb,$blog_id;
	$sql=$wpdb->prepare('DELETE FROM ' . WORDBOOKER_ERRORLOGS . ' WHERE blog_id =%d and user_ID=%d',$blog_id,$user_ID);
	$result = $wpdb->query($sql);
	if ($result === false) {
		echo '<div id="message" class="updated fade">';
		_e('Failed to clear Diagnostic logs.', 'wordbooker');
		echo "</div>\n";
	}
}
function wordbooker_append_to_errorlogs($method, $error_code, $error_msg,$post_id,$user_id) {
	global $user_ID, $wpdb,$blog_id;
	if ($post_id == null) {
		$post_id = 0;
	}
	$level=900;
	$sql=$wpdb->prepare("INSERT INTO " . WORDBOOKER_ERRORLOGS . " (user_id, method, error_code, error_msg, post_id, blog_id, diag_level) VALUES (%d,%s,%d,%s,%d,%d,%d)",$user_id ,$method,$error_code,$error_msg,$post_id,$blog_id,$level);
	$result = $wpdb->query($sql);
}

function wordbooker_delete_from_errorlogs($post_id) {
	global $wpdb,$blog_id;
	$sql=$wpdb->prepare('DELETE FROM ' . WORDBOOKER_ERRORLOGS . ' WHERE post_id = %d and blog_id =%d',$post_id,$blog_id );
	$result = $wpdb->query($sql);
}


function wordbooker_trim_postlogs() {
	# Forget that something has been posted to Facebook if it's been there  more than a year.
	global $wpdb;
	$result = $wpdb->query('DELETE FROM ' . WORDBOOKER_POSTLOGS . ' WHERE timestamp < DATE_SUB(CURDATE(), INTERVAL 365 DAY)');
}


function wordbooker_insert_into_postlogs($post_id,$blog_id) {
	global $wpdb;
	wordbooker_delete_from_postlogs($post_id,$blog_id);
	if (!WORDBOOKER_TESTING) {
		$sql=$wpdb->prepare("INSERT INTO ".WORDBOOKER_POSTLOGS." (post_id,blog_id) VALUES (%d,%d)",$post_id ,$blog_id);
		$result = $wpdb->query($sql);
	}
}

function wordbooker_insert_into_process_queue($post_id,$blog_id,$entry_type) {
	global $wpdb;
	$sql = $wpdb->prepare( 'INSERT INTO '.WORDBOOKER_PROCESS_QUEUE.' (entry_type,blog_id,post_id,status) VALUES (%s,%d,%d,"B")',$entry_type,$blog_id ,$post_id);
	$result = $wpdb->query($sql);
}

function wordbooker_delete_from_process_queue($post_id,$blog_id) {
	global $wpdb,$blog_id;
	$sql= $wpdb->prepare("DELETE FROM  ".WORDBOOKER_PROCESS_QUEUE." where post_id=%d and blog_id=%d", $post_id,$blog_id);
	$result = $wpdb->query($sql);
}

function wordbooker_delete_from_postlogs($post_id,$blog_id) {
	global $wpdb,$blog_id;
	$sql= $wpdb->prepare("DELETE FROM ".WORDBOOKER_POSTLOGS." where post_id=%d and blog_id=%d", $post_id,$blog_id);
	$result = $wpdb->query($sql);
}

function wordbooker_delete_from_commentlogs($post_id,$blog_id) {
	global $wpdb;
	$sql= $wpdb->prepare("DELETE FROM ".WORDBOOKER_POSTCOMMENTS." where wp_post_id =%d and blog_id=%d", $post_id,$blog_id);
	$result = $wpdb->query($sql);
}

function wordbooker_delete_comment_from_commentlogs($comment_id,$blog_id) {
	global $wpdb;
	$results = print_r($comment_id, true);
	wordbooker_debugger("deleting comment: ".$results,' ',-4,99);
	$sql= $wpdb->prepare("DELETE FROM ".WORDBOOKER_POSTCOMMENTS." where wp_comment_id =%d and blog_id=%d", $comment_id,$blog_id);
	$result = $wpdb->query($sql);;
}
?>