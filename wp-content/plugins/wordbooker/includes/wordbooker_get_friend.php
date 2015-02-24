<?php
/*
Extension Name: Wordbooker Posting Options
Extension URI: http://wordbooker.tty.org.uk
Version: 2.2
Description: Interface between friend tagger and friends lists in DB
Author: Steve Atty
*/

require_once( '../../../../wp-config.php');
  global $wpdb,$user_ID;
  if (!isset($_GET['match'])) {$_GET['match']='';}
  if (!isset($_GET['name'])) {$_GET['name']='';}
   if (!isset($_GET['userid'])) {$_GET['userid']=-9999;}
  $match=$wpdb->escape($_GET['match']);
  $name=$wpdb->escape($_GET['name']);
  $userid=$wpdb->escape($_GET['userid']);
  if ($userid!=$user_ID) {die('No No No');}
  if (strlen($match)>0){
  $sql=$wpdb->prepare("select name from ".WORDBOOKER_FB_FRIENDS." where name like %s and user_id=%d and list_type='friend'",$match."%",$userid);
  $wb_users = $wpdb->get_results($sql);
  if (is_array($wb_users)) {
	  foreach ($wb_users as $wb_user){
		echo $wb_user->name."#";
		}
	  }
  }
  if (strlen($name)>0){
  $sql=$wpdb->prepare("select facebook_id from ".WORDBOOKER_FB_FRIENDS." where name=%s and user_id=%d and list_type='friend'",$name,$userid);
  $wb_users = $wpdb->get_results($sql);
  if (is_array($wb_users)) {
	  foreach ($wb_users as $wb_user){
			  echo $wb_user->facebook_id;
  }
  }
  }
?>