<?php
/*
Extension Name: Wordbooker Misc Functions
Extension URI: http://wordbooker.tty.org.uk
Version: 2.2
Description: Various Functions that don't fit into any specific category.
Author: Steve Atty
*/

function wordbooker_delete_comment($comment_id) {
	global $blog_id;
	wordbooker_delete_comment_from_facebook($comment_id,$blog_id);
//	wordbooker_delete_from_commentlogs($comment_id,$blog_id);
}

function wordbooker_delete_comment_from_facebook($comment_id,$blog_id){
	global $wpdb,$blog_id;
	$sql="select user_id,fb_comment_id,fb_user_id,fb_target_id,in_out from ".WORDBOOKER_POSTCOMMENTS." where wp_comment_id=".$comment_id." and blog_id=".$blog_id;
	$results = $wpdb->get_results($sql);
	foreach($results as $result){
		$inout=$result->in_out;
		$uid=$result->fb_user_id;
		$targetid=$result->fb_target_id;
		$sql="select facebook_id,access_token,pages from ".WORDBOOKER_USERDATA." where facebook_id='".$uid."' and access_token is not null limit 1";
		# OK if we're dealing with an old post then we need to work out the userid and target_id from the existing data
		if (!is_numeric($uid)) {
			$userid=$result->user_id;
			$sql="select facebook_id,access_token,pages from ".WORDBOOKER_USERDATA." where user_id='".$userid."' and access_token is not null limit 1";
			$fbpipd=explode('-',$fb_post_id);
			$targetid=$fbpipd[0];
		}
		$pages = $wpdb->get_results($sql);
		$pages=$pages[0];
		$uid=$pages->facebook_id;
		$access_token=unserialize($pages->access_token);
		$pagelist=unserialize($pages->pages);
		if ($uid != $targetid){
			if(is_array($pagelist)){
				foreach ($pagelist as $pager) {
					$pid=explode(":",$pager['id']);
					if ($pid[1]==$targetid) {
						$access_token=$pager['access_token'];
						break;
					}
				}
			}
		}
		$del_com=0;
		$del_imp_com=wordbooker_get_option('wordbooker_delete_comment_fb_imp');
		$del_exp_com=wordbooker_get_option('wordbooker_delete_comment_fb_exp');
		if (($inout=="in") && ($del_imp_com)) {$del_com=1; wordbooker_debugger("Trying to delete source comment from Facebook : ".$error_msg,$result->fb_comment_id,-4,99); }
		if (($inout=="out") && ($del_exp_com)) {$del_com=1;wordbooker_debugger("Trying to delete exported comment from Facebook : ".$error_msg,$result->fb_comment_id,-4,99); }
		if($del_com==1){
			try {
				wordbooker_delete_fb_post($result->fb_comment_id,$access_token);
				wordbooker_debugger("Deleted comment from Facebook : ".$error_msg,$result->fb_comment_id,-4,99);
			}
			catch (Exception $e)
			{
				$error_msg = $e->getMessage();
				wordbooker_debugger("Failed to delete comment from Facebook : ".$error_msg,$result->fb_comment_id,-4,99);
			}
		} else {wordbooker_debugger("No delete options selected for deleted comment : ".$error_msg,$result->fb_comment_id,-4,99); }
	}
}

function wordbooker_debugger($method,$error_msg,$post_id,$level=9) {
	if (WORDBOOKER_NEVER_LOG) { return;}
	global $user_ID,$post_ID,$wpdb,$blog_id,$post,$wbooker_user_id,$comment_user,$wb_user_id;
	$loglevel=wordbooker_get_option('wordbooker_advanced_diagnostics_level');
	if ($loglevel>999) {return;}
	#var_dump($wbooker_user_id);
	$usid=1;
	if (isset($user_ID)) {$usid=$user_ID;}
	if (isset($post_id) && ($post_id>=1)){
		$p=get_post($post_id);
		#we dont want to record anything if its an draft of any kind
		if (stristr($p->post_status,'draft')) {return;}
		$x = get_post_meta($post->ID, '_wordbooker_options', true);
		$usid=$p->post_author;
		if(isset($x['wordbooker_override_author'])) {$usid=$user_ID;}
	}
	$admin_id=wordbooker_get_option('wordbooker_diagnostic_admin');
	$admin_comment_log=wordbooker_get_option('wordbooker_comment_log');
	$token_log=wordbooker_get_option('wordbooker_token_log');
  	if ((!isset($admin_comment_log)) && ($post_id==-2)) { return;}
	if ((!isset($token_log)) && ($post_id==-5) && ($level<99)) { return;}
	$row_id=1;
	if (isset($wb_user_id)) {$usid=$wb_user_id;} else {
		if (!isset($admin_id)) {$admin_id=1;}
		if (!isset($post_id)) {$post_id=$post_ID;}
		if (!isset($post_id)) {$post_id=1;}
		if ($usid==0) {$usid=$wbooker_user_id;}
		if (!isset($usid)) {$usid=wordbooker_get_option('wordbooker_default_author');}
		if (!isset($usid)) {$usid=$admin_id;}
		if ($usid==0) {$usid=$admin_id;}
		if ($post_id==-3) {$usid=$comment_user;}
		if ($post_id==-2) {$usid=$comment_user;}
		if ($post_id==-1) {$usid=$wbooker_user_id;}
		if ($post_id==0) {$usid=$user_ID;}
	}
if (!is_numeric($usid)) {$usid=0;}
if (is_array($error_msg)) { $error_msg="Array : ". print_r($error_msg,TRUE);}
if (is_array($method)) { $method="Array : ". print_r($method,TRUE);}
$sql=$wpdb->prepare("INSERT INTO " . WORDBOOKER_ERRORLOGS . " (user_id, method, error_code, error_msg, post_id, blog_id, diag_level) VALUES (%d,%s,%d,%s,%d,%d,%d)",$usid ,$method,$row_id,$error_msg,$post_id,$blog_id,$level);
	$result = $wpdb->query($sql);
}

function wordbooker_permissions_ok($user_id){
	global $wpdb;
	$wbooker_user_id=$user_id;
	$sql="select auths_needed from  ".WORDBOOKER_USERDATA."  where user_ID=".$user_id;
	$result = $wpdb->get_results($sql);
	return $result[0]->auths_needed;
}


function wordbooker_remove_wordbooker(){
	$table_array= array (WORDBOOKER_ERRORLOGS,WORDBOOKER_POSTLOGS,WORDBOOKER_USERDATA,WORDBOOKER_USERSTATUS,WORDBOOKER_POSTCOMMENTS,WORDBOOKER_PROCESS_QUEUE,WORDBOOKER_FB_FRIENDS,WORDBOOKER_FB_FRIEND_LISTS);
	foreach ($table_array as $table) {
		$sql="Drop table ".$table;
		#$result = $wpdb->query($sql);
		echo "Dropping table : ".$table."<br /";
	}
	echo "Removing commment cron ";
	$dummy=wp_clear_scheduled_hook('wb_comment_job');
	echo "Removing Status Cache cron";
	$dummy=wp_clear_scheduled_hook('wb_cron_job');
	delete_option(WORDBOOKER_SETTINGS);

}

function wordbooker_delete_post($post_id) {
	global $blog_id;
	$delete_from_fb=wordbooker_get_option('wordbooker_delete_published');
		wordbooker_debugger("Starting Deletion of Post ".$post_id,"",-4,99) ;
	if ($delete_from_fb) {
		wordbooker_debugger("Deleting Post ".$post_id,"Removing Post from Facebook",-4,99) ;
		wordbooker_delete_post_from_facebook($post_id,$blog_id);
	}
	wordbooker_debugger("Deleting Post ".$post_id,"Removing Error logs",-4,99) ;
	wordbooker_delete_from_errorlogs($post_id,$blog_id);
	wordbooker_debugger("Deleting Post ".$post_id,"Removing post logs",-4,99) ;
	wordbooker_delete_from_postlogs($post_id,$blog_id);
	wordbooker_debugger("Deleting Post ".$post_id,"Removing FB comment logs",-4,99) ;
	wordbooker_delete_from_commentlogs($post_id,$blog_id);
	wordbooker_debugger("Completed Deletion of Post ".$post_id,"",-4,99) ;

}

function wordbooker_delete_post_from_facebook($post_id,$blog_id){
	global $wpdb,$blog_id;
	$sql="select user_id,fb_post_id,fb_user_id,fb_target_id from ".WORDBOOKER_POSTCOMMENTS." where wp_post_id=".$post_id." and blog_id=".$blog_id;
	$results = $wpdb->get_results($sql);
	foreach($results as $result){
		$uid=$result->fb_user_id;
		$targetid=$result->fb_target_id;
		$sql="select facebook_id,access_token,pages from ".WORDBOOKER_USERDATA." where facebook_id='".$uid."' and access_token is not null limit 1";
		# OK if we're dealing with an old post then we need to work out the userid and target_id from the existing data
		if (!is_numeric($uid)) {
			$userid=$result->user_id;
			$sql="select facebook_id,access_token,pages from ".WORDBOOKER_USERDATA." where user_id='".$userid."' and access_token is not null limit 1";
			$fbpipd=explode('-',$fb_post_id);
			$targetid=$fbpipd[0];
		}
		$pages = $wpdb->get_results($sql);
		$pages=$pages[0];
		$uid=$pages->facebook_id;
		$access_token=unserialize($pages->access_token);
		$pagelist=unserialize($pages->pages);
		if ($uid != $targetid){
			if(is_array($pagelist)){
				foreach ($pagelist as $pager) {
					$pid=explode(":",$pager['id']);
					if ($pid[1]==$targetid) {
						$access_token=$pager['access_token'];
						break;
					}
				}
			}
		}
		try {
			wordbooker_delete_fb_post($result->fb_post_id,$access_token);
			wordbooker_debugger("Deleted post from Facebook : ".$error_msg,$result->fb_post_id,-4,99);
		}
		catch (Exception $e)
		{
			$error_msg = $e->getMessage();
			wordbooker_debugger("Failed to delete post from Facebook : ".$error_msg,$result->fb_post_id,-4,99);
		}
	}
}


