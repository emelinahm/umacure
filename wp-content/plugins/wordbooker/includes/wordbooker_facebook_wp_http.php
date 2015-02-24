<?php
/*
Extension Name: Wordbooker Posting Options
Extension URI: http://wordbooker.tty.org.uk
Version: 2.2
Description: Interface calls for the wp_http interface.
Author: Steve Atty
*/

function wordbooker_fb_stream_publish($data,$target) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $data['access_token'], $app_secret);
	$url='https://graph.facebook.com/'.$target.'/feed?appsecret_proof='.$appsecret_proof;
	$x=wordbooker_make_http_post_call($url,$data);
	return($x);
}

function wordbooker_fb_action_publish($data,$target) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $data['access_token'], $app_secret);
	$url='https://graph.facebook.com/'.$target.'/news.publishes?appsecret_proof='.$appsecret_proof;
	$data['fb:explicitly_shared'] = 'true';
	$data['article'] = $data['link'];

	$x=wordbooker_make_http_post_call($url,$data);
	return($x);
}

function wordbooker_fb_status_update($data,$target) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $data['access_token'], $app_secret);
	$url='https://graph.facebook.com/'.$target.'/feed?appsecret_proof='.$appsecret_proof;
	$x=wordbooker_make_http_post_call($url,$data);
    return($x);
}

function wordbooker_fb_link_publish($data,$target) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $data['access_token'], $app_secret);
	$url='https://graph.facebook.com/'.$target.'/links?appsecret_proof='.$appsecret_proof;
	$x=wordbooker_make_http_post_call($url,$data);
    return($x);
}

function wordbooker_fb_note_publish($data,$target){
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $data['access_token'], $app_secret);
	$url='https://graph.facebook.com/'.$target.'/notes?appsecret_proof='.$appsecret_proof;
	$x=wordbooker_make_http_post_call($url,$data);
    return($x);
}

function wordbooker_fql_query($query,$access_token) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
	$url = 'https://api.facebook.com/method/fql.query?&query='.rawurlencode($query).'&format=JSON-STRINGS&access_token='.$access_token.'&appsecret_proof='.$appsecret_proof.'&format=JSON-STRINGS';
	$x=wordbooker_make_http_call($url);
    return($x);
}

function wordbooker_me($access_token) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
    $url = 'https://graph.facebook.com/me/accounts?access_token='.$access_token.'&appsecret_proof='.$appsecret_proof.'&format=JSON-STRINGS';
	$x=wordbooker_make_http_call($url);
    return($x);
}

function wordbooker_me_groups($uid,$access_token) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
    $url = 'https://graph.facebook.com/'.$uid.'/groups?access_token='.$access_token.'&appsecret_proof='.$appsecret_proof.'&format=JSON-STRINGS';
	$x=wordbooker_make_http_call($url);
    return($x);
}

function wordbooker_me_albums($uid,$access_token) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
    $url = 'https://graph.facebook.com/'.$uid.'/albums?access_token='.$access_token.'&appsecret_proof='.$appsecret_proof.'&format=JSON-STRINGS';
	$x=wordbooker_make_http_call($url);
    return($x);
}

function wordbooker_upload_photo($data,$target) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
    $url = 'https://graph.facebook.com/'.$target.'/photos?appsecret_proof='.$appsecret_proof;
	$x=wordbooker_make_http_post_call($url,$data);
    return($x);
}

function wordbooker_get_fb_id($fb_id,$access_token) {
	if (!isset($fb_id)){$fb_id='me';}
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
    $url = 'https://graph.facebook.com/'.$fb_id.'?fields=id,name,link&access_token='.$access_token.'&appsecret_proof='.$appsecret_proof.'&format=JSON-STRINGS';
	$x=wordbooker_make_http_call($url);
    return($x);
}

function wordbooker_me_status($fb_id,$access_token) {
	if (!isset($fb_id)){$fb_id='me';}
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
    $url = 'https://graph.facebook.com/'.$fb_id.'?access_token='.$access_token.'&appsecret_proof='.$appsecret_proof.'&format=JSON-STRINGS';
	$x=wordbooker_make_http_call($url);
    return($x);
}

