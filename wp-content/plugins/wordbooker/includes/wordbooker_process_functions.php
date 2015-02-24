<?php

/*
Extension Name: Wordbooker Posting Options
Extension URI: http://wordbooker.tty.org.uk
Version: 2.2
Description: Posting Options for the WordBooker Plugin
Author: Steve Atty
*/

 function wordbooker_publish($post_id) {
	global $user_ID, $user_identity, $user_login, $wpdb, $blog_id,$wordbooker_settings,$wb_user_id;
	$post = get_post($post_id);
	if ((isset($user_ID) && $user_ID>0) &&  (!current_user_can(WORDBOOKER_MINIMUM_ADMIN_LEVEL))) { wordbooker_debugger("This user doesn't have enough rights"," ",$post_id,99) ; return; }
	wordbooker_debugger("Commence Publish "," ",$post_id,99) ;
	$wordbooker_settings = wordbooker_options();
	# If there is no user row for this user then set the user id to the default author. If the default author is set to 0 (i.e current logged in user) then only blog level settings apply.
	$wb_user_id=$post->post_author;
	# Put a check in here so that we can allow the EDIT user's preferences to over-ride the AUTHOR settings
	if (wordbooker_get_userdata($user_ID) && (isset($wordbooker_settings["wordbooker_override_author"]))) {
	$wb_user_id=$user_ID;}
	if (! wordbooker_get_userdata($wb_user_id)) { $wb_user_id=$wordbooker_settings["wordbooker_default_author"];}
	if  ($wordbooker_settings["wordbooker_default_author"] == 0 ) {$wb_user_id=$wb_user_id;} else {$wb_user_id=$wordbooker_settings["wordbooker_default_author"];}
	# If we've no FB user associated with this ID and the blog owner hasn't overridden then we give up.
	if ((! wordbooker_get_userdata($wb_user_id))  && ( !isset($wordbooker_settings['wordbooker_publish_no_user'])))  { wordbooker_debugger("Not a WB user (".$post->post_author.") and no overide - give up "," ",$post_id,99) ; return;}
	if ((! wordbooker_get_userdata($wb_user_id))  && ( !isset($wordbooker_settings['wordbooker_publish_no_user'])))  {wordbooker_debugger("Author (".$post->post_author.") not a WB user and no overide- give up "," ",$post_id,99) ;  return;}
	if ($_POST["wordbooker_default_author"]== 0 ) { wordbooker_debugger("Author of this post is the Post Author"," ",$post->ID,80);  $_POST["wordbooker_default_author"]=$post->post_author; }
	wordbooker_debugger("Options Set - call transition  "," ",$post_id,80) ;
	$retcode=wordbooker_publish_action($post_id);
	return $retcode;
}