function wordbooker_hyperlinked_method($method) {
	return '<a href="'. WORDBOOKER_FB_DOCPREFIX . $method . '"'. ' title="Facebook API documentation" target="facebook"'. '>'. $method. '</a>';
}


function wordbooker_render_errorlogs() {
	global $user_ID, $wpdb,$blog_id;
	$diaglevel=wordbooker_get_option('wordbooker_advanced_diagnostics_level');
	if ($diaglevel>999) {$diaglevel=0;}
	$count_rows = $wpdb->get_results('SELECT count(*) as count FROM ' . WORDBOOKER_ERRORLOGS . ' WHERE user_ID = ' . $user_ID . '  and blog_id='.$blog_id);
	$rows = $wpdb->get_results('SELECT * FROM ' . WORDBOOKER_ERRORLOGS . ' WHERE user_ID = ' . $user_ID . '  and blog_id='.$blog_id.' and diag_level >='.$diaglevel.' order by sequence_id asc');
	if ($count_rows[0]->count >= 1) {
	echo "<h3>";
	_e('Diagnostic Messages', 'wordbooker');
	$diag_count=sprintf(__('(Showing %1$s from a total of %2$s rows)'), count($rows), $count_rows[0]->count);
	echo ' '.$diag_count;
 ?></h3>
	<div class="wordbooker_errors"><p></p>
<?php if (count($rows) > 0 ) {
?>
	<table class="wordbooker_errorlogs"><tr><th>Post</th><th>Time</th><th>Action</th><th>Message</th><th>Error Code</th></tr>
<?php
	foreach ($rows as $row) {
		$row_type=array(0=>'Authorisation Process',-1=>"Cache Refresh",-2=>"Comment Processing (Admin Diag)",-3=>"Comment Processing (User Diag)",-4=>"Post Deletion",-5=>'Access Token Exchange',-6=>'Authorisation Process',-7=>'DB Maintenance');
		$hyperlinked_post = '';
		if (($post = get_post($row->post_id))) {
			$hyperlinked_post = '<a href="'. get_permalink($row->post_id) . '">'. apply_filters('the_title',get_the_title($row->post_id)) . '</a>';
		}
		$hyperlinked_method= wordbooker_hyperlinked_method($row->method);
		if ($row->error_code>1){ echo "<tr class='error'>";} else {echo "<tr class='diag'>";}
		if(!isset($row->post_id)){$row->post_id='-6';}
?>
			<td><?php if ($row->post_id>0) { echo $hyperlinked_post;} else { echo $row_type[$row->post_id];}  ?></td>
			<td><?php echo $row->timestamp; ?></td>
			<td><?php echo $row->method; ?></td>
			<td><?php echo stripslashes($row->error_msg); ?></td>
			<td><?php if ($row->error_code>1) {echo $row->error_code;} else { echo "-";}  ?></td>
		</tr>
<?php
		}
	echo "</table> ";
	}
?>
	<form action="<?php echo WORDBOOKER_SETTINGS_URL; ?>" method="post">
		<input type="hidden" name="action" value="clear_errorlogs" />
		<p class="submit" style="text-align: center;">
		<input type="submit" value="<?php _e('Clear Diagnostic Messages', 'wordbooker'); ?>" /></p></form></div><hr>
<?php
	}
}

function wordbooker_check_permissions($wbuser,$user) {
	global $user_ID;
	$perm_miss=wordbooker_get_cache($user_ID,'auths_needed',1);
	if ($perm_miss->auths_needed==0) { return;}
	$response='code';
	$redirecturl=urlencode(get_bloginfo('wpurl')).'/wp-admin/options-general.php?page=wordbooker';
	$perms_to_check= array(WORDBOOKER_FB_PUBLISH_STREAM,WORDBOOKER_FB_STATUS_UPDATE,WORDBOOKER_FB_READ_STREAM,WORDBOOKER_FB_CREATE_NOTE,WORDBOOKER_FB_PHOTO_UPLOAD,WORDBOOKER_FB_VIDEO_UPLOAD,WORDBOOKER_FB_MANAGE_PAGES,WORDBOOKER_FB_READ_FRIENDS,WORDBOOKER_FB_USER_PHOTOS);
	$perm_messages= array( __('Publish content to your Wall/Fan pages', 'wordbooker'),__('Update your status', 'wordbooker'), __('Read your News Feed and Wall', 'wordbooker'),__('Create notes', 'wordbooker'),__('Upload photos', 'wordbooker'),__('Upload videos', 'wordbooker'),__('Manage pages', 'wordbooker'),__('Read friend lists', 'wordbooker'),__('Access your photos', 'wordbooker'));
	if (!defined('WORDBOOKER_PREMIUM')) {
		$response='token';
		$redirecturl='https://wordbooker.tty.org.uk/index2.html?br='.urlencode(get_bloginfo('wpurl').'&fbid='.WORDBOOKER_FB_ID);
		$perms_to_check= array(WORDBOOKER_FB_PUBLISH_STREAM,WORDBOOKER_FB_STATUS_UPDATE,WORDBOOKER_FB_READ_STREAM,WORDBOOKER_FB_CREATE_NOTE,WORDBOOKER_FB_PHOTO_UPLOAD,WORDBOOKER_FB_VIDEO_UPLOAD,WORDBOOKER_FB_MANAGE_PAGES,WORDBOOKER_FB_READ_FRIENDS);
		$perm_messages= array( __('Publish content to your Wall/Fan pages', 'wordbooker'),__('Update your status', 'wordbooker'), __('Read your News Feed and Wall', 'wordbooker'),__('Create notes', 'wordbooker'),__('Upload photos', 'wordbooker'),__('Upload videos', 'wordbooker'),__('Manage pages', 'wordbooker'),__('Read friend lists', 'wordbooker'));
	}

	$preamble= __("but requires authorization to ", 'wordbooker');
	$postamble= __(" on Facebook. Click on the following link to grant permission", 'wordbooker');
	$loginUrl2='https://www.facebook.com/dialog/oauth?client_id='.WORDBOOKER_FB_ID.'&redirect_uri='.$redirecturl.'&scope='.implode(',',$perms_to_check).'&response_type='.$response;
	if(is_array($perms_to_check)) {
		foreach(array_keys($perms_to_check) as $key){
			if (pow(2,$key) & $perm_miss->auths_needed ) {
				$midamble.=$perm_messages[$key].", ";
				}
		}
		$midamble=rtrim($midamble," ");
		$midamble=rtrim($midamble,",");
		$midamble=trim(preg_replace("/(.*?)((,|\s)*)$/m", "$1", $midamble));
		$midamble=str_replace(",","and",$midamble);

		echo " ".$preamble.$midamble.$postamble.'</p>';
		/*
		<div style="text-align: center;"><a href="'.$loginUrl2.'" > <img src="https://static.ak.facebook.com/images/devsite/facebook_login.gif"  alt="Facebook Login Button" /></a><br /></div>';
		*/
		echo '<div style="text-align: center;"><div id="u_0_0" class="pluginFaviconButton pluginFaviconButtonEnabled pluginFaviconButtonMedium"><i class="pluginFaviconButtonIcon img sp_login-button sx_login-button_medium"></i><span class="pluginFaviconButtonBorder"><span class="pluginFaviconButtonText fwb">
      <a href="'.$loginUrl2.'" STYLE="text-decoration: none;Color:white">&nbsp;'.__("Authorise Wordbooker",wordbooker).'&nbsp;</a></span></span></div></div><br />';
	}
	echo "and then save your settings<br />";

	echo '<form action="" method="post">';
	echo '<p style="text-align: center;"><input type="submit" name="perm_save" class="button-primary" value="'. __('Save Configuration', 'wordbooker').'" /></p></form>';
	$wplang=wordbooker_get_language();
	$wordbooker_settings = wordbooker_options();
	$fb_id=$wordbooker_settings["fb_comment_app_id"];
	if (strlen($fb_id)<6) {
	$fb_id=WORDBOOKER_FB_ID;
	}
	if (defined('WORDBOOKER_PREMIUM')) {
		$fb_id=WORDBOOKER_FB_ID;
	}
$efb_script = <<< EOGS
 <div id="fb-root"></div>
     <script type="text/javascript" defer="defer">
      window.fbAsyncInit = function() {
	FB.init({
	 appId  : '
EOGS;
$efb_script.=$fb_id;
$efb_script .= <<< EOGS
',
	  status : true, // check login status
	  cookie : true, // enable cookies to allow the server to access the session
	  xfbml  : true,  // parse XFBML
	  oauth:true
	});
      };
      (function() {
	var e = document.createElement('script');
EOGS;
$efb_script.= "e.src = document.location.protocol + '//connect.facebook.net/".$wplang."/all.js';";
$efb_script.= <<< EOGS
	e.async = true;
	document.getElementById('fb-root').appendChild(e);
      }());
    </script>
EOGS;
	echo $efb_script;
	echo "</div></div>";
}