function wordbooker_friend_lists($fb_id,$access_token) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
     $url = 'https://graph.facebook.com/'.$fb_id.'?fields=friendlists.limit(1000)&access_token='.$access_token.'&appsecret_proof='.$appsecret_proof.'&format=JSON-STRINGS';
		$x=wordbooker_make_http_call($url);
    return($x);
}

function wordbooker_friends($access_token,$flid) {
 	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
     $url = 'https://graph.facebook.com/'.$flid.'/members?access_token='.$access_token.'&appsecret_proof='.$appsecret_proof.'&format=JSON-STRINGS';
     if ($flid==-100) {
		$url = 'https://graph.facebook.com/me/friends?access_token='.$access_token.'&appsecret_proof='.$appsecret_proof.'&format=JSON-STRINGS';
    }
	$x=wordbooker_make_http_call($url);
    return($x);
}

function wordbooker_delete_fb_post($fb_post_id,$access_token){
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
	$url='https://graph.facebook.com/'.$fb_post_id.'?method=delete&access_token='.$access_token.'&appsecret_proof='.$appsecret_proof.'&format=JSON-STRINGS';
	$x=wordbooker_make_http_call($url);
        return($x);
}

function wordbooker_get_access_token($access_token) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$secret=WORDBOOKER_SETTINGS_HEX; } else {$secret=WORDBOOKER_FB_SECRET;}
    $url='https://graph.facebook.com/oauth/access_token?client_id='.WORDBOOKER_FB_ID.'&client_secret='.$secret.'&grant_type=fb_exchange_token&fb_exchange_token='.$access_token;
	$x=wordbooker_make_http_call2($url);
   // wordbooker_debugger("Access token returns ",$x,-5,98) ;
	return($x);
}
function wordbooker_get_access_token_from_code($code) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$secret=WORDBOOKER_SETTINGS_HEX; } else {$secret=WORDBOOKER_FB_SECRET;}
    $url='https://graph.facebook.com/oauth/access_token?client_id='.WORDBOOKER_FB_ID.'&client_secret='.$secret.'&code='.$code.'&redirect_uri='.urlencode(get_bloginfo('wpurl')).'/wp-admin/options-general.php?page=wordbooker';
	$x=wordbooker_make_http_call2($url);
   // wordbooker_debugger("Access token returns ",$x,-5,98) ;
	return($x);
}

function wordbooker_check_access_token($access_token) {
	if (!defined('WORDBOOKER_FB_ACCESS_TOKEN')) {$access='254577506873|szBVgLKb2hvtvSkMeSMTkaPnGFM'; } else {$access=WORDBOOKER_FB_ACCESS_TOKEN;}
	 $url='https://graph.facebook.com/debug_token?input_token='.$access_token.'&access_token='.$access.'&format=JSON-STRINGS';
	 try {
	$x=wordbooker_make_http_call($url);
	}
	catch (Exception $e) {
		$x = $e;
	}
	return($x);
}

function wordbooker_check_version() {
	$version=explode(" ",WORDBOOKER_CODE_RELEASE);
	$url='https://wordbooker.tty.org.uk/check_ver.cgi?ver='.urlencode($version[0])."&blog=".urlencode(network_site_url())."&fbaid=".WORDBOOKER_FB_ID."&wpkey=".WORDBOOKER_KEY;
	$x=wordbooker_make_http_call2($url);
	return($x);
}

function wordbooker_status_feed($fb_id,$access_token) {
	if (!isset($fb_id)){$fb_id='me';}
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
    $url = 'https://graph.facebook.com/'.$fb_id.'/feed/?access_token='.$access_token.'&appsecret_proof='.$appsecret_proof.'&format=JSON-STRINGS&limit=10';
	$x=wordbooker_make_http_call($url);
    return($x);
}

function wordbooker_fb_pemissions($fb_id,$access_token) {
	if (!isset($fb_id) || $fb_id<1000 ){$fb_id='me';}
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
    $url = 'https://graph.facebook.com/'.$fb_id.'/permissions?access_token='.$access_token.'&appsecret_proof='.$appsecret_proof.'&format=JSON-STRINGS';
	$x=wordbooker_make_http_call($url);
    return($x);
}

function wordbooker_fb_get_comments($fb_id,$access_token) {
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
    $url = 'https://graph.facebook.com/'.$fb_id.'/comments?access_token='.$access_token.'&appsecret_proof='.$appsecret_proof;
	$x=wordbooker_make_http_call($url);
        return($x);
}