function wordbooker_process_post_data($newstatus, $oldstatus, $post) {
	if (!isset($_POST)){return;}
	global $user_ID, $user_identity, $user_login, $wpdb, $blog_id,$wordbooker_settings,$wb_user_id;;
	# If this is an autosave or its an attachment then we give up and return as otherwise we lose user settings.
    if (!isset($_POST['action'])) { $_POST['action']="fruitbat";}
    if ($_POST['action']=='autosave') { return;}
    if ($post->post_type=='attachment') {return;}
    # If this is a password protected post we give up
	if ($post->post_password != '') {return;}
	if ((isset($user_ID) && $user_ID>0) &&  (!current_user_can(WORDBOOKER_MINIMUM_ADMIN_LEVEL))) { wordbooker_debugger("This user doesn't have enough rights"," ",$post_id,99) ; return; }
	$wordbooker_settings = wordbooker_options();
	# If there is no user row for this user then set the user id to the default author. If the default author is set to 0 (i.e current logged in user) then only blog level settings apply.
	$wb_user_id=$post->post_author;
	# Put a check in here so that we can allow the EDIT user's preferences to over-ride the AUTHOR settings
	if (wordbooker_get_userdata($user_ID) && (isset($wordbooker_settings["wordbooker_override_author"]))) {
	$wb_user_id=$user_ID;}
	if (! wordbooker_get_userdata($wb_user_id)) { $wb_user_id=$wordbooker_settings["wordbooker_default_author"];}
	if  ($wordbooker_settings["wordbooker_default_author"] == 0 ) {$wb_user_id=$wb_user_id;} else {$wb_user_id=$wordbooker_settings["wordbooker_default_author"];}
	# If we've no FB user associated with this ID and the blog owner hasn't overridden then we give up.
	if ((! wordbooker_get_userdata($wb_user_id))  && ( !isset($wordbooker_settings['wordbooker_publish_no_user'])))  { wordbooker_debugger("Not a WB user (".$post->post_author.") and no overide - give up "," ",$post_id,99) ; return;}
	if ((! wordbooker_get_userdata($wb_user_id))  && ( !isset($wordbooker_settings['wordbooker_publish_no_user'])))  {wordbooker_debugger("Author (".$post->post_author.") not a WB user and no overide- give up "," ",$post_id,99) ;  return;}
	if (!isset($_POST["wordbooker_default_author"])) {$_POST["wordbooker_default_author"]=0; }
	if ($_POST["wordbooker_default_author"]== 0 ) { wordbooker_debugger("Author of this post is the Post Author"," ",$post->ID,80);  $_POST["wordbooker_default_author"]=$post->post_author; }
    $custom_types=explode(",",$wordbooker_settings['wordbooker_exclude_ptype']);
    wordbooker_debugger("Commence Publish "," ",$post->ID,99) ;
    wordbooker_debugger("Starting Post Processing ","",$post->ID,80);
    wordbooker_debugger("Post exclusion types ".$wordbooker_settings['wordbooker_exclude_ptype'],$post->post_type,$post->ID,80);
    if(in_array($post->post_type,$custom_types)) {
		wordbooker_debugger("Post type ".$post->post_type." excluded from publish"," ",$post->ID,99);
		return;
    }
	wordbooker_update_post_meta($post);
	# Don't save the options if we're publishing using other settings.
	if(!isset($_POST['crabstick'])){$_POST['crabstick']="nothing";}
	if ( ($_POST['action']=='editpost') && ($_POST['crabstick']!='stairwell')){
		foreach (array_keys($_POST) as $key ) {
			if (substr($key,0,8)=='wordbook') {
				$wordbooker_sets[$key]=str_replace( array('&amp;','&quot;','&#039;','&lt;','&gt;','&nbsp;&nbsp;'),array('&','"','\'','<','>',"\t"),$_POST[$key]);
			}
		}
		update_post_meta($post->ID, '_wordbooker_options', $wordbooker_sets);
	}
	# Has this been fired by a post revision rather than a proper publish
	if (wp_is_post_revision($post->ID)) {return;}
	if (!$newstatus=="publish") { return;}
	if ( $post->post_status == 'publish' ) {
		$post_type_info = get_post_type_object( $post->post_type );
		if ( $post_type_info && !$post_type_info->public ) { return; }
	}

	$wb_params = get_post_meta($post->ID, '_wordbooker_options', true);
	$wb_user_id=$post->post_author;
	# Put a check in here so that we can allow the EDIT user's preferences to over-ride the AUTHOR settings
	if (wordbooker_get_userdata($user_ID) && (isset($wordbooker_settings["wordbooker_override_author"]))) {
	$wb_user_id=$user_ID;} else {
	if (! wordbooker_get_userdata($post->post_author)) { $wb_user_id=$wordbooker_settings["wordbooker_default_author"];}

	if  ($wordbooker_settings["wordbooker_default_author"] == 0 ) {$wb_user_id=$post->post_author;} else {$wb_user_id=$wordbooker_settings["wordbooker_default_author"];}}
	$wordbooker_settings["wordbooker_override_id"]=$wb_user_id;
	$_POST['wordbooker_override_id']=$wb_user_id;
	if ($_POST['crabstick']=='stairwell') {
		# If we have settings on the post then use those rather than the user defaults
		if (count($wb_params)> 10 ) {$wordbooker_settings=$wb_params;}
		else {
			$wordbooker_user_settings_id="wordbookuser".$blog_id;
			$wordbookuser=get_usermeta($wb_user_id,$wordbooker_user_settings_id);
			if(is_array($wordbookuser)) {
				foreach (array_keys($wordbookuser) as $key) {
					if ((strlen($wordbookuser[$key])>0) && ($wordbookuser[$key]!="0") ) {
						$wordbooker_settings[$key]=$wordbookuser[$key];
					}
				}
			}
		}
		foreach (array_keys($wordbooker_settings) as $key ) {
				if (substr($key,0,8)=='wordbook') {
					if (!isset($_POST[$key])){$_POST[$key]=str_replace( array('&amp;','&quot;','&#039;','&lt;','&gt;','&nbsp;&nbsp;'),array('&','"','\'','<','>',"\t"),$wordbooker_settings[$key]);}
				}
		}
		update_post_meta($post->ID, '_wordbooker_options',$wordbooker_settings);
	}
	if(!isset($_POST["_wp_http_referer"])) {$_POST["_wp_http_referer"]="nothing";}
	if ( (!is_array($wb_params)) &&((stripos($_POST["_wp_http_referer"],'press-this')) || ( stripos($_POST["_wp_http_referer"],'index.php')) || (!isset($_POST['wordbooker_post_edited']) )) ) {
		wordbooker_debugger("Inside the press this / quick press / remote client block "," ",$post->ID) ;
		# Get the default publish setting for the post type
		if($post->post_type=='page'){
			$publish=$wordbooker_settings["wordbooker_publish_page_default"];
		}
		else {
			$publish=$wordbooker_settings["wordbooker_publish_post_default"];
		}
		$wordbooker_global_settings=wordbooker_options();
		$wordbooker_settings=$wordbooker_global_settings;
		$wordbooker_user_settings_id="wordbookuser".$blog_id;
		$wordbookuser=get_usermeta($wb_user_id,$wordbooker_user_settings_id);
		# If we have user settings then lets go through and override the blog level defaults.
		if(is_array($wordbookuser)) {
			foreach (array_keys($wordbookuser) as $key) {
				if ((strlen($wordbookuser[$key])>0) && ($wordbookuser[$key]!="0") ) {
					$wordbooker_settings[$key]=$wordbookuser[$key];
				}
			}
		}
		$wordbooker_settings['wordbooker_publish_default']=$publish;
		# Then populate the post array.
		if (is_array($wordbooker_settings)) {
			foreach (array_keys($wordbooker_settings) as $key ) {
				if (substr($key,0,8)=='wordbook') {
					$_POST[$key]=str_replace( array('&amp;','&quot;','&#039;','&lt;','&gt;','&nbsp;&nbsp;'),array('&','"','\'','<','>',"\t"),$wordbooker_settings[$key]);
				}
			}
		}
	}
	if ( !wordbooker_get_userdata($wb_user_id)) {
		wordbooker_debugger("No Settings for ".$wb_user_id." so using default author settings",' ',$post->ID,80);
		$wb_user_id=$wordbooker_settings["wordbooker_default_author"];
		# New get the user level settings from the DB
		$wordbooker_user_settings_id="wordbookuser".$blog_id;
		$wordbookuser=get_usermeta($wb_user_id,$wordbooker_user_settings_id);
		# If we have user settings then lets go through and override the blog level defaults.
		if(is_array($wordbookuser)) {
			foreach (array_keys($wordbookuser) as $key) {
				if ((strlen($wordbookuser[$key])>0) && ($wordbookuser[$key]!="0") ) {
					$wordbooker_settings[$key]=$wordbookuser[$key];
				}
			}
		}
		# Then populate the post array.
			if(is_array($wordbooker_settings)) {
			foreach (array_keys($wordbooker_settings) as $key ) {
				if (substr($key,0,8)=='wordbook') {
					if (!isset($_POST[$key])){$_POST[$key]=str_replace( array('&amp;','&quot;','&#039;','&lt;','&gt;','&nbsp;&nbsp;'),array('&','"','\'','<','>',"\t"),$wordbooker_settings[$key]);}
				}
			}
		}
	}
	# OK now lets get the settings from the POST array
	foreach (array_keys($_POST) as $key ) {
		if (substr($key,0,8)=='wordbook') {
			$wb_params[$key]=str_replace(array('&','"','\'','<','>',"\t",), array('&amp;','&quot;','&#039;','&lt;','&gt;','&nbsp;&nbsp;'),$_POST[$key]);
		}
	}
	if ($newstatus=="future") {
		$wb_params['wordbooker_scheduled_post']=1;
		wordbooker_debugger("This looks like a post that is scheduled for future publishing",$newstatus,$post->ID,80);
	}
	if ($newstatus=="publish" && (!isset($oldstatus) || $oldstatus!="publish") ) {
		wordbooker_debugger("This looks like a new post being published ",$newstatus,$post->ID,80) ;
		$wb_params['wordbooker_new_post']=1;
	}
	update_post_meta($post->ID, '_wordbooker_options', $wb_params);
	if ($newstatus=="publish") {
		wordbooker_debugger("Calling Wordbooker publishing function",' ',$post->ID,90) ;
		$retcode=wordbooker_publish_action($post->ID);
	}
}