function wordbooker_get_language() {
	global $q_config;
	$wplang= get_locale();
	if (isset ($q_config["language"])) {
		$x=get_option('qtranslate_locales');
		$wplang=$x[$q_config["language"]];
	}
	$fb_valid_fb_locales = array(
	'ca_ES', 'cs_CZ', 'cy_GB', 'da_DK', 'de_DE', 'eu_ES', 'en_PI', 'en_UD', 'ck_US', 'en_US', 'es_LA', 'es_CL', 'es_CO', 'es_ES', 'es_MX',
	'es_VE', 'fb_FI', 'fi_FI', 'fr_FR', 'gl_ES', 'hu_HU', 'it_IT', 'ja_JP', 'ko_KR', 'nb_NO', 'nn_NO', 'nl_NL', 'pl_PL', 'pt_BR', 'pt_PT',
	'ro_RO', 'ru_RU', 'sk_SK', 'sl_SI', 'sv_SE', 'th_TH', 'tr_TR', 'ku_TR', 'zh_CN', 'zh_HK', 'zh_TW', 'fb_LT', 'af_ZA', 'sq_AL', 'hy_AM',
	'az_AZ', 'be_BY', 'bn_IN', 'bs_BA', 'bg_BG', 'hr_HR', 'nl_BE', 'en_GB', 'eo_EO', 'et_EE', 'fo_FO', 'fr_CA', 'ka_GE', 'el_GR', 'gu_IN',
	'hi_IN', 'is_IS', 'id_ID', 'ga_IE', 'jv_ID', 'kn_IN', 'kk_KZ', 'la_VA', 'lv_LV', 'li_NL', 'lt_LT', 'mk_MK', 'mg_MG', 'ms_MY', 'mt_MT',
	'mr_IN', 'mn_MN', 'ne_NP', 'pa_IN', 'rm_CH', 'sa_IN', 'sr_RS', 'so_SO', 'sw_KE', 'tl_PH', 'ta_IN', 'tt_RU', 'te_IN', 'ml_IN', 'uk_UA',
	'uz_UZ', 'vi_VN', 'xh_ZA', 'zu_ZA', 'km_KH', 'tg_TJ', 'ar_AR', 'he_IL', 'ur_PK', 'fa_IR', 'sy_SY', 'yi_DE', 'gn_PY', 'qu_PE', 'ay_BO',
	'se_NO', 'ps_AF', 'tl_ST');
	# Fix for Japanese locale and others mismatch - Thanks to Shohei Tanaka plus others
	$fix_wp_locales = array(
		'ca'=> 'ca_ES',
		'en'=> 'en_US',
		'el'=> 'el_GR',
		'sq'=> 'sq_AL',
		'uk'=> 'uk_UA',
		'vi'=> 'vi_VN',
		'et'=> 'et_EE',
		'ja'=> 'ja_JP',
		'zh'=> 'zh_CN'
	);
	if ( isset( $fix_wp_locales[$wplang] ) ) {
		$wplang = $fix_wp_locales[$wplang];
	}
	if (strlen($wplang) == 2) {
		$wplang = strtolower($wplang).'_'.strtoupper($wplang);
	}
	$wplang = str_replace('-', '_', $wplang);
	if ( !in_array($wplang, $fb_valid_fb_locales) ) {
		$wplang = 'en_US';
	}
	return $wplang;
}

function wordbooker_short_url($post_id) {
	$wordbooker_settings =wordbooker_options();
	$url = get_permalink($post_id);
	if (!isset($wordbooker_settings["wordbooker_disable_shorties"])) {
		return $url;
	}
	if ($wordbooker_settings["wordbooker_disable_shorties"]=='off') {
		return $url;
	}
	if ($wordbooker_settings["wordbooker_disable_shorties"]=='forced') {
		return wp_get_shortlink($post_id);
	}
	if (function_exists('fts_show_shorturl')) {
		$post = get_post($post_id);
		$url=fts_show_shorturl($post,$output = false);
	}
	if (function_exists('wp_ozh_yourls_geturl')) {
		$url=wp_ozh_yourls_geturl($post_id);
	}
	if (function_exists('wpme_get_shortlink')) {
		$post = get_post($post_id);
		$url = wpme_get_shortlink($post_id);
	}
	# If there is no shortened url then return the original
	if ("!!!".$url."XXXX"=="!!!XXXX") {$url = $url2;}
	# If the short url is undefined then return the original.
	if (stripos($url,'undefined.undefined')) {$url=$url2;}
	return $url;
}

function parse_wordbooker_attributes($attribute_text,$post_id,$timestamp) {
	# Changes various "tags" into their WordPress equivalents.
	global $wordbooker_post_options;
	$post = get_post($post_id);
	$user_id=$post->post_author;
	$title=$post->post_title;
	$perma=get_permalink($post->ID);
	$perma_short=wordbooker_short_url($post_id);
	$user_info = get_userdata($user_id);
	$blog_url= get_bloginfo('url');
	$wp_url= get_bloginfo('wpurl');
	$blog_name = get_bloginfo('name');
	$author_nice=$user_info->display_name;
	$author_nick=$user_info->nickname;
	$author_first=$user_info->first_name;
	$author_last=$user_info->last_name;

	# Format date and time to the blogs preferences.
	$date_info=date_i18n(get_option('date_format'),$timestamp);
	$time_info=date_i18n(get_option('time_format'),$timestamp);

	# Now do the replacements
	$attribute_text=str_ireplace( '%author%',$author_nice,$attribute_text );
	$attribute_text=str_ireplace( '%first%',$author_first,$attribute_text );
	$attribute_text=str_ireplace( '%wpurl%',$wp_url,$attribute_text );
	$attribute_text=str_ireplace( '%burl%',$blog_url,$attribute_text );
	$attribute_text=str_ireplace( '%last%',$author_last,$attribute_text );
	$attribute_text=str_ireplace( '%nick%',$author_nick,$attribute_text );
	$attribute_text=str_ireplace( '%title%',$title,$attribute_text );
	$attribute_text=str_ireplace( '%link%',$perma,$attribute_text );
	$attribute_text=str_ireplace( '%slink%',$perma_short,$attribute_text );
	$attribute_text=str_ireplace( '%date%', $date_info ,$attribute_text);
	$attribute_text=str_ireplace( '%time%', $time_info,$attribute_text );
    if (!isset($wordbooker_post_options['wordbooker_tag_list'])) {$wordbooker_post_options['wordbooker_tag_list']=' ';}
	 $friend_list=explode(';',$wordbooker_post_options['wordbooker_tag_list']);
	 if (count($friend_list)>1) {
	  $friend_list=array_slice($friend_list, 0, 5);
	  foreach($friend_list as $friend) {
	    $friend_id=explode(':',$friend);
	    $friends[]=$friend_id[0];
	  }
	  if (count($friends)>0) {
	  $attribute_text=str_ireplace( '%F1%', '@['.$friends[0].']',$attribute_text );
	  $attribute_text=str_ireplace( '%F2%', '@['.$friends[1].']',$attribute_text );
	  $attribute_text=str_ireplace( '%F3%', '@['.$friends[2].']',$attribute_text );
	  $attribute_text=str_ireplace( '%F4%', '@['.$friends[3].']',$attribute_text );
	  $attribute_text=str_ireplace( '%F5%', '@['.$friends[4].']',$attribute_text );
	  }
	  }
	return wordbooker_translate($attribute_text);
}

function wordbooker_renew_access_token($userid=null) {
	global $wpdb,$user_ID,$wbooker_user_id;
	if(is_null($userid)){$userid=$user_ID;}
	$wbooker_user_id=$userid;
	$sql="select user_ID,access_token,updated from ".WORDBOOKER_USERDATA." where user_ID=".$userid;
	$result = $wpdb->get_results($sql);
	$today=date('z');
	foreach($result as $user_row){
		if (strlen($user_row->access_token)>15) {
			wordbooker_debugger("Access token was ",unserialize($user_row->access_token),-5,88) ;
			try {
			  $ret_code=wordbooker_get_access_token(unserialize($user_row->access_token));
			}
			catch (Exception $e) {
				$error_code = $e->getCode();
				$error_msg = $e->getMessage();
				wordbooker_append_to_errorlogs("Access token refresh failed ",50, $error_msg,-5,$wbooker_user_id);
				return;
			}
			wordbooker_debugger("Return code is ",$ret_code,-5,88) ;
			$x=explode('&',$ret_code);
			$x=explode('=',$x[0]);
			$access_token=$x[1];
			$ex=$x[1];
			$ex2=explode('=',$ex);
			if (!isset($ex2[1])){$ex2[1]=0;}
			$time=time()+$ex2[1];
			if (strlen($access_token) < 15) {$access_token=unserialize($user_row->access_token);}
			if (strlen($access_token) > 15) {
			$sql= "Update " . WORDBOOKER_USERDATA . " set access_token = '" . serialize($access_token) . "', updated=".$today." where user_id=".$userid;
				if (strlen($ex2[1])> 3) {$sql= "Update " . WORDBOOKER_USERDATA . " set access_token = '" . serialize($access_token) . "',  expires='".$time."', updated=".$today." where user_id=".$userid;}
				$result = $wpdb->query($sql);
				wordbooker_debugger("Access token was ",unserialize($user_row->access_token),-5,88) ;
				wordbooker_debugger("Access token is now ",$access_token,-5,88) ;
				wordbooker_debugger("Access token updated"," ",-5,88) ;
			}
			else {wordbooker_debugger("Access token wasn't updated as new one was too short",print_r($ret_code,true),-5,88) ; }
		}  else {wordbooker_debugger("Access token wasn't updated as original was too short",print_r($ret_code,true),-5,88) ; }
	}
}

