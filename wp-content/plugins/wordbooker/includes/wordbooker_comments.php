<?php
/*
Extension Name: Wordbooker
Extension URI: http://wordbooker.tty.org.uk
Version: 2.2
Description: Comment Handling Options.
Author: Steve Atty
*/

function wordbooker_poll_comments($userid=0) {
	global  $wpdb, $user_ID,$table_prefix,$blog_id,$comment_user;
	$comment_user=1;
	$wordbooker_settings=get_option('wordbooker_settings');
	$scheds1['Never'] = array('interval' => 3600,);
	$scheds1['Manual'] = array('interval' => 3600,);
	$scheds2=wp_get_schedules();
	$scheds=array_merge($scheds1,$scheds2);
	$trimtime=(strtotime("now")-($scheds[$wordbooker_settings['wordbooker_comment_cron']]["interval"]*3));
	$domain_info=wordbooker_domain_is_private(network_site_url());
	if($domain_info[2]=='bad') {
		$wordbooker_settings['wordbooker_public_url']=6;
		$wordbooker_settings['wordbooker_fake_publish']='on';
		wordbooker_set_option('wordbooker_fake_publish', $wordbooker_settings['wordbooker_fake_publish']);
	} else {
		$wordbooker_settings['wordbooker_public_url']=0;
	}
    if ($wordbooker_settings['wordbooker_public_url']>0) {
		wordbooker_debugger("Non Public URL being used  -Comment handling disabled "," ",-3,9) ;
		return;
	 }

	if (! $wordbooker_settings['wordbooker_comment_handling']) {
		wordbooker_debugger("Comment handling disabled "," ",-3,98) ;
		return;
	 }
	 if ( $wordbooker_settings['wordbooker_comment_handling']=='Never') {
		wordbooker_debugger("Comment handling disabled "," ",-3,98) ;
		return;
	 }
	$sql="Select user_ID,name from ".WORDBOOKER_USERSTATUS." where blog_id=".$blog_id;
	$processed_time=time();
	if ($userid>0) { $sql.=" and user_ID=".$userid;}
	$rows = $wpdb->get_results($sql);
	foreach ($rows as $comment_row) {
		$comment_user=$comment_row->user_ID;
		$perms_missing=wordbooker_permissions_ok($comment_user);
		if ($perms_missing>0) {
			wordbooker_debugger("Permissions incorrect - please reauthenticate "," ",-3,98) ;
			return;
		}
		$sql1='SELECT count(*) as count FROM '.WORDBOOKER_ERRORLOGS.' WHERE timestamp < DATE_SUB(NOW(), INTERVAL '.($scheds[$wordbooker_settings['wordbooker_comment_cron']]["interval"]*3).' SECOND) and blog_id ='.$blog_id.' and post_id=-3';
		$rcount = $wpdb->get_results($sql1);
		sleep(1);
	$sql='DELETE FROM '.WORDBOOKER_ERRORLOGS.' WHERE timestamp < DATE_SUB(NOW(), INTERVAL '.($scheds[$wordbooker_settings['wordbooker_comment_cron']]["interval"]*3).' SECOND) and blog_id ='.$blog_id.' and post_id=-3';
	$result = $wpdb->query($sql);
		sleep(1);
	  //  wordbooker_debugger("Processing your comments ",$sql1,-3,9);
		wordbooker_debugger("Comment logs deleted (older than ".($scheds[$wordbooker_settings['wordbooker_comment_cron']]["interval"]*3).")",$rcount[0]->count,-3,9) ;
		wordbooker_debugger("Processing comments for ".$comment_row->name," ",-3,9) ;
		wordbooker_debugger("Processing your comments "," ",-3,9) ;
		if (!isset($wordbooker_settings['wordbooker_comment_pull']) ) {
			wordbooker_debugger("Starting Incoming comment handling"," ",-3,98);
			$incoming=wordbooker_get_comments_from_facebook($comment_row->user_ID);
			wordbooker_debugger("Incoming comment handling completed"," ",-3,98);
		 }
		else {wordbooker_debugger("Incoming comment handling disabled "," ",-3,98) ; }
		$outgoing=0;
		if (!isset($wordbooker_settings['wordbooker_comment_push']) ) {
			wordbooker_debugger("Starting Outgoing comment handling"," ",-3,98);
			$outgoing=wordbooker_post_comments_to_facebook($comment_row->user_ID);
			wordbooker_debugger("Outgoing comment handling completed"," ",-3,98);
		}
		else {wordbooker_debugger("Outgoing comment handling disabled "," ",-3,98) ; }
		wordbooker_debugger("Completed comment processing for ".$comment_row->name," In : ".$incoming." - Out : ".$outgoing,-3,98) ;
		wordbooker_debugger("Completed your comment processing "," In : ".$incoming." - Out : ".$outgoing,-3,98) ;
	}
	wordbooker_debugger("Comment handling completed "," ",-3,98) ;
}

