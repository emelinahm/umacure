<?php
/*
Extension Name: Wordbooker
Extension URI: http://wordbooker.tty.org.uk
Version: 2.2
Description: Core Posting Functions
Author: Steve Atty
*/

function wordbooker_wall_post($post_id,$access_token,$post_title,$post_data,$target_id,$dummy,$target_name,$wpuserid,$fb_uid){
	if (strlen($dummy)>0) {
		wordbooker_debugger("Wall Post to ".$target_name." Test Only",'No Post Made',$post_id,90) ;
		return;
	}
	$post_data['access_token']=$access_token;
	global $user_ID;
try {
		$result = wordbooker_fb_stream_publish($post_data,$target_id);
		wordbooker_debugger("Wall Post to ".$target_name." Succeeded - result : ",$result->id,$post_id,90) ;
		wordbooker_store_post_result($post_id,$result->id,$wpuserid,$fb_uid,$target_id);
		//	$result = wordbooker_fb_action_pubish($post_data,$target_id);
	//	wordbooker_debugger("Action Post to ".$target_name." Succeeded - result : ",$result->id,$post_id,90) ;
	    }
	catch (Exception $e) {
		$error_code = $e->getCode();
		$error_msg = $e->getMessage();
		wordbooker_append_to_errorlogs($method, $error_code, $error_msg,$post_id,$user_ID);
		wordbooker_debugger("Wall Post to ".$target_name." Failed : ",$error_msg,$post_id,99) ;
	}
}

function wordbooker_tag_post($post_id,$access_token,$post_title,$post_data,$target_id,$dummy,$target_name,$wpuserid,$fb_uid){
	if (strlen($dummy)>0) {
	      wordbooker_debugger("Tag Post to ".$target_name." - Test Only",'No Post Made',$post_id,90) ;
	      return;
	}
	$post_data['access_token']=$access_token;
	global $user_ID;
	try {
		$result = wordbooker_fb_stream_publish($post_data,$target_id);
		wordbooker_store_post_result($post_id,$result->id,$wpuserid,$fb_uid,$target_id);
		wordbooker_debugger("Tag Post to ".$target_name." Succeeded - result : ",$result->id,$post_id,90) ;
	    }
	catch (Exception $e) {
		$error_code = $e->getCode();
		$error_msg = $e->getMessage();
		wordbooker_append_to_errorlogs($method, $error_code, $error_msg,$post_id,$user_ID);
		wordbooker_debugger("Tag Post to ".$target_name." Failed : ",$error_msg,$post_id,99) ;
	}
}

function wordbooker_link_post($post_id,$access_token,$post_title,$post_data,$target_id,$dummy,$target_name,$wpuserid,$fb_uid){
	if (strlen($dummy)>0) {
		wordbooker_debugger("Link Post to ".$target_name." Test Only",'No Post Made',$post_id,90) ;
		return;
	}
	$post_data2['message']=$post_data['message'];
	$post_data2['link']=$post_data['link'];
	$post_data2['access_token']=$access_token;
	global $user_ID;
try {
		$result = wordbooker_fb_link_publish($post_data2,$target_id);
		wordbooker_store_post_result($post_id,$result->id,$wpuserid,$fb_uid,$target_id);
		wordbooker_debugger("Link Post to ".$target_name." Succeeded - result : ",$result->id,$post_id,90) ;
	    }
	catch (Exception $e) {
		$error_code = $e->getCode();
		$error_msg = $e->getMessage();
		wordbooker_append_to_errorlogs($method, $error_code, $error_msg,$post_id,$user_ID);
		wordbooker_debugger("Link Post to ".$target_name." Failed : ",$error_msg,$post_id,99) ;
	}
}
function wordbooker_status_update($post_id,$access_token,$post_date,$target_id,$dummy,$target_name,$wpuserid,$fb_uid) {
	global $wordbooker_post_options,$user_ID;
	wordbooker_debugger("Setting status_text".$wordbooker_post_options['wordbooker_status_update_text']," ",$post_id) ;
	if (strlen($wordbooker_post_options['wordbooker_status_update_text'])< 4) {
		wordbooker_debugger("Status update text is too short",$post_id,90) ;
		return;
	}
	if (strlen($dummy)>0) {
		wordbooker_debugger("Status update to ".$target_name." Test Only",'No Post Made',$post_id,90) ;
		return;
	}
	$status_text = parse_wordbooker_attributes(stripslashes($wordbooker_post_options['wordbooker_status_update_text']),$post_id,strtotime($post_date));
	$status_text = wordbooker_post_excerpt($status_text,420);
	$data=array( 'access_token'=>$access_token,'message' =>$status_text);
	try {
		$result = wordbooker_fb_status_update($data,$target_id);
		wordbooker_store_post_result($post_id,$result->id,$wpuserid,$fb_uid,$target_id);
		wordbooker_debugger("Status update  to ".$target_name." Succeeded result : ",$result->id,$post_id,90) ;
	    }
	catch (Exception $e) {
		$error_code = $e->getCode();
		$error_msg = $e->getMessage();
		wordbooker_append_to_errorlogs($method, $error_code, $error_msg,$post_id,$user_ID);
		wordbooker_debugger("Status Update  to ".$target_name." failed : ".$error_msg,$post_id,99) ;
	}
}