function get_check_session(){
	global $facebook2,$user_ID;
	# This function basically checks for a stored session and if we have one it returns it, If we have no stored session then it gets one and stores it

	# OK lets go to the database and see if we have a session stored
	wordbooker_debugger("Getting Userdata ",$user_ID,0) ;
	$session = wordbooker_get_userdata($user_ID);
	if(!isset($session->access_token)){$session=new stdClass(); $session->access_token='1234';}
	if (strlen($session->access_token)>5) {
		wordbooker_debugger("Session found. Check validity ",$session->facebook_id,0) ;
		# We have a session ID so lets not get a new one
		# Put some session checking in here to make sure its valid
		try {
		wordbooker_debugger("Calling Facebook API : get current user ",$session->facebook_id,0) ;
		$ret_code= (string) wordbooker_me($session->facebook_id,$session->access_token);
		}
		catch (Exception $e) {
		# We don't have a good session so
		wordbooker_debugger("User Session invalid - clear down data "," ",0) ;
		#wordbooker_delete_user($user_ID,1);k
		return;
	}
		return $session->access_token;
	}
	else
	{
		# Are we coming back from a login with a session set?
		# This is for premium which does it all internally using the POST back from Facebook
		if (!defined('WORDBOOKER_PREMIUM')) {
			if(isset($_POST['session'])){
				$zz=htmlspecialchars_decode ($_POST['session'])."<br>";
				$newkey=explode("&expires_in=",$zz);
				$session->access_token=$newkey[0];
				$session->expires=0;
			}
		} else {
		# This is for regular which gets a stream back in the URL
		if (isset($_REQUEST['code'])){
			$x=wordbooker_get_access_token_from_code($_REQUEST['code']);
			$xx=explode("&expires=",$x);
			$newkey=explode("=",$xx[0]);}
			$session->access_token=$newkey[1];
			$session->expires=0;
			if ($xx[1]>0) {$session->expires=time()+$xx[1];}
		}
		# Lets Build up our session
		if (!isset($session->facebook_id)){$session->facebook_id=NULL;}
		if (!isset($session->access_token)){$session->access_token=NULL;}
		if (!isset($session->session_key)){$session->session_key=NULL;}
		if (!isset($session->sig)){$session->sig=NULL;}
		if (!isset($session->uid)){$session->uid=NULL;}
		try {
			wordbooker_debugger("Checking Session Status ",$session->facebook_id,0) ;
			$ret_code=wordbooker_me_status($session->facebook_id,$session->access_token);
		}
		catch (Exception $e) {
		# We don't have a good session so
			wordbooker_debugger("User Session invalid - clear down data "," ",0) ;
		}
		wordbooker_debugger("Checking session pass 2 "," ",0) ;
		if (strlen($session->access_token)>5){
		wordbooker_debugger("Session found. Store it ",(string) $ret_code->id,0) ;
			# Yes! so lets store it
		wordbooker_set_userdata(NULL, NULL, NULL,$session, (string) $ret_code->id);
			return $session->access_token;
		}
	}
}


function wordbooker_option_setup($wbuser) {
?>
	<h3><?php _e('Setup', 'wordbooker'); ?></h3>
	<div class="wordbooker_setup">
<?php
	$access_token=get_check_session();
	$response='code';
	$redirecturl=urlencode(get_bloginfo('wpurl')).'/wp-admin/options-general.php?page=wordbooker';
	if (!defined('WORDBOOKER_PREMIUM')) {
	$response='token';
	$redirecturl='https://wordbooker.tty.org.uk/index2.html?br='.urlencode(get_bloginfo('wpurl').'&fbid='.WORDBOOKER_FB_ID);
	}
	/*	$access_token=get_check_session();	$loginUrl2='https://www.facebook.com/dialog/oauth?client_id='.WORDBOOKER_FB_ID.'&redirect_uri=https://wordbooker.tty.org.uk/index2.html?br='.urlencode(get_bloginfo('wpurl').'&fbid='.WORDBOOKER_FB_ID).'&scope=publish_actions,publish_stream,user_status,create_note,read_stream,email,user_groups,manage_pages,read_friendlists&response_type=token';

	$loginUrl2='https://www.facebook.com/dialog/oauth?client_id='.WORDBOOKER_FB_ID.'&redirect_uri='.urlencode(get_bloginfo('wpurl')).'/wp-admin/options-general.php?page=wordbooker&scope=publish_actions,create_note,publish_stream,user_status,read_stream,email,user_groups,manage_pages,read_friendlists&response_type=code';
	*/
	$loginUrl2='https://www.facebook.com/dialog/oauth?client_id='.WORDBOOKER_FB_ID.'&redirect_uri='.$redirecturl.'&scope=publish_actions,create_note,publish_stream,user_status,read_stream,email,user_groups,manage_pages,read_friendlists&response_type='.$response;
	if ( is_null($access_token) ) {
		wordbooker_debugger("No session found - lets login and authorise "," ",0,99) ;
		if (!defined('WORDBOOKER_PREMIUM')) {
			echo '<br />'.__("Secure link ( may require you to add a new certificate for wordbooker.tty.org.uk ) Also you may get a warning about passing data on a non secure connection :",'wordbooker')."<br />";
		}
		echo '<br /><div id="u_0_0" class="pluginFaviconButton pluginFaviconButtonEnabled pluginFaviconButtonMedium"><i class="pluginFaviconButtonIcon img sp_login-button sx_login-button_medium"></i><span class="pluginFaviconButtonBorder"><span class="pluginFaviconButtonText fwb">
      <a href="'.$loginUrl2.'" STYLE="text-decoration: none;Color:white">&nbsp;'.__("Authorise Wordbooker",'wordbooker').'&nbsp;</a></span></span></div></p><p><br />';
		echo __("Or enter the Access Token you exported from another Worbooker install in the following box and click on 'Save Token'",'wordbooker');
		echo'<form action="" method="post"><input type="text" size="60" maxlength="400" name="imported_token" />';
		echo '&nbsp;&nbsp;<input type="submit" name="token_save" class="button-primary" value="'. __('Save Token', 'wordbooker').'" />';
		echo	'</p></form></div>';
	}
	 else  {
		wordbooker_debugger("Everything looks good so lets ask them to refresh "," ",0,99) ;
		wordbooker_renew_access_token();
			echo __("Wordbooker should now be authorised. Please click on the Reload Page Button",'wordbooker').'<br> <form action="options-general.php?page=wordbooker" method="post">';
		echo '<p style="text-align: center;"><input type="submit" name="perm_save" class="button-primary" value="'. __('Reload Page', 'wordbooker').'" /></p>';
		echo '</form> ';
	}
	$wplang=wordbooker_get_language();
	$wordbooker_settings = wordbooker_options();
	$fb_id=$wordbooker_settings["fb_comment_app_id"];
	if (strlen($fb_id)<6) {
	$fb_id=WORDBOOKER_FB_ID;
	}
	if (defined('WORDBOOKER_PREMIUM')) {
		$fb_id=WORDBOOKER_FB_ID;
	}
$efb_script = <<< EOGS
 <div id="fb-root"></div>
     <script type="text/javascript" defer="defer">
      window.fbAsyncInit = function() {
	FB.init({
	 appId  : '
EOGS;
$efb_script.=$fb_id;
$efb_script .= <<< EOGS
',
	  status : true, // check login status
	  cookie : true, // enable cookies to allow the server to access the session
	  xfbml  : true,  // parse XFBML
	  oauth:true
	});
      };
      (function() {
	var e = document.createElement('script');
EOGS;
$efb_script.= "e.src = document.location.protocol + '//connect.facebook.net/".$wplang."/all.js';";
$efb_script.= <<< EOGS
	e.async = true;
	document.getElementById('fb-root').appendChild(e);
      }());
    </script>
EOGS;
	echo $efb_script;
	echo "</div></div>";
}