function wordbooker_publish_action($post_id) {
	global $user_ID, $user_identity, $user_login, $wpdb,$wordbooker_post_options,$blog_id,$doing_post;
	if(isset($doing_post)) {wordbooker_debugger("Looks like we've already got a post going on so we can give up","",$post_id,99) ; unset($doing_post); return;}
	$doing_post="running";
	$post=get_post($post_id);
	$wordbooker_post_options= get_post_meta($post_id, '_wordbooker_options', true);
	 $wordbooker_settings=wordbooker_options();
	$excludes=array();
	$includes=array();
	$posttags = get_the_tags($post_id);
        $excludetags=explode(",",ltrim($wordbooker_settings['wordbooker_exclude_ttype'],","));
        if($posttags) {
	  foreach($posttags as $posttag) {
	    if(in_array($posttag->term_id,$excludetags)) {
	      wordbooker_debugger("Post excluded from publish due to tag : ",$posttag->name,$post_id);
	     unset($wordbooker_post_options["wordbooker_publish_default"]);
	    }
	  }
        }
         $x=(get_the_category($post_id));
         foreach($x as $y) {
        $postcats[]=rtrim(get_category_parents($y->term_id, FALSE, ' &raquo; '),' &raquo; ');
        $postcatnums[]=$y->term_id;
        }
        if (count($postcats)>0){
	  $excludethispost=0;
       $excats=explode(",", ltrim($wordbooker_settings["wordbooker_exclude_ctype"],","));
       if(count($excats)>1){
		   foreach($excats as $y) {
		  $excludes[]=rtrim(get_category_parents($y, FALSE, ' &raquo; '),' &raquo; ');
		   }
       }
       $incats=explode(",", ltrim($wordbooker_settings["wordbooker_include_ctype"],","));
       if(count($incats)>1){
		   foreach($incats as $y) {
				$includes[]=rtrim(get_category_parents($y, FALSE, ' &raquo; '),' &raquo; ');
			}
		}
	   foreach($postcatnums as $postcatnum) {
		   foreach($excats as $excat) {
			  if ($postcatnum==$excat) {$excludethispost=1;}
		   }
		}
        if (($excludethispost==1) && (isset($wordbooker_settings["wb_proc_exclude"]))  ) {
        wordbooker_debugger("Post excluded from publish due to excluded categories"," ",$post_id);
        unset($wordbooker_post_options["wordbooker_publish_default"]);
        }
       	if(!isset($wordbooker_settings["wb_proc_exclude"])){
	  // If the text of an exclude category is part of the text of the text of an included category then the more granular is enabled so we should enable including.
	  foreach($postcats as $postcat) {
	      foreach($includes as $include) {
		if ($include==$postcat){
		  foreach($excludes as $exclude) {
		    if (stristr($include,$exclude)) {
		      $$excludethispost=0;
		      wordbooker_debugger("Post included for publish due to granular control"," ",$post_id,80);
		    }
		  }
		}
	      }
	    }
	}
        }
        if ($excludethispost==1) { wordbooker_debugger("Post excluded from publish due to excluded categories"," ",$post_id); unset($wordbooker_post_options["wordbooker_publish_default"]);}

	if (is_array($wordbooker_post_options)){
		foreach (array_keys($wordbooker_post_options) as $key){
			wordbooker_debugger("(".$post_id.") Post option : ".$key,$wordbooker_post_options[$key],$post_id,80) ;
		}
	}

	if ($wordbooker_post_options["wordbooker_publish_default"]=="200") { $wordbooker_post_options["wordbooker_publish_default"]='on';}
	# If the user_ID is set then lets use that, if not get the user_id from the post
	$whichuser=$post->post_author;
	if ($user_ID >=1) {$whichuser=$user_ID;}
	# If the default user is set to 0 then we use the current user (or the author of the post if that isn't set - i.e. if this is a scheduled post)
	$wpuserid=$whichuser;
	if  ($wordbooker_post_options["wordbooker_override_id"] > 0 ) {$wpuserid=$wordbooker_post_options["wordbooker_override_id"];}
	$images=wordbooker_return_images($post->post_content,$post->ID,0);
	$ogimage=$images[0]['src'];
	update_post_meta($post->ID, '_wordbooker_thumb', $ogimage);
	$excerpt=wordbooker_post_excerpt($post->post_content,$wordbooker_settings['wordbooker_extract_length']);
	update_post_meta($post->ID, '_wordbooker_extract', $excerpt);
	if ($wordbooker_post_options["wordbooker_publish_default"]!="on"  ) {
	  if ($wordbooker_post_options["wordbooker_did_i_tag"]!="on"  ) {
		  wordbooker_debugger("Publish Default is not Set, Giving up ",$wpuserid,$post->ID) ;
		  return;
	  }
	}
	wordbooker_debugger("User has been set to : ",$wpuserid,$post->ID,80) ;


	$perms_missing=wordbooker_permissions_ok($wpuserid);
	if ($perms_missing>0) {
		wordbooker_debugger("Permissions incorrect - please reauthenticate ",$wpuserid,$post->ID,80);
		return;
	}
	if (!$wbuser = wordbooker_get_userdata($wpuserid) ) {
		wordbooker_debugger("Unable to get FB session for : ",$wpuserid,$post->ID,99) ;
		return 28;
	}
	wordbooker_debugger("Posting as user : ",$wpuserid,$post->ID,80) ;
	wordbooker_debugger("Calling wordbooker_fbclient_publishaction"," ",$post->ID,99) ;
	wordbooker_fbclient_publishaction($wbuser, $post->ID,$wpuserid);
	unset($doing_post);
	return 30;
}