function wordbooker_fb_put_comments($fb_id,$comment,$access_token) {
    $url = 'https://graph.facebook.com/'.$fb_id.'/comments';
	$data['message']=$comment;
	$data['access_token']=$access_token;
	if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret=WORDBOOKER_SETTINGS_HEX; } else {$app_secret=WORDBOOKER_FB_SECRET;}
	$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
	$data['appsecret_proof']=$appsecret_proof;
	$x=wordbooker_make_http_post_call($url,$data);
    return($x);
}

function wordbooker_fb_get_box_comments($url) {
  	$url = 'https://graph.facebook.com/comments?ids='.$url;
	$x=wordbooker_make_http_call($url);
    return($x);
}
function wordbooker_fb_create_event($fb_id,$event_data,$access_token) {
    $url = 'https://graph.facebook.com/'.$fb_id.'?access_token='.$access_token;
	$event_data = array(
	    'name'          => 'Event: ' . date("H:m:s"),
	    'start_time'    => time() + 60*60,
	    'end_time'      => time() + 60*60*2,
	    'owner'         => $page
	);
	$x=wordbooker_make_http_post_call($url,$data);
    return($x);
}

function wordbooker_make_http_call($url) {
	global $wp_version;
	$params = array(
			'redirection' => 0,
			'httpversion' => '1.1',
			'timeout' => 60,
             'user-agent' => apply_filters( 'http_headers_useragent', 'WordPress/' . $wp_version . ';  wordbooker-' . WORDBOOKER_CODE_RELEASE ),
			'headers' => array( 'Connection' => 'close' , 'Content-type' => 'application/x-www-form-urlencoded'),
			'sslverify' => false
	);
		$response = wp_remote_get( $url, $params );
		if ( is_wp_error($response) )
		{
		$error_string = $response->get_error_message();
		 throw new Exception($error_string); return;
		}
		$x=json_decode($response['body']);
		  if (isset($x->error_msg)) {
		  $error=$x->error_msg;}
		  if (isset($x->error->message)) {
		  $error=$x->error->message;}
		  if (isset($error)) {
			  throw new Exception($error);
		  }
//var_dump($x);
		return $x;
}

function wordbooker_make_http_call2($url) {
	global $wp_version;
	$params = array(
			'redirection' => 0,
			'httpversion' => '1.1',
			'timeout' => 60,
             'user-agent' => apply_filters( 'http_headers_useragent', 'WordPress/' . $wp_version . ';  wordbooker-' . WORDBOOKER_CODE_RELEASE ),
			'headers' => array( 'Connection' => 'close' , 'Content-type' => 'application/x-www-form-urlencoded'),
			'sslverify' => false
	);
		$response = wp_remote_get( $url, $params );
		if ( is_wp_error($response) )
		{
		$error_string = $response->get_error_message();
		 throw new Exception($error_string); return;
		}
		$x=json_decode($response['body']);
		  if (isset($x->error_msg)) {
		  $error=$x->error_msg;}
		  if (isset($x->error->message)) {
		  $error=$x->error->message;}
		  if (isset($error)) {
			  throw new Exception($error);
		  }
		  if (is_null($x)) {$x=$response['body'];}
	//	  var_dump($x);
		return $x;
}

function wordbooker_make_http_post_call($url,$data) {
	global $wp_version;
	$params = array(
			'redirection' => 0,
			'httpversion' => '1.1',
			'timeout' => 60,
			'user-agent' => apply_filters( 'http_headers_useragent', 'WordPress/' . $wp_version . ';  wordbooker-' . WORDBOOKER_CODE_RELEASE ),
			'headers' => array( 'Connection' => 'close' , 'Content-type' => 'application/x-www-form-urlencoded'),
			'sslverify' => false, // warning: might be overridden by 'https_ssl_verify' filter
			'body' => http_build_query( $data, '', '&' )
		);

		$response = wp_remote_post( $url, $params );
				if ( is_wp_error($response) )
		{
		$error_string = $response->get_error_message();
		 throw new Exception($error_string); return;
		}
		$response=json_decode($response['body']);
		//var_dump($response);
	if (isset($response->error->message)) {
		throw new Exception ($response->error->message);
	}
		return $response;
}
?>