function wordbooker_status($user_id)
{
	echo '<h3>'.__('Status', 'wordbooker').'</h3>';
	global  $user_ID,$blog_id;
	$wordbooker_user_settings_id="wordbookuser".$blog_id;
	$wordbookuser=get_user_meta($user_ID,$wordbooker_user_settings_id,true);
	if (isset($wordbookuser['wordbooker_disable_status']) && $wordbookuser['wordbooker_disable_status']=='on') {return;}
	global $shortcode_tags;
	$result = wordbooker_get_cache($user_id);
?>
	<div class="wordbooker_status"><div class="facebook_picture">
		<a href="<?php echo $result->url; ?>" target="facebook">
		<img src="<?php echo $result->pic; ?>" /></a></div><p>
		<a href="<?php echo $result->url; ?>"><?php echo $result->name; ?></a> ( <?php echo $result->facebook_id; ?> )<br /><br />
		<i><?php echo "<p>".preg_replace('/(\n)+/','<br /><br />',$result->status); ?></i></p>
		(<?php
			$current_offset=0;
		//	$current_offset = get_option('gmt_offset');
			echo date('D M j, g:i a', $result->updated+(3600*$current_offset)); ?>).
		<br /><br />
<?php
}

function wordbooker_option_status($wbuser) {
	global $user_ID;
	# Go to the cache and try to pull details
	$fb_info=wordbooker_get_cache($user_ID,'use_facebook,facebook_id,expires,access_token',1);
	# If we're missing stuff lets kick the cache.
	if (! isset($fb_info->facebook_id)) {
		 wordbooker_cache_refresh ($user_ID);
		$fb_info=wordbooker_get_cache($user_ID,'use_facebook,facebook_id,expires,access_token',1);
	}
	if ($fb_info->use_facebook==1) {
		echo"<p>".__('Wordbooker appears to be configured and working just fine', 'wordbooker')."</p><p>";
		echo "</p><p>".__("If you like, you can start over from the beginning (this does not delete your posting and comment history)", 'wordbooker').":</p><br />";
		if (unserialize($fb_info->expires)==0) {
		echo __("Your current Facebook Access token is non-expiring",'wordbooker');
		} else {
		echo __("Your current Facebook Access token will expire on : ",'wordbooker')."<b>".date('l F j, G:i',unserialize($fb_info->expires)+(3600*$current_offset))."</b>";}
		$token=$fb_info->facebook_id.'|'.$fb_info->expires.'|'.unserialize($fb_info->access_token);
		$crushed_token=base64_encode(gzcompress($token,9));
		$wbuser2= wordbooker_get_userdata($user_ID);
		$at=wordbooker_check_access_token($wbuser2->access_token);
		if(!$at->data->is_valid) {
			echo "<p><b>".__('WARNING : Your Access token is not valid  ', 'wordbooker')."</b>";
			if (isset($at->data->error->message)) {echo "( ".$at->data->error->message." )";}
			  echo "</p>";
		}
		wordbooker_check_permissions($wbuser,'');
	}
	else
	{
		echo "<p>".__('Wordbooker is able to connect to Facebook', 'wordbooker').'</p>';
	}
	echo'<form action="" method="post">';
	echo '<p style="text-align: center;"><input type="submit"  class="button-primary" name="reset_user_config"  value="'.__('Reset User Session', 'wordbooker').'" />';
	echo '&nbsp;&nbsp;<input type="submit" name="perm_save" class="button-primary" value="'. __('Refresh Status', 'wordbooker').'" />';
	if (isset($crushed_token)){
	echo '<input type="hidden" name="exported_token" value="'.$crushed_token.'" />';
	echo '&nbsp;&nbsp;<input type="button" name="dummy" class="button-primary" value="'. __('Export Access Token', 'wordbooker').'" onclick=alert(this.form.exported_token.value) />';}
	echo '</p></form></div>';
    $description=__("Recent Facebook Activity for this site", 'wordbooker');
    $iframe='<iframe src="http://www.facebook.com/plugins/activity.php?site='.get_bloginfo('url').'&amp;width=600&amp;height=400&amp;header=true&amp;colorscheme=light&amp;font&amp;border_color&amp;recommendations=true" style="border:none; overflow:hidden; width:600px; height:400px"></iframe>';
    $activity="<hr><h3>".$description.'</h3><p>'.$iframe."</p></div><div class='fb-shared-activity' data-width='300' data-height='300'></div>";
	$options = wordbooker_options();
   if (isset($options["wordbooker_fb_rec_act"])) { echo $activity; }
}

function wordbooker_version_ok($currentvers, $minimumvers) {
	#Lets strip out the text and any other bits of crap so all we're left with is numbers.
	$currentvers=trim(preg_replace("/[^0-9.]/ ", "", $currentvers ));
	$current = preg_split('/\D+/', $currentvers);
	$minimum = preg_split('/\D+/', $minimumvers);
	for ($ii = 0; $ii < min(count($current), count($minimum)); $ii++) {
		if ($current[$ii] < $minimum[$ii]) return false;
	}
	if (count($current) < count($minimum)) return false;
	return true;
}


function wordbooker_FILTER_FLAG_NO_LOOPBACK_RANGE($value) {
    // Fails validation for the following loopback IPv4 range: 127.0.0.0/8
    // This flag does not apply to IPv6 addresses
    return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? $value :
        (((ip2long($value) & 0xff000000) == 0x7f000000) ? FALSE : $value);
}

function wordbooker_domain_is_private($url)
{
	//$url='http://212.159.61.36/fred';
	//$url='http://192.168.0.26:8808/fred';
	//$url='http://::1/fred';
	$exploded_url=parse_url($url);
	$site_domain=$exploded_url['host'];
	if(WORDBOOKER_IGNORE_LOCAL) {
		return array($site_domain,'Forced Ignore','good');
		}
	if ($site_domain==':') {return array('IPV6 Local Host','IP address','bad');}
	if(filter_var($site_domain, FILTER_VALIDATE_IP)){
		if(filter_var($site_domain,  FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) && filter_var($site_domain, FILTER_CALLBACK, array('options' =>'wordbooker_FILTER_FLAG_NO_LOOPBACK_RANGE'))) {
			$return=array($site_domain,'IP Address','good'); } else {$return=array($site_domain,'IP Address','bad'); }
		return $return;
	} else {
		if (strtolower($site_domain)=='localhost')  {return array($site_domain,'Domain','bad');} else {
		return array($site_domain,'Domain','good');}
	}
}