function wordbooker_post_comments_to_facebook($user_id) {
	global  $wpdb, $user_ID,$table_prefix,$blog_id,$comment_user;
	$processed_posts=0;
	$comment_user=$user_id;
	$wbuser = wordbooker_get_userdata($user_id);
	if (strlen($wbuser->access_token)<20) {
		wordbooker_debugger("No user session for comment handling "," ",-3,98) ;
		return 0;
	}
	// Lets check the token before starting anything
	$at=wordbooker_check_access_token($wbuser->access_token);
	if(!$at->data->is_valid) {
		wordbooker_debugger("Comment Processing failed : Access Token is not valid ",$at->data->error->message,-3,98) ;
		return 0;
	}
	$wordbooker_settings=wordbooker_options();
	$close_comments=get_option('close_comments_for_old_posts');
	$close_days_old=get_option('close_comments_days_old');
	$comment_structure=$wordbooker_settings['wordbooker_comment_post_format'];
	$comment_tag=$wordbooker_settings['wordbooker_comment_attribute'];
	$wordbooker_close=$wordbooker_settings['wordbooker_close_comment'];
	$closed_comment_flag=array('1'=>'Enabled',''=>'Disabled');
	$closedays_ts=strtotime("-".$close_days_old." DAYS");
	$closecomments_ts=strtotime("-".$wordbooker_close." DAYS");
	$closeposts=date("Y-m-d H:i:s", strtotime("-".$wordbooker_close." DAYS"));
	wordbooker_debugger("Auto close comments is set to ".$closed_comment_flag[$close_comments],$close_days_old,-3,80);
	$sql="select distinct wp_post_id,fb_post_id from ".WORDBOOKER_POSTCOMMENTS." where fb_comment_id is null and blog_id=".$blog_id." and user_id=".$user_id." and in_out is null";
	if ($close_comments==1) {$sql.=" and comment_timestamp  > ".$closedays_ts ;}
	if ($wordbooker_close>0) {$sql.=" and comment_timestamp > ".$closecomments_ts;}
	$rows = $wpdb->get_results($sql);
	wordbooker_debugger("Blog posts for comment handling : ".$sql,count($rows),-3,80);
	foreach($rows as $row) {
		wordbooker_debugger("Starting comment handling for WP post ".$row->wp_post_id,$row->fb_post_id,-3,9);
		$wordbooker_post_options = get_post_meta($row->wp_post_id, '_wordbooker_options', true);
		if (!isset($wordbooker_post_options['wordbooker_comment_put'])) {
			wordbooker_debugger("Outgoing comment disabled for WP post ".$row->wp_post_id,$row->fb_post_id,-3,9);;
			continue ;
		}
		$andlogic="";
		if ($wordbooker_close>0) {
			$andlogic=" and wpposts2.post_date > '".$closeposts."'";
			}
		$sql="select distinct comment_ID,comment_date from ".$wpdb->comments." wpcom, ".$wpdb->posts." wpposts2, ".WORDBOOKER_POSTCOMMENTS." wbcom2 where wpcom.comment_post_id=".$row->wp_post_id." and wpcom.comment_approved=1 and wpcom.comment_post_id = wpposts2.ID  and wbcom2.wp_post_id=wpposts2.ID and wbcom2.user_id=".$user_id." and wbcom2.blog_id=".$blog_id." and wbcom2.fb_post_id='".$row->fb_post_id."' and  wpposts2.comment_status='open' and wpcom.comment_id not in (select distinct wp_comment_id from ".WORDBOOKER_POSTCOMMENTS." wbcom, ".$wpdb->posts." wpposts where wbcom.wp_post_id=".$row->wp_post_id." and wbcom.fb_post_id='".$row->fb_post_id."' and wbcom.user_id=".$user_id." and wbcom.wp_post_id = wpposts.ID and  wpposts.comment_status='open')".$andlogic;
		$results = $wpdb->get_results($sql);
		wordbooker_debugger("Comments for processing : ".$sql,count($results),-3,80);
		foreach($results as $result){
			$x=0;
			$comment_content=parse_wordbooker_comment_attributes($result->comment_ID,$comment_structure,$comment_tag);
			try {
				$x=wordbooker_fb_put_comments($row->fb_post_id,$comment_content,$wbuser->access_token);
			}
			catch (Exception $e)
			{
				$error_msg = $e->getMessage();
				$err_no=(integer) substr($error_msg,2,3);
				wordbooker_debugger("Failed to post comment to Facebook : ".$error_msg,$row->fb_post_id,-3,98);
				if ($err_no=100) {
					$sql= $wpdb->prepare("DELETE FROM ".WORDBOOKER_POSTCOMMENTS." where fb_post_id =%d and blog_id=%d", $row->fb_post_id,$blog_id);
					$result = $wpdb->query($sql);
				}
			}
			if (strlen($x->id)>2){
				$sql="insert into ".WORDBOOKER_POSTCOMMENTS." (wp_post_id,fb_post_id,wp_comment_id,fb_comment_id,user_id,blog_id,comment_timestamp,in_out) values (".$row->wp_post_id.",'".$row->fb_post_id."',".$result->comment_ID.",'".$x->id."',".$user_id.",".$blog_id.",'".strtotime($result->comment_date)."','out')";
				wordbooker_debugger("Record comment posting ",$sql,-3,80);
				$wpdb->query($sql);
				wordbooker_debugger("Posting comment to Facebook Post : ".$row->fb_post_id." returns",$x->id,-3,9) ;
				$processed_posts=$processed_posts+1;
			}
		}
		wordbooker_debugger("Finished comment handling for WP post ".$row->wp_post_id,$row->fb_post_id,-3,9);
	}
	return $processed_posts;
}