function wordbooker_fbclient_publishaction($wbuser,$post_id,$wpuserid) {
	global $wordbooker_post_options,$wpdb;
	$wordbooker_settings =wordbooker_options();
	$post = get_post($post_id);
	$post_link_share = wordbooker_short_url($post_id);
	$post_link=wordbooker_short_url($post_id);
	$post_title=html_entity_decode(ltrim(wordbooker_translate($post->post_title),'@'));
	$post_content = $post->post_content;
	$images=wordbooker_return_images($post_content,$post_id,1) ;
	if (count($images) > 0) {
		foreach ($images as $key){
			wordbooker_debugger("Post Images : ",$key['src'],$post->ID,80) ;
		}
	}
	// Set post_meta to be first image
	update_post_meta($post->ID,'_wordbooker_thumb',$images[0]['src']);
	wordbooker_debugger("Getting the Excerpt"," ",$post->ID,80) ;
	unset ($processed_content);
	if (isset($wordbooker_post_options["wordbooker_use_excerpt"])  && (strlen($post->post_excerpt)>3)) {
		$post_content=$post->post_excerpt;
		$post_content=wordbooker_translate($post_content);
	}
	else {	$post_content=wordbooker_post_excerpt(wordbooker_translate($post_content),$wordbooker_post_options['wordbooker_extract_length']);}
	update_post_meta($post->ID,'_wordbooker_extract',$post_content);
	# this is getting and setting the post attributes
	$post_attribute=parse_wordbooker_attributes(stripslashes($wordbooker_post_options["wordbooker_attribute"]),$post_id,strtotime($post->post_date));
	$post_data = array(
		'media' => $images,
		'post_link' => $post_link,
		'post_link_share' => $post_link_share,
		'post_title' => $post_title,
		'post_excerpt' => htmlspecialchars_decode($post_content,ENT_QUOTES),
		'post_attribute' =>htmlspecialchars_decode($post_attribute,ENT_QUOTES),
		'post_id'=>$post->ID,
		'post_date'=>$post->post_date
		);
	if (function_exists('qtrans_use')) {
		global $q_config;
		$post_data['post_title']=qtrans_use($q_config['default_language'],$post_data['post_title']);
	}
	$post_id=$post->ID;
	$wordbooker_fb_post = array(
	  'name' => ltrim(wordbooker_translate($post_data['post_title']),'@'),
	  'link' => $post_data['post_link'],
	 'message'=> $post_data['post_attribute'],
	 'description' => $post_data['post_excerpt'],
	  'picture'=>$images[0]['src'],
	   'caption' => wordbooker_translate(get_bloginfo('description'))
	);
	if (isset($wordbooker_post_options['wordbooker_excerpt_for_attribute']))
	{
	  	if (strlen($post->post_excerpt)>3) {
		$post_content2=wordbooker_post_excerpt(wordbooker_translate($post->post_excerpt),$wordbooker_post_options['wordbooker_extract_length']);
	}
	else {	$post_content2=wordbooker_post_excerpt(wordbooker_translate($post_content),$wordbooker_post_options['wordbooker_extract_length']);}
	  	$wordbooker_fb_post['message'] = htmlspecialchars_decode($post_content2,ENT_QUOTES);
	}
	if (isset($wordbooker_settings['wordbooker_use_url_not_slug']))
	{
		$wordbooker_fb_post['caption'] = get_bloginfo('url');
	}
	$wordbooker_fb_post['caption']=wordwrap($wordbooker_fb_post['caption'],900);
	wordbooker_debugger("Post Titled : ",$post_data['post_title'],$post_id,90) ;
	wordbooker_debugger("Post URL : ",$post_data['post_link'],$post_id,90) ;
	wordbooker_debugger("Post Caption : ",$wordbooker_fb_post['caption'],$post_id,90) ;
	if ($wordbooker_post_options['wordbooker_actionlink']==100) {
		wordbooker_debugger("No action link being used","",$post_id,80) ;
		$action_links = array('name' =>' ','link' => 'https://www.facebook.com/share.php?u='.urlencode($post_data['post_link_share']));
		$wordbooker_fb_post['actions']=json_encode($action_links);
	}
		if ($wordbooker_post_options['wordbooker_actionlink']==110) {
		wordbooker_debugger("Internal FB share being used","",$post_id,80) ;
	//	$action_links = array('name' =>' ','link' => 'https://www.facebook.com/share.php?u='.urlencode($post_data['post_link_share']));
	//	$wordbooker_fb_post['actions']=json_encode($action_links);
	}
	if ($wordbooker_post_options['wordbooker_actionlink']==200) {
		wordbooker_debugger("Share Link being used"," ",$post_id,80) ;
		$action_links = array('name' => __('Share', 'wordbooker'),'link' => 'https://www.facebook.com/share.php?u='.urlencode($post_data['post_link_share']));
		$wordbooker_fb_post['actions']=json_encode($action_links);
	}
	if ($wordbooker_post_options['wordbooker_actionlink']==300) {
		wordbooker_debugger("Read Full link being used"," ",$post_id,80) ;
		$action_links = array('name' => __('Read entire article', 'wordbooker'),'link' => $post_data['post_link_share']);
		$wordbooker_fb_post['actions'] =json_encode($action_links);
	}
	if (! isset($wordbooker_post_options['wordbooker_secondary_active'])) {$wordbooker_post_options['wordbooker_secondary_active']='X';}

	$posting_array[] = array('target_id'=>__("Primary", 'wordbooker'),
				'target'=>$wordbooker_post_options['wordbooker_primary_target'],
				 'target_type'=>$wordbooker_post_options['wordbooker_primary_type'],
				 'target_active'=>$wordbooker_post_options['wordbooker_primary_active']);
	$posting_array[] = array('target_id'=>__("Secondary", 'wordbooker'),
				'target'=>$wordbooker_post_options['wordbooker_secondary_target'],
				 'target_type'=>$wordbooker_post_options['wordbooker_secondary_type'],
				 'target_active'=>$wordbooker_post_options['wordbooker_secondary_active']);
	$target_types = array('PW' => "",'FW' => __('Fan Wall', 'wordbooker'), 'GW'=>__('Group wall', 'wordbooker'));
	$posting_type=array("1"=>"Wall Post","2"=>"Note","3"=>"Status Update","4"=>"Link");
	if(isset($wordbooker_post_options["wordbooker_publish_default"])){
	// Lets check the token before starting anything
	$at=wordbooker_check_access_token($wbuser->access_token);
	if(!$at->data->is_valid) {
		wordbooker_debugger("Posting Failed : Access Token is not valid ",$at->data->error->message,$post_id,99) ;
		return;
	}
	foreach($posting_array as $posting_target) {
		$access_token='dummy access token';
		$wbuser->pages[]=array( 'id'=>'PW:'.$wbuser->facebook_id, 'name'=>"Personal Wall",'access_token'=>$wbuser->access_token);
		if(is_array($wbuser->pages)){
			foreach ($wbuser->pages as $pager) {
				if ($pager['id']==$posting_target['target']) {
					$target_name=$pager['name'];
					$access_token=$pager['access_token'];
				}
			}
		}
		if ($posting_target['target_active']=='X') { unset($posting_target['target_active']);}
 		if (isset($posting_target['target_active'])) {
			$target_type=substr($posting_target['target'],0,2);
			if ($access_token=='dummy access token') {$access_token=$wbuser->access_token;}
			if (is_null($access_token)) {
				wordbooker_debugger("Posting to ".$target_name." (".$posting_target['target_id'].") failed as there is no access token","",$post_id,90) ;
			}
			else {
				if (!defined('WORDBOOKER_FB_SECRET')) {$app_secret='df04f22f3239fb75bf787f440e726f31'; } else {$app_secret=WORDBOOKER_FB_SECRET;}
				$appsecret_proof= hash_hmac('sha256', $access_token, $app_secret);
				$wordbooker_fb_post['appsecret_proof']=$appsecret_proof;
				$target=substr($posting_target['target'],3);
				if (!isset($wordbooker_settings['wordbooker_fake_publish'])) {$wordbooker_settings['wordbooker_fake_publish']='';}
				$is_dummy=$wordbooker_settings['wordbooker_fake_publish'];
			//	if($is_dummy="x") {unset($is_dummy);}
				wordbooker_debugger("Posting to ".$target_types[$target_type]." ".$target_name." (".$posting_target['target_id'].") as a ".$posting_type[$posting_target['target_type']],"",$post_id,90) ;
				switch($posting_target['target_type']) {
					# Wall Post
					case 1 :
					wordbooker_wall_post($post_id,$access_token,$post_title,$wordbooker_fb_post ,$target,$is_dummy,$target_name,$wpuserid,$wbuser->facebook_id);
					break;
					# Note
					case 2 :
					wordbooker_notes_post($post_id,$access_token,$post_title,$target,$is_dummy,$target_name,$wpuserid,$wbuser->facebook_id);
					break;
					# Status Update
					case 3 :
					wordbooker_status_update($post_id,$access_token,$post_data['post_date'],$target,$is_dummy,$target_name,$wpuserid,$wbuser->facebook_id);
					break ;
					# Link Post
					case 4 :
					wordbooker_link_post($post_id,$access_token,$post_title,$wordbooker_fb_post ,$target,$is_dummy,$target_name,$wpuserid,$wbuser->facebook_id);
					break ;
				}
			}
		} else {wordbooker_debugger("Posting to ".$posting_target['target_id']." target (".$target_name.") not active","",$post_id,90) ; }
	}
	# Premium functions - once per publish
	if (!defined('WORDBOOKER_PREMIUM')) {} else {
		wordbooker_debugger("Processing Premium Functions","",$post_id,90) ;
		# Photo Album Upload
		if(isset($wordbooker_post_options["wordbooker_album_upload"]) && isset($wordbooker_post_options["wordbooker_album_list"])){
			wordbooker_debugger("Premium : Upload Images to Album","",$post_id,90) ;
			//$images=wordbooker_return_images($post->post_content,$post->ID,0);
			$images=array();
				$args = array(
				'post_type' => 'attachment',
				'numberposts' => -1,
				'post_status' => null,
				'post_parent' => $post_id
				);
			$attachments = get_posts( $args );
			if ( $attachments ) {
				foreach ( $attachments as $attachment ) {
					if ($attachment->post_type=='attachment') {
						$junk=wp_get_attachment_image_src( $attachment->ID,'large');
						$images[]=$junk[0];
					  }
					}
				}
			}
		//	var_dump($images);
		}
	} else {wordbooker_debugger("Publish not set, so not doing anything"," ",$post_id,80) ; }
	/*
	if (isset($wordbooker_post_options["wordbooker_did_i_tag"])){
	wordbooker_debugger("Notifying friends about a post"," ",$post_id,80) ;
	$is_dummy=$wordbooker_settings['wordbooker_fake_publish'];
	  $friend_list=explode(';',$wordbooker_post_options['wordbooker_tag_list']);
	  $access_token=$wbuser->access_token;
	  if(is_array($friend_list)) {
	    $wordbooker_fb_post = array(
	    'name' => ltrim(wordbooker_translate($post_data['post_title']),'@'),
	    'link' => $post_data['post_link'],
	    'message'=> $wordbooker_post_options['wordbooker_tag_message'],
	    'description' => $post_data['post_excerpt'],
	    'picture'=>$images[0]['src'],
	    'caption' => wordbooker_translate(get_bloginfo('description'))
	  );
	  $friend_list=array_slice($friend_list, 0, 5);
	  foreach($friend_list as $friend) {
	    $friend_id=explode(':',$friend);
	    if(strlen($friend_id[0])>3){
	      wordbooker_tag_post($post_id,$access_token,$post_title,$wordbooker_fb_post ,$friend_id[0],$is_dummy,$friend_id[1],$wpuserid,$wbuser->facebook_id);
	    }
	  }}
	} else {wordbooker_debugger("Tagging not set"," ",$post_id,80) ; }
	*/
}


?>