function wordbooker_option_support() {
	global $wp_version,$wpdb,$user_ID,$facebook2;
	$wordbooker_settings=wordbooker_options();
?>
	<h3><?php _e('Support', 'wordbooker'); ?></h3><div class="wordbooker_support">
	<?php _e('For feature requests, bug reports, and general support :', 'wordbooker'); ?>
	<ul><li><?php _e('Check the ', 'wordbooker'); ?><a href=" <?php echo plugins_url(); ?>/wordbooker/documentation/wordbooker_user_guide.pdf" target="wordpress"><?php _e('User Guide', 'wordbooker'); ?></a>.</li>
	<li><?php _e('Check the ', 'wordbooker'); ?><a href="http://wordpress.org/extend/plugins/wordbooker/other_notes/" target="wordpress"><?php _e('WordPress.org Notes', 'wordbooker'); ?></a>.</li>
	<li><?php _e('Try the ', 'wordbooker'); ?><a href="http://wordbooker.tty.org.uk/forums/" target="facebook"><?php _e('Wordbooker Support Forums', 'wordbooker'); ?></a>.</li>
		<li><?php _e('Enhancement requests can be made at the ', 'wordbooker'); ?><a href="http://code.google.com/p/wordbooker/" target="facebook"><?php _e('Wordbooker Project on Google Code', 'wordbooker'); ?></a>.</li>
	<li><?php _e('Consider upgrading to the ', 'wordbooker'); ?><a href="http://wordpress.org/download/"><?php _e('latest stable release', 'wordbooker'); ?></a> <?php _e(' of WordPress. ', 'wordbooker'); ?></li>
	<li><?php _e('Read the release notes for Wordbooker on the ', 'wordbooker'); ?><a href="http://wordbooker.tty.org.uk/current-release/">Wordbooker</a> <?php _e('blog.', 'wordbooker'); ?></li>
	<li><?php _e('Check the Wordbooker ', 'wordbooker'); ?><a href="http://wordbooker.tty.org.uk/faqs/">Wordbooker</a> <?php _e('FAQs', 'wordbooker'); ?></li>
	</ul><br />
	<?php echo "<p>"; _e('Please provide the following information about your installation:', 'wordbooker'); echo "</p>"; ?>
	<?php echo "<br /><b>"; _e('Server Status', 'wordbooker'); echo "</b>"; ?>
	<ul>
<?php
	$hide=0;
	if (is_multisite() ) $hide=1;
	if (is_super_admin() ) $hide=0;
	if ($hide==1) { echo "<br />"; _e('<li> Multisite is enabled - Please talk to your Super Adminstrator for support information </li>', 'wordbooker'); } else {
	$active_plugins = get_option('active_plugins');
	$sitewide_active_plugins= get_site_option('active_sitewide_plugins');
	if (is_array($sitewide_active_plugins)){
	foreach($sitewide_active_plugins as $key => $value) {$active_plugins[]=$key;} }
	$plug_info=get_plugins();
	$plug_info=get_plugins();
	$phpvers = phpversion();
	$jsonvers=phpversion('json');
	if (!phpversion('json')) { $jsonvers="Installed but version not being returned";}
	$sxmlvers=phpversion('simplexml');
	if (!phpversion('simplexml')) { $sxmlvers=" No version being returned";}
	$t=$wpdb->get_results("select version() as ve",ARRAY_A);
	$mysqlvers =  $t[0]['ve'];
	$http_coding="No Multibyte support";
	$int_coding="No Multibyte support";
	$mb_language="No Multibyte support";
	if (function_exists('mb_convert_encoding')) {
		$http_coding=mb_http_output();
		$int_coding=mb_internal_encoding();
		$mb_language=mb_language();
	}
	 wordbooker_set_option('wordbooker_can_curl','0');
	$curlstatus=__("Curl is not installed - using WP_HTTP",'wordbooker');
	$curlv=__('Curl version not available','wordbooker');
	if (function_exists('curl_init')) {
	  $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/wordbooker');
	   curl_setopt($ch, CURLOPT_HEADER, 0);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	   curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/includes/fb_ca_chain_bundle.crt');
	   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
	   if (WORDBOOKER_IPV==6 && isset($wordbooker_settings['wordbooker_use_curl_4'])) {;
	   curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	   }
	   $mtime = microtime();
	   $mtime = explode(' ', $mtime);
	   $mtime = $mtime[1] + $mtime[0];
	   $starttime = $mtime;
	   $curlcontent = @curl_exec($ch);
	   $mtime = microtime();
	   $mtime = explode(" ", $mtime);
	   $mtime = $mtime[1] + $mtime[0];
	   $endtime = $mtime;
	   $totaltime = ($endtime - $starttime);
	   $x=json_decode($curlcontent);
	   $curlstatus=__("Curl is available but cannot access Facebook - using WP_HTTP instead (",'wordbooker').curl_errno($ch)." - ". curl_error($ch) ." )";
	   if ($x->name=="Wordbooker") {
	    $curlstatus=__("Curl is available and can access Facebook - All is OK, you can use the Curl Interface ( <i> Response Time was :
	    ".$totaltime." seconds </i> )",'wordbooker');
	     wordbooker_set_option('wordbooker_can_curl','1');
	   }
  	 curl_close($ch);
	  $curlv2=curl_version();
  	 $curlv=$curlv2['version'];
	}
	$new_wb_table_prefix=$wpdb->base_prefix;
	if (isset ($db_prefix) ) { $new_wb_table_prefix=$db_prefix;}
	$interface='WP HTTP';
	if((isset($wordbooker_settings['wordbooker_use_curl'])) && ($wordbooker_settings['wordbooker_use_curl']=='on')) {$interface='Curl';}
		$params = array(
			'redirection' => 0,
			'httpversion' => '1.1',
			'timeout' => 60,
			'user-agent' => apply_filters( 'http_headers_useragent', 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ) . ';wordbooker-' . WORDBOOKER_CODE_RELEASE ),
			'headers' => array( 'Connection' => 'close' , 'Content-type' => 'application/x-www-form-urlencoded'),
			'sslverify' => false
	);
	   $mtime = microtime();
	   $mtime = explode(' ', $mtime);
	   $mtime = $mtime[1] + $mtime[0];
	   $starttime = $mtime;
           $response = wp_remote_get('https://graph.facebook.com/wordbooker', $params );
	   $mtime = microtime();
	   $mtime = explode(" ", $mtime);
	   $mtime = $mtime[1] + $mtime[0];
	   $endtime = $mtime;
	   $totaltime = ($endtime - $starttime);
	   if ( is_wp_error($response) ) {$wphttpstatus=__("WP_HTTP cannot access Facebook - This is a problem",'wordbooker');}
 else {
	   $x=json_decode($response['body']);
	   if ($x->name=="Wordbooker") {
	    $wphttpstatus=__("WP_HTTP can access Facebook - All is OK ( <i> Response Time was : ".$totaltime." seconds </i> )",'wordbooker');
	    } else  {$wphttpstatus=__("WP_HTTP cannot communicate with Facebook - This is a problem ( <i> Response Time was : ".$totaltime." seconds </i> )",'wordbooker');}}
	   if (!defined('WORDBOOKER_PREMIUM')) { $wordbooker_premium='Not Activated';} else { $wordbooker_premium=WORDBOOKER_KEY;}
	$my_version=$plug_info['wordbooker/wordbooker.php']['Version'];
	$doy=date ( 'z');
	$curr_version="";
	$version_check=wordbooker_get_option('version_check');
	if($doy!=$version_check) {
		 $curr_version=wordbooker_check_version();
	     wordbooker_set_option('version_check', $doy );
	     if (strlen($curr_version)>10){$curr_version='0.0.0';}
	     if (strlen($curr_version)<4){$curr_version='0.0.0';}
		 wordbooker_set_option('current_release', $curr_version );
	}
	$stable_release=$wordbooker_settings['current_release'];
	$stable=$stable_release;
	if(strlen($stable)<6) {$stable='0.0.0';}
	$my_minor=substr($my_version,2);
	$stable_minor=substr($stable,2);
	$ver_diff=0;
	if ($stable_minor > 0) {
		$ver_diff=($stable_minor-$my_minor)*100;
		if ($ver_diff<0) {$ver_diff=0;}
		if ($ver_diff>5) {$ver_diff=6;}
		$ver_diff=round($ver_diff,0);
	}
	$woint = "9223372036854775807";
	$wpint = intval($int);
	if ($woint == 9223372036854775807) {
	  /* 64bit */
	 $wpbit = 64;
	}
	elseif ($woint == 2147483647) {
	  /* 32bit */
	  $wpbit = 32;
	}
	$wpbit .=' Bit';
	wordbooker_set_option('version_difference', $ver_diff );
	$ver_col=array(0=>'green',1=>'black', 2=>'blue', 3=>'yellow',4=>'orange',5=>'red',6=>'red');
	if(strlen($stable_release)<6) { $stable_release='Stable Version information not verified';}
	if ($stable_release=='0.0.0') { $stable_release='Unable to obtain stable version information';};
	$domain_info=wordbooker_domain_is_private(network_site_url());
	if($domain_info[2]=='bad') {
		$wordbooker_settings['wordbooker_public_url']=6;
		$wordbooker_settings['wordbooker_fake_publish']='on';
		wordbooker_set_option('wordbooker_fake_publish', $wordbooker_settings['wordbooker_fake_publish']);
	} else {
		$wordbooker_settings['wordbooker_public_url']=0;
	}
	wordbooker_set_option('wordbooker_public_url', $wordbooker_settings['wordbooker_public_url']);
	  $info = array(
		'Wordbooker' => "<span style='color:".$ver_col[$ver_diff]."';>".$plug_info['wordbooker/wordbooker.php']['Version']."</span>",
		'Wordbooker Code Base' => WORDBOOKER_CODE_RELEASE,
		'Wordbooker Current Stable Release' =>$stable_release,
		'Blog Domain'=>"<span style='color:".$ver_col[$wordbooker_settings['wordbooker_public_url']]."';>".$domain_info[0]." ( ".$domain_info[1]." ) </span>",
		'Wordbooker ID'=>WORDBOOKER_FB_ID,
		'Wordbooker Premium Key'=>$wordbooker_premium,
		'Wordbooker Schema' => $wordbooker_settings['schema_vers'],
		'WordPress' => $wp_version,
		'Table prefix' =>$new_wb_table_prefix,
		 'PHP' => $phpvers,
		 'PHP Memory Limit' => ini_get('memory_limit'),
		 'PHP Memory Usage (MB)' => memory_get_usage(true)/1024/1024,
		 'PHP Max Execution Time' => ini_get('max_execution_time'),
		'JSON Encode' => WORDBOOKER_JSON_ENCODE,
		'JSON Decode' => WORDBOOKER_JSON_DECODE,
		'WP_HTTP Status' => $wphttpstatus,
		'Curl Status' => $curlstatus,
		'Curl Version' => $curlv,
		'Facebook Interface' => $interface,
		'JSON Version' => $jsonvers,
		'SimpleXML library' => $sxmlvers." (". WORDBOOKER_SIMPLEXML.")",
		'HTTP Output Character Encoding'=>$http_coding,
		'Internal PHP Character Encoding'=>$int_coding,
		'64 or 32 bit'=>$wpbit,
		'MySQL' => $mysqlvers,
		);
	$version_errors = array();
	$phpminvers = '5.0';
	$mysqlminvers = '4.0';
	if (!wordbooker_version_ok($phpvers, $phpminvers)) {
		$version_errors['PHP'] = $phpminvers;
	}
	if ($mysqlvers != 'Unknown' && !wordbooker_version_ok($mysqlvers, $mysqlminvers)) {
		$version_errors['MySQL'] = $mysqlminvers;
	}

	foreach ($info as $key => $value) {
		$suffix = '';
		if (isset($version_errors[$key]) && ($minvers = $version_errors[$key])) {
			$suffix = " <span class=\"wordbooker_errorcolor\">" . " (need $key version $minvers or greater)" . " </span>";
		}
		echo "<li>$key: <b>$value</b>$suffix</li>";
	}
	if (!function_exists('simplexml_load_string')) {
		_e("<li>XML: your PHP is missing <code>simplexml_load_string()</code></li>", 'wordbooker');
	}

	$rows = $wpdb->get_results("show variables like 'character_set%'");
	foreach ($rows as $chardata){
		echo "<li> Database ". $chardata->Variable_name ." : <b> ".$chardata->Value ."</b></li>";
	}
	$rows = $wpdb->get_results("show variables like 'collation%'");
	foreach ($rows as $chardata){
		echo "<li> Database ". $chardata->Variable_name ." : <b> ".$chardata->Value ."</b></li>";
	}
	echo "<li> Server : <b>".$_SERVER['SERVER_SOFTWARE']."</b></li></ul><p><b>";
	_e("Active Plugins :", 'wordbooker');
	echo "</p><ul>";
	 foreach($active_plugins as $name) {
		if ( $plug_info[$name]['Title']!='Wordbooker') {
		echo "&nbsp;&nbsp;&nbsp;".$plug_info[$name]['Title']." ( ".$plug_info[$name]['Version']." ) <br />";}
	}
	echo "<br /></ul> ";
}
		_e("Wordbooker Table Status :", 'wordbooker');
	echo "</p><ul>";
	$table_array= array (WORDBOOKER_ERRORLOGS,WORDBOOKER_POSTLOGS,WORDBOOKER_USERDATA,WORDBOOKER_USERSTATUS,WORDBOOKER_POSTCOMMENTS,WORDBOOKER_PROCESS_QUEUE,WORDBOOKER_FB_FRIENDS,WORDBOOKER_FB_FRIEND_LISTS);
	foreach ($table_array as $table) {
		$sql="select count(*) from ".$table;
		$result=$wpdb->get_results($sql,ARRAY_N);
		if (!$result)
	{
	$tstat_string= sprintf("ERROR : table </b>'%s'<b> is missing ! - Please Deactivate and Re-activate the plugin from the Plugin Options Page", $table);
	}
	else {
	$tstat_string= sprintf("&nbsp;&nbsp;&nbsp;Table </b>'%s'<b> is present and contains %s rows", $table,$result[0][0]);
	 }
	echo "&nbsp;&nbsp;&nbsp;".$tstat_string."<br />";
	}
	echo "</b>";
	if (ADVANCED_DEBUG) { phpinfo(INFO_MODULES);}