function wordbooker_get_comments_from_facebook($user_id) {
	global $wpdb,$blog_id,$comment_user;
	$processed_posts=0;
	$comment_user=$user_id;
	$wbuser = wordbooker_get_userdata($user_id);
	if (strlen($wbuser->access_token)<20) {
		wordbooker_debugger("No user session for comment handling "," ",-3,98) ;
		return 0;
	}
	$at=wordbooker_check_access_token($wbuser->access_token);
	if(!$at->data->is_valid) {
		wordbooker_debugger("Comment Processing failed : Access Token is not valid ",$at->data->error->message,-3,98) ;
		return 0;
	}
	$close_comments=get_option('close_comments_for_old_posts');
	$close_days_old=get_option('close_comments_days_old');
	$wordbooker_settings=get_option('wordbooker_settings');
	$wordbooker_close=$wordbooker_settings['wordbooker_close_comment'];
	$comment_approve=0;
	$closedays_ts=strtotime("-".$close_days_old." DAYS");
	$closecomments_ts=strtotime("-".$wordbooker_close." DAYS");
	if (isset($wordbooker_settings['wordbooker_comment_approve'])) {$comment_approve=1;}
	$closed_comment_flag=array('1'=>'Enabled',''=>'Disabled');
	wordbooker_debugger("Auto close comments is set to ".$closed_comment_flag[$close_comments],$close_days_old,-3,80);
	$sql='Select distinct fb_post_id from '.WORDBOOKER_POSTCOMMENTS.' where fb_comment_id is null and user_id='.$user_id.' and blog_id='.$blog_id. " and in_out is null ";
	if ($close_comments==1) {$sql.=" and comment_timestamp  > ".$closedays_ts ;}
	if ($wordbooker_close>0) {$sql.=" and comment_timestamp > ".$closecomments_ts;}
	$rows = $wpdb->get_results($sql);
	wordbooker_debugger("Blog posts with FB Posts against them : ".$sql,count($rows),-3,80);
	foreach ($rows as $fb_comment) {
		wordbooker_debugger("Starting comment handling for FB post ".$fb_comment->fb_post_id,"",-3,9);
		try {
			$all_comments=wordbooker_fb_get_comments($fb_comment->fb_post_id,$wbuser->access_token);
			wordbooker_debugger("Comments pulled from Facebook",count($all_comments->data),-3,9);
		}
		catch (Exception $e)
		{
			$error_msg = $e->getMessage();
			$err_no=(integer) substr($error_msg,2,3);
			wordbooker_debugger("Failed to get comment from Facebook : ".$error_msg,$row->fb_post_id,-3,98);
			if ($err_no=100) {
				$sql= $wpdb->prepare("DELETE FROM ".WORDBOOKER_POSTCOMMENTS." where fb_post_id =%d and blog_id=%d", $row->fb_post_id,$blog_id);
				$result = $wpdb->query($sql);
			}
		}
		if(count($all_comments->data) > 0 ) {
			remove_action('preprocess_comment', array('spam_captcha','check_comment_captcha'));
			remove_action('wp_insert_comment', array('spam_captcha','check_comment_akismet'));
			foreach($all_comments->data as $single_comment) {
				$s = explode("_",$single_comment->id);
				$fb_comid=end($s);
				# Now check that we don't already have this comment in the table as it means we've processed it before (or sent it to FB)
				$sql="Select fb_comment_id from ".WORDBOOKER_POSTCOMMENTS." where fb_comment_id like'%".$fb_comid."'";
				wordbooker_debugger("Checking If comment exists : ".$sql,' ',-3,80);
				$commq=$wpdb->query($sql);
				if(!$commq) {
					wordbooker_debugger("Found new comment for FB post ".$fb_comment->fb_post_id,"from : ".$single_comment->from->name,-3,9);
					$commemail=$wordbooker_settings['wordbooker_comment_email'];
					$time = date("Y-m-d H:i:s",strtotime($single_comment->created_time));
					$current_offset = get_option('gmt_offset');
					$atime = date("Y-m-d H:i:s",strtotime($single_comment->created_time)+(3600*$current_offset));
					$sql="select distinct wp_post_id from ".WORDBOOKER_POSTCOMMENTS." where fb_post_id='".$fb_comment->fb_post_id."'";
					$wp_post_rows = $wpdb->get_results($sql);
					wordbooker_debugger("Blogs posts to send comment to : ".$sql,count($wp_post_rows),-3,80);
					foreach ($wp_post_rows as $wp_post_row) {
						$wordbooker_post_options = get_post_meta($wp_post_row->wp_post_id, '_wordbooker_options', true);
						if (!isset($wordbooker_post_options['wordbooker_comment_get'])) {
							wordbooker_debugger("Incoming comments disabled for WP post ".$wp_post_row->wp_post_id,' ',-3,98);
							continue ;
						}
						$data = array(
							'comment_post_ID' => $wp_post_row->wp_post_id,
							'comment_author' => $single_comment->from->name,
							'comment_author_email' => $commemail,
							'comment_author_url' => 'https://www.facebook.com/'.$single_comment->from->id,
							'comment_content' =>$single_comment->message,
							'comment_author_IP' => '127.0.0.1',
							'comment_date' => $atime,
							'comment_date_gmt' => $time,
							'comment_parent'=> 0,
							'user_id' => 1,
						   	'comment_agent' => 'Wordbooker plugin '.WORDBOOKER_CODE_RELEASE,
							'comment_approved' => $comment_approve,
						);
						$data = apply_filters('preprocess_comment', $data);
						$data['comment_parent'] = isset($data['comment_parent']) ? absint($data['comment_parent']) : 0;
						$parent_status = ( 0 < $data['comment_parent'] ) ? wp_get_comment_status($data['comment_parent']) : '';
						$data['comment_parent'] = ( 'approved' == $parent_status || 'unapproved' == $parent_status ) ? $data['comment_parent'] : 0;
						$newComment= wp_insert_comment($data);
						update_comment_meta($newComment, "fb_uid", $single_comment->from->id);
						update_comment_meta($newComment, "akismet_result", true);
						wordbooker_debugger("Inserted comment from ".$single_comment->from->name." into ".$wp_post_row->wp_post_id." as ".$newComment,"",-3,9);
						$sql="Insert into ".WORDBOOKER_POSTCOMMENTS." (fb_post_id,user_id,comment_timestamp,wp_post_id,blog_id,wp_comment_id,fb_comment_id,in_out) values ('".$fb_comment->fb_post_id."',".$user_id.",".strtotime($single_comment->created_time).",".$wp_post_row->wp_post_id.",".$blog_id.",".$newComment.",'".$single_comment->id."','in' )";
						$commq2=$wpdb->query($sql);
						$processed_posts=$processed_posts+1;
					}
					wordbooker_debugger("Finished comment inserts for FB post ".$fb_comment->fb_post_id,"",-3,9);
				}
			   else {
					wordbooker_debugger("Found existing comment for FB post ".$fb_comment->fb_post_id,"from : ".$single_comment->from->name,-3,9);
				}
			}
		}
		wordbooker_debugger("Finished comment handling for FB post ".$fb_comment->fb_post_id,"",-3,98);
	}
	return $processed_posts;
}

function parse_wordbooker_comment_attributes($comment_id,$comment_structure,$comment_tag) {
	# Changes various "tags" into their WordPress equivalents.
	$comment = get_comment($comment_id);
	$comment_author=$comment->comment_author;
	$comment_date=date_i18n(get_option('date_format'),strtotime($comment->comment_date));
	$comment_time=date_i18n(get_option('time_format'),strtotime($comment->comment_date));
	$comment_content=$comment->comment_content;
	# Now do the replacements
	$comment_structure=str_ireplace( '%author%',$comment_author,$comment_structure );
	$comment_structure=str_ireplace( '%content%',$comment_content,$comment_structure );
	$comment_structure=str_ireplace( '%date%',$comment_date,$comment_structure );
	$comment_structure=str_ireplace( '%time%',$comment_time,$comment_structure );
	$comment_structure=str_ireplace( '%tag%',$comment_tag,$comment_structure );
	return $comment_structure;
}
?>