function wordbooker_notes_post($post_id,$access_token,$post_title,$target_id,$dummy,$target_name,$wpuserid,$fb_uid){
	if (strlen($dummy)>0) {
		wordbooker_debugger("Notes publish  to ".$target_name." Test Only",'No Post Made',$post_id,90) ;
		return;
	}
	global $post,$user_ID;
	$excerpt=apply_filters('the_content', $post->post_content);
	$open_tags="[simage,[[CP,[gallery,[imagebrowser,[slideshow,[tags,[albumtags,[singlepic,[album,[contact-form,[contact-field,[/contact-form,<strong>Google+:</strong>";
	$close_tags="],]],],],],],],],],],],],Daniel Treadwell</a>.</i>";
	$open_tag=explode(",",$open_tags);
	$close_tag=explode(",",$close_tags);
	foreach (array_keys($open_tag) as $key) {
		if (preg_match_all('!' . preg_quote($open_tag[$key]) . '(.*?)' . preg_quote($close_tag[$key]) .'!i',$excerpt,$matches)) {
			$excerpt=str_replace($matches[0],"" , $excerpt);
		 }
	}
	$excerpt = preg_replace('#(<wpg.*?>).*?(</wpg2>)#', '$1$2', $excerpt);
	$data=array(
		'access_token'=>$access_token,
		'message' => preg_replace("/<script.*?>.*?<\/script>/xmsi","",$excerpt),
		'subject' =>$post_title
	);
	try {
		$result = wordbooker_fb_note_publish($data,$target_id);
		wordbooker_store_post_result($post_id,$result->id,$wpuserid,$fb_uid,$target_id);
		wordbooker_debugger("Note Publish to ".$target_name." result : ",$result->id,$post_id,90) ;
	}
	catch (Exception $e) {
		$error_code = $e->getCode();
		$error_msg = $e->getMessage();
		wordbooker_append_to_errorlogs($method, $error_code, $error_msg,$post_id,$user_ID);
		wordbooker_debugger("Notes publish  to ".$target_name." fail : ".$error_msg,$error_code,$post_id,99) ;
	}
}

function wordbooker_photo_post($post_id,$access_token,$post_title,$post_data,$target_id,$dummy,$target_name,$wpuserid,$fb_uid){
	if (strlen($dummy)>0) {
		wordbooker_debugger("Photo Post to ".$target_name." Test Only",'No Post Made',$post_id,90) ;
		return;
	}
	$post_data['access_token']=$access_token;
	global $user_ID;
	try {
		$result = wordbooker_upload_photo($post_data,$target_id);
		wordbooker_debugger("Photo Post to ".$target_name." Succeeded - result : ",$result->id,$post_id,90) ;
		wordbooker_store_post_result($post_id,$result->id,$wpuserid,$fb_uid,$target_id);
	    }
	catch (Exception $e) {
		$error_code = $e->getCode();
		$error_msg = $e->getMessage();
		wordbooker_append_to_errorlogs($method, $error_code, $error_msg,$post_id,$user_ID);
		wordbooker_debugger("Wall Post to ".$target_name." Failed : ",$error_msg,$post_id,99) ;
	}
}

function wordbooker_store_post_result($post_id,$fb_post_id,$wpuserid,$fb_uid,$target_id) {
	global $wpdb,$blog_id,$user_ID;
	$tstamp=time();
	$wordbooker_settings = wordbooker_options();
	$sql= $wpdb->prepare('INSERT INTO '.WORDBOOKER_POSTCOMMENTS.' (fb_post_id,comment_timestamp,wp_post_id,blog_id,user_id,fb_user_id,fb_target_id) VALUES (%s,%d,%d,%d,%d,%d,%d)',$fb_post_id,$tstamp,$post_id,$blog_id,$wpuserid,$fb_uid,$target_id);
	wordbooker_debugger(" Store SQL : ",$sql,$post_id,99);
	$result = $wpdb->query($sql);
	wordbooker_insert_into_postlogs($post_id,$blog_id);
	# Clear down the diagnostics for this post if the user has chosen so
	if (isset($wordbooker_settings['wordbooker_clear_diagnostic'])){
	$result = $wpdb->query(' DELETE FROM '.WORDBOOKER_ERRORLOGS.' WHERE   blog_id ='.$blog_id.' and post_id='.$post_id.' and (error_message not like "(%_%)" and method not like "% - result")'); }
	# Now Change the publish flag for this post to mark it as published.
	$wb_params=get_post_meta($post_id, '_wordbooker_options', true);
	$wb_params["wordbooker_publish_default"]='published';
	update_post_meta($post_id, '_wordbooker_options', $wb_params);
}
?>