?>
	</ul>
<?php
	if ($version_errors) {
?>
	<div class="wordbooker_errorcolor">
	<?php _e('Your system does not meet the', 'wordbooker'); ?> <a href="http://wordpress.org/about/requirements/"><?php _e('WordPress minimum requirements', 'wordbooker'); ?></a>. <?php _e('Things are unlikely to work.', 'wordbooker'); ?>
	</div>
<?php
	} else if ($mysqlvers == 'Unknown') {
?>
	<div>
	<?php _e('Please ensure that your system meets the', 'wordbooker'); ?> <a href="http://wordpress.org/about/requirements/"><?php _e('WordPress minimum requirements', 'wordbooker'); ?></a>.
	</div>
<?php
	}
echo "</div>";
}

function wordbooker_translate($text) {
	if (function_exists('qtrans_use')) {
		global $q_config;
		$text=qtrans_use($q_config['language'],$text);
	}
	return $text;
}

function wordbooker_return_images($post_content,$postid,$flag) {
	global $wordbooker_post_options,$wpdb,$post;
	if(is_null($postid)) {return;}
	$wordbooker_settings =wordbooker_options();
	# Grab the content of the post once its been filtered for display - this converts app tags into HTML so we can grab gallery images etc.
	$args = array(
	'post_type' => 'attachment',
	'numberposts' => -1,
	'post_status' => null,
	'post_parent' => $postid
	);
	$post_content2=" ";
	$attachments = get_posts( $args );
	if ( $attachments ) {
		foreach ( $attachments as $attachment ) {
			if ($attachment->post_type=='attachment') {
			  $junk=wp_get_attachment_image_src( $attachment->ID,'wordbooker_og');
			  $og_image=$junk[0];
			  if(!isset($og_image)) {
				$junk=wp_get_attachment_image_src( $attachment->ID,'large');
				$og_image=$junk[0];
			  }
			  if(!isset($og_image)) {$og_image=wp_get_attachment_url($attachment->ID);}
			  $post_content2 = ' <img src="' . $og_image . '"> ';
			}
		}
	}

	$processed_content ="!!!!  ".$post_content2."  ".apply_filters('the_content', $post_content)."    !!!";
	$yturls  = array();
	$matches_tn=array();
	$matches_ct=array();
	$matches=array();
	# Get the Yapb image for the post
	if (class_exists('YapbImage')) {
	   $siteUrl = get_option('siteurl');
	   if (substr($siteUrl, -1) != '/') $siteUrl .= '/';
	    $uri = substr($url, strpos($siteUrl, '/', strpos($url, '//')+2));
	    $WordbookerYapbImageclass = new YapbImage(null,$postid,$uri);
	    $WordbookerYapbImage=$WordbookerYapbImageclass->getInstanceFromDb($postid);
	    if (strlen($WordbookerYapbImage->uri)>6) {$yturls[]=get_bloginfo('url').$WordbookerYapbImage->uri;}
	}
	if ( function_exists( 'get_the_post_thumbnail' ) ) {
		if(!isset($wordbooker_settings['wordbooker_images_double_quote'])) {
			preg_match_all('/<img \s+ ([^>]*\s+)? src \s* = \s* [\'"](.*?)[\'"]/ix',get_the_post_thumbnail($postid), $matches_tn);
		}
		else {
			preg_match_all('/<img \s+ ([^>]*\s+)? src \s* = \s* ["](.*?)["]/ix',get_the_post_thumbnail($postid), $matches_tn);
		}
		if ($flag==1 && is_array($matches_tn[2]) && count($matches_tn[2])>0) {wordbooker_debugger("Getting the thumnail image",$matches_tn[2][0],$postid,80) ;}
		if(isset($matches_tn[2][0])) {$matches[]=$matches_tn[2][0];}
	}
	$meta_tag_scan=explode(',',$wordbooker_settings['wordbooker_meta_tag_scan']);
	foreach($meta_tag_scan as $meta_tag) {
		$xx=get_post_meta($postid, trim($meta_tag), true);
		if(strlen($xx)>= 5 ) {$matches_ct[]=trim($xx);}
		if ($flag==1) {wordbooker_debugger("Getting image from custom meta : ".$meta_tag,$xx,$postid,80) ;}
	}
	$matches=$matches_ct;
	if ( function_exists( 'get_the_post_thumbnail' ) ) {
		$matches=array_merge($matches_ct,$matches_tn[2]);
	}

	# If the user only wants the thumbnail then we can simply not do the skim over the processed images
	if (! isset($wordbooker_post_options["wordbooker_thumb_only"]) ) {
		if ($flag==1) {wordbooker_debugger("Getting the rest of the images "," ",$postid,80) ;}
		preg_match_all('/<img \s+ ([^>]*\s+)? src \s* = \s* ["\'](.*?)["\']/ix',$processed_content, $matched);
		$x=strip_shortcodes($post_content);
		$regexes = array(
	    '#<object[^>]+>.+?(?:https?:)?//www\.youtube(?:\-nocookie)?\.com/[ve]/([A-Za-z0-9\-_]+).+?</object>#s', // Old standard YouTube embed
	    '#(?:https?:)?//www\.youtube(?:\-nocookie)?\.com/[ve]/([A-Za-z0-9\-_]+)#', // More comprehensive search for old YouTube embed (probably can be removed)
	    '#(?:https?:)?//www\.youtube(?:\-nocookie)?\.com/embed/([A-Za-z0-9\-_]+)#', // YouTube iframe, the new standard since at least 2011
	    '#(?:https?(?:a|vh?)?://)?(?:www\.)?youtube(?:\-nocookie)?\.com/watch\?.*v=([A-Za-z0-9\-_]+)#', // Any YouTube URL. After http(s) support a or v for Youtube Lyte and v or vh for Smart Youtube plugin
	    '#(?:https?(?:a|vh?)?://)?youtu\.be/([A-Za-z0-9\-_]+)#', // Any shortened youtu.be URL. After http(s) a or v for Youtube Lyte and v or vh for Smart Youtube plugin
	    '#<div class="lyte" id="([A-Za-z0-9\-_]+)"#' // YouTube Lyte
		);
		foreach($regexes as $regex) {
			preg_match_all($regex, $x, $matches4 );
			$matches3[]=$matches4[1];
		}
		if (is_array($matches3) && count($matches3)>0) {
			foreach ($matches3 as $key ) {
			if(is_array($key) && count($key)>0 && strlen($key[0])>1){
				$yturls[]='http://img.youtube.com/vi/'.$key[0].'/0.jpg';
			}
			}
		}
		if ( function_exists( 'get_video_thumbnail' )) {
			$yturls[] = get_video_thumbnail();
		}
	}
	if ( function_exists( 'get_the_post_thumbnail' ) ) {
		# If the thumb only is set then pulled images is just matches
		if (!isset($wordbooker_settings["wordbooker_meta_tag_thumb"])) {
			if (! isset($wordbooker_post_options["wordbooker_thumb_only"]) ) {
				if ($flag==1) {wordbooker_debugger("Setting image array to be both thumb and the post images "," ",$postid,80) ;}
			 	$pulled_images=@array_merge($matched[2],$yturls,$matches);
			}
			else {
				if ($flag==1) {wordbooker_debugger("Setting image array to be just thumb "," ",$postid,80) ;}
				$pulled_images[]=$matches_tn[2][0];
			}
		}
	}

	if (isset($wordbooker_settings["wordbooker_meta_tag_thumb"]) && isset($wordbooker_post_options["wordbooker_thumb_only"]) ) {
	if ($flag==1) {wordbooker_debugger("Setting image array to be just thumb from meta. "," ",$postid,80) ;}
	$pulled_images[]=$matches_ct[2];}

	else {
		if ($flag==1) {wordbooker_debugger("Setting image array to be post and thumb images. "," ",$postid,80) ;}
		if (is_array($matched[2])) {$pulled_images[]=array_merge($matches,$matched[2]);}
		if (is_array($matched[2]) && is_array($yturls)) {$pulled_images=array_merge($matches,$matched[2],$yturls);}
	}
	$images = array();
	if (is_array($pulled_images)) {
		foreach ($pulled_images as $ii => $imgsrc) {
			//var_dump($imgsrc);
			if ($imgsrc) {
				if (stristr(substr($imgsrc, 0, 8), '://') ===false) {
					/* Fully-qualify src URL if necessary. */
					$scheme = $_SERVER['HTTPS'] ? 'https' : 'http';
					$new_imgsrc = "$scheme://". $_SERVER['SERVER_NAME'];
					if ($imgsrc[0] == '/') {
						$new_imgsrc .= $imgsrc;
					}
					$imgsrc = $new_imgsrc;
				}
				$images[] =  $imgsrc;
			}
		}
	}
	/* Pull out <wpg2> image tags. */
	$wpg2_g2path = get_option('wpg2_g2paths');
	if ($wpg2_g2path) {
		$g2embeduri = $wpg2_g2path['g2_embeduri'];
		if ($g2embeduri) {
			preg_match_all('/<wpg2>(.*?)</ix', $processed_content,
				$wpg_matches);
			foreach ($wpg_matches[1] as $wpgtag) {
				if ($wpgtag) {
					$images[] = $g2embeduri.'?g2_view='.'core.DownloadItem'."&g2_itemId=$wpgtag";
				}
			}
		}
	}
	$wordbooker_settings =wordbooker_options();
	if (count($images)>0){
		# Remove duplicates
		$images=array_unique($images);
		# Strip images from various plugins
		$images=wordbooker_strip_images($images,$flag,$postid);
		# And limit it to 10 pictures to keep Facebook happy.
		$images = array_slice($images, 0, 10);

	}
	if (count($images)==0) {
		if (isset($wordbooker_settings['wordbooker_use_this_image']))  {
			$images[]=$wordbooker_settings['wb_wordbooker_default_image'];
			if ($flag==1) {wordbooker_debugger("No Post images found so using open graph default to keep Facebook happy ",'',$postid,90) ;}
			}
		else {
			$x=plugins_url().'/wordbooker/includes/wordbooker_blank.jpg';
			$images[]=$x;
			if ($flag==1) {wordbooker_debugger("No Post images found so loading blank to keep Facebook happy ",'',$postid,90) ;}
			}
		}
	$images=array_unique($images);
	$post_link_share = get_permalink($postid);
	foreach ($images as $single) {
		$images_array[]=array(
				'type' => 'image',
				'src' => $single,
				'href' => $post_link_share,
				);
	}
        return $images_array;
}

function wordbooker_strip_images($images,$flag,$postid)
{
	global $post;
	$newimages = array();
	$image_types= array ('jpg','jpeg','gif','png','tif','bmp','jpe','php','svg');
	$strip_array= array ('addthis.com','gravatar.com','zemanta.com','wp-includes','plugins','favicon.ico','facebook.com','themes','mu-plugins','fbcdn.net');
	foreach($images as $single){
		if (is_array($single)) {break;}
		$ok=true;
		$file_extension = trim(strtolower(substr($single , strrpos($single , '.') +1,strlen($single))));
		if (in_array($file_extension,$image_types)) {
			foreach ($strip_array as $strip_domain) {
				if ($flag==1) {wordbooker_debugger("Looking for ".$strip_domain." in ".$single," ",$postid,80) ;}
				if (stripos($single,$strip_domain)){$ok=false;break;}
			}
			if ($ok) { if (!in_array($single,$newimages)){$newimages[]=$single;}}
			else { wordbooker_debugger("Found a match so dump the image",$single,$postid,80); }
		}
		else {
		wordbooker_debugger("Image URL ".$single." ( ".$file_extension." ) not valid "," ",$postid,90) ;}
	}
	$images=$newimages;
	$newimages = array();
	foreach($images as $single){
	  if (preg_match('/.*googleusercontent.*proxy.*url=(.+)/ix', $single, $matches_google_proxy) === 1) {
	    $newimages[] = urldecode($matches_google_proxy[1]);
	  } else {
	    $newimages[] = $single;
	  }
	}
	return $newimages;
}

 function wordbooker_post_excerpt($excerpt, $maxlength,$doyoutube=1) {
	global $wordbooker_post_options;
	if (!isset($maxlength)) {$maxlength=$wordbooker_post_options['wordbooker_extract_length'];}
	if (!isset($maxlength)) {$maxlength=256;}
	$excerpt = trim($excerpt);
	# Support for various Canalplan AC plugin calls - hey its my other plugin so why not!
	if (function_exists('canal_stats')) $excerpt =canal_stats($excerpt);
	if (function_exists('canal_trip_stats')) $excerpt =canal_trip_stats($excerpt);
	if (function_exists('canal_linkify_name')) $excerpt =canal_linkify_name($excerpt);
	if (function_exists('canal_blogroute_insert')) $excerpt =canal_blogroute_insert($excerpt);
	# Remove any other short codes
	if (function_exists('strip_shortcodes')) {
		$excerpt = strip_shortcodes($excerpt);
	}
	# Now lets strip any tags which dont have balanced ends
	$open_tags="[simage,[[CPR,[[CPT,[gallery,[imagebrowser,[slideshow,[tags,[albumtags,[singlepic,[album,[contact-form,[contact-field,[/contact-form,<strong>Google+:</strong>,[aartikel,[jwplayer";
	$close_tags="],]],]],],],],],],],],],],],Daniel Treadwell</a>.</i>,[/aartikel,]";
	$open_tag=explode(",",$open_tags);
	$close_tag=explode(",",$close_tags);
	foreach (array_keys($open_tag) as $key) {
		if (preg_match_all('!' . preg_quote($open_tag[$key]) . '(.*?)' . preg_quote($close_tag[$key]) .'!i',$excerpt,$matches)) {
			$excerpt=str_replace($matches[0],"" , $excerpt);
		 }
	}
	# Strip wpg tags
	$excerpt = preg_replace('#(<wpg.*?>).*?(</wpg2>)#', '$1$2', $excerpt);
	# Translate the text
	$excerpt=wordbooker_translate($excerpt);
	# Remove HTML tags as we don't want them - FB don't support HTML tags.
	$excerpt = strip_tags($excerpt);
	# Now lets strip off the youtube stuff.
	preg_match_all( '#http://(www.youtube|youtube|[A-Za-z]{2}.youtube)\.com/(watch\?v=|w/\?v=|\?v=)([\w-]+)(.*?)player_embedded#i', $excerpt, $matches );
	$excerpt=str_replace($matches[0],"" , $excerpt);
	preg_match_all( '#http://(www.youtube|youtube|[A-Za-z]{2}.youtube)\.com/(watch\?v=|w/\?v=|\?v=|embed/)([\w-]+)(.*?)#i', $excerpt, $matches );
	$excerpt=str_replace($matches[0],"" , $excerpt);
	if (strlen($excerpt) > $maxlength) {
		# If we've got multibyte support then we need to make sure we get the right length - Thanks to Kensuke Akai for the fix
		if(function_exists('mb_strimwidth')){
			$excerpt=mb_strimwidth($excerpt, 0, $maxlength);
			$arr=preg_split("/\s+(?=\S*+$)/",$excerpt);
			$excerpt=$arr[0]." ...";
		}
		else {
			$excerpt=current(explode("SJA26666AJS", wordwrap($excerpt, $maxlength, "SJA26666AJS")))." ...";
		}
	}
	return $excerpt;
}

function wordbooker_update_post_meta($post) {
	$wordbooker_disable_og=wordbooker_get_option('wordbooker_fb_disable_og');
	$wordbooker_settings =wordbooker_options();
	if (!isset($wordbooker_disable_og)){
		$images=wordbooker_return_images($post->post_content,$post->ID,0);
		if (is_array($images)){
			$ogimage=$images[0]['src'];
			$extraog=array_slice($images,1,4);
			$junk="";
			foreach($extraog as $tagimg) {
			  $junk.=$tagimg['src'].',';
			}
			update_post_meta($post->ID, '_wordbooker_ogimages', $junk);
			update_post_meta($post->ID, '_wordbooker_thumb', $ogimage);
		}
	}
	$excerpt=wordbooker_post_excerpt($post->post_content,$wordbooker_settings['wordbooker_extract_length']);
	update_post_meta($post->ID, '_wordbooker_extract', $excerpt);
}
?>