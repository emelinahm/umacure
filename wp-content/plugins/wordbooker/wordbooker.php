<?php
/*
Plugin Name: Wordbooker
Plugin URI: http://wordbooker.tty.org.uk
Description: Provides integration between your blog and your Facebook account. Navigate to <a href="options-general.php?page=wordbooker">Settings &rarr; Wordbooker</a> for configuration.
Author: Steve Atty
Author URI: http://wordbooker.tty.org.uk
Version: 2.2.1
*/

 /*
 * Copyright 2013 Steve Atty (email : posty@tty.org.uk)
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


if (file_exists(WP_CONTENT_DIR."/uploads/wordbooker_premium.php")){
	@include(WP_CONTENT_DIR."/uploads/wordbooker_premium.php");
}

global $table_prefix, $wp_version,$wpdb,$db_prefix,$wbooker_user_id;
$wbooker_user_id=0;
#error_reporting (E_ALL | E_NOTICE | E_STRICT | E_DEPRECATED);

function wordbooker_global_definitions() {
	global $table_prefix, $wp_version,$wpdb,$db_prefix,$wbooker_user_id;
	$wbooker_user_id=0;
	define('WORDBOOKER_CODE_RELEASE',"2.2.1 - Listen To Seashells They Know Everything");
	# For Troubleshooting
	define('ADVANCED_DEBUG',false);
	define('WORDBOOKER_DEBUG', false);
	define('WORDBOOKER_TESTING', false);
	if (!defined('WORDBOOKER_PREMIUM')) {
		# Wordbooker - live
		define('WORDBOOKER_FB_ID', '254577506873');
		define('APP_TITLE','Wordbooker');
		define('WORDBOOKER_APPLICATION_NAME','Wordbooker');
		define('OPENGRAPH_NAMESPACE','wordbooker');
		define('WORDBOOKER_KEY','Public');
		define('WORDBOOKER_IGNORE_LOCAL',false);
		define('WORDBOOKER_NEVER_LOG',false);
	}
	define('WORDBOOKER_FB_APIVERSION', '1.0');
	define('WORDBOOKER_FB_DOCPREFIX','http://wiki.developers.facebook.com/index.php/');
	define('WORDBOOKER_FB_PUBLISH_STREAM', "publish_stream");
	define('WORDBOOKER_FB_READ_STREAM', "read_stream");
	define('WORDBOOKER_FB_STATUS_UPDATE',"status_update");
	define('WORDBOOKER_FB_CREATE_NOTE',"create_note");
	define('WORDBOOKER_FB_OFFLINE_ACCESS',"offline_access");
	define('WORDBOOKER_FB_MANAGE_PAGES',"manage_pages");
	define('WORDBOOKER_FB_PHOTO_UPLOAD',"photo_upload");
	define('WORDBOOKER_FB_VIDEO_UPLOAD',"video_upload");
	define('WORDBOOKER_FB_READ_FRIENDS',"read_friendlists");
	define('WORDBOOKER_FB_USER_PHOTOS',"user_photos");
	define('WORDBOOKER_SETTINGS','wordbooker_settings');
	define('WORDBOOKER_OPTION_SCHEMAVERS', 'schema_vers');
	define('WORDBOOKER_SETTINGS_HEX','df04f22f3239fb75bf787f440e726f31');
	define('WORDBOOKER_USER_AGENT','WordPress/' . $wp_version . '; wordbooker-' .WORDBOOKER_CODE_RELEASE );
	define('WORDBOOKER_SCHEMA_VERSION', '5.7');
	define('WORDBOOKER_SETTINGS_PAGENAME', 'wordbooker');

	$new_wb_table_prefix=$wpdb->base_prefix;
	if (isset ($db_prefix) ) { $new_wb_table_prefix=$db_prefix;}
	define('WORDBOOKER_ERRORLOGS', $new_wb_table_prefix . 'wordbooker_errorlogs');
	define('WORDBOOKER_POSTLOGS', $new_wb_table_prefix . 'wordbooker_postlogs');
	define('WORDBOOKER_USERDATA', $new_wb_table_prefix . 'wordbooker_userdata');
	define('WORDBOOKER_USERSTATUS', $new_wb_table_prefix . 'wordbooker_userstatus');
	define('WORDBOOKER_POSTCOMMENTS', $new_wb_table_prefix . 'wordbooker_postcomments');
	define('WORDBOOKER_PROCESS_QUEUE', $new_wb_table_prefix . 'wordbooker_process_queue');
	define('WORDBOOKER_FB_FRIENDS', $new_wb_table_prefix . 'wordbooker_fb_friends');
	define('WORDBOOKER_FB_FRIEND_LISTS', $new_wb_table_prefix . 'wordbooker_fb_friend_lists');
	define('WORDBOOKER_MINIMUM_ADMIN_LEVEL', 'edit_posts');	/* Contributor role or above. */
	define('WORDBOOKER_SETTINGS_URL', 'options-general.php?page=' . WORDBOOKER_SETTINGS_PAGENAME);

	$wordbooker_wp_version_tuple = explode('.', $wp_version);
	define('WORDBOOKER_WP_VERSION', $wordbooker_wp_version_tuple[0] * 10 + $wordbooker_wp_version_tuple[1]);

	if (function_exists('json_encode')) {
		define('WORDBOOKER_JSON_ENCODE', 'PHP');
	} else {
		define('WORDBOOKER_JSON_ENCODE', 'Wordbook');
	}

	if (function_exists('json_decode') ) {
		define('WORDBOOKER_JSON_DECODE', 'PHP');
	} else {
		define('WORDBOOKER_JSON_DECODE', 'Wordbooker');
	}
	if (function_exists('simplexml_load_string') ) {
		define('WORDBOOKER_SIMPLEXML', 'provided by PHP');
	} else {
		define('WORDBOOKER_SIMPLEXML', 'is missing - this is a problem');
	}
	if (WORDBOOKER_JSON_DECODE == 'Wordbooker') {
		function json_decode($json){
			$comment = false;
			$out = '$x=';

			for ($i=0; $i<strlen($json); $i++)
			{
			if (!$comment)
			{
				if ($json[$i] == '{')        $out .= ' array(';
				else if ($json[$i] == '}')    $out .= ')';
				else if ($json[$i] == ':')    $out .= '=>';
				else                         $out .= $json[$i];
			}
			else $out .= $json[$i];
			if ($json[$i] == '"')    $comment = !$comment;
			}
			eval($out . ';');
			return $x;
		}
	}
	if (WORDBOOKER_JSON_ENCODE == 'Wordbooker') {
		function json_encode($var) {
			if (is_array($var)) {
				$encoded = '{';
				$first = true;
				foreach ($var as $key => $value) {
					if (!$first) {
						$encoded .= ',';
					} else {
						$first = false;
					}
					$encoded .= "\"$key\":"
						. json_encode($value);
				}
				$encoded .= '}';
				return $encoded;
			}
			if (is_string($var)) {
				return "\"$var\"";
			}
			return $var;
		}
	}
	if (function_exists('curl_version')) {
		$curlv2=curl_version();
		$curlv=$curlv2['version'];
		$bitfields = Array('CURL_VERSION_IPV6');
		foreach($bitfields as $feature)
		{
		  if ($curlv2['features'] & constant($feature)) {define('WORDBOOKER_IPV', '6');} else { define('WORDBOOKER_IPV', '4');}
		}
	} else {define('WORDBOOKER_IPV', '4');}


	define('GLOBAL_DEFINITIONS_NOT_CALLED','not a problem');
}

if (@GLOBAL_DEFINITIONS_NOT_CALLED == 'GLOBAL_DEFINITIONS_NOT_CALLED') {
wordbooker_global_definitions();
}

function wordbooker_delete_option($key) {
	$options = wordbooker_options();
	unset($options[$key]);
	update_option(WORDBOOKER_SETTINGS, $options);
}

function wordbooker_rrmdir($dir)
{
    if (is_dir($dir)) // ensures that we actually have a directory
    {
        $objects = scandir($dir); // gets all files and folders inside
        foreach ($objects as $object)
        {
            if ($object != '.' && $object != '..')
            {
                if (filetype($dir . '/' . $object) === 'dir')
                {
                    // if we find a directory, do a recursive call
                    wordbooker_rrmdir($dir . '/' . $object);
                }
                else
                {
                    // if we find a file, simply delete it
                    unlink($dir . '/' . $object);
                }
            }
        }
        // the original directory is now empty, so delete it
        rmdir($dir);
    }
}

function wordbooker_admin_load() {
     global $user_ID,$wpdb,$blog_id;
	if (isset($POST['reset_user_config'])){
		wordbooker_delete_userdata();
		return;
	}
	if (isset($_POST["imported_token"])) {
		$uncrushed_token=gzuncompress(base64_decode($_POST["imported_token"]));
		$token_parts=explode('|',$uncrushed_token);
		$sql=$wpdb->prepare("delete from  " . WORDBOOKER_USERDATA . " where user_id=%d",$user_ID);
		$result = $wpdb->query($sql);
		$sql= $wpdb->prepare("insert into " . WORDBOOKER_USERDATA . " (user_id,expires,facebook_id,blog_id,access_token) values (%d,%s,%s,%d,%s)",$user_ID,$token_parts[1] ,$token_parts[0],$blog_id, serialize($token_parts[2]));
		$result = $wpdb->query($sql);
		wordbooker_cache_refresh($user_ID);
		wp_redirect(WORDBOOKER_SETTINGS_URL);
	}
	if (!isset($_POST['action'])) return;
	switch ($_POST['action']) {
	case 'delete_userdata':
		# Catch if they got here using the perm_save/cache refresh
		if ( ! isset ($_POST["perm_save"])) {
			wordbooker_delete_userdata();
		}
		wp_redirect(WORDBOOKER_SETTINGS_URL);
		break;
	case 'clear_errorlogs':
		wordbooker_clear_diagnosticlogs();
		wp_redirect(WORDBOOKER_SETTINGS_URL);
		break;
	case 'clear_diagnosticlogs':
		wordbooker_clear_diagnosticlogs();
		wp_redirect(WORDBOOKER_SETTINGS_URL);
		break;
	}
	exit;
}

function wordbooker_admin_head() {
?>
	<style type="text/css">
	.wordbooker_setup { margin: 0 3em; }
	.wordbooker_notices { margin: 0 3em; }
	.wordbooker_status { margin: 0 3em; }
	.wordbooker_errors { margin: 0 3em; }
	.wordbooker_thanks { margin: 0 3em; }
	.wordbooker_thanks ul { margin: 1em 0 1em 2em; list-style-type: disc; }
	.wordbooker_support { margin: 0 3em; }
	.wordbooker_support ul { margin: 1em 0 1em 2em; list-style-type: disc; }
	.facebook_picture {
		float: right;
		border: 1px solid black;
		padding: 2px;
		margin: 0 0 1ex 2ex;
	}
	.wordbooker_errorcolor { color: #c00; }
	table.wordbooker_errorlogs { text-align: center; }
	table.wordbooker_errorlogs th, table.wordbooker_errorlogs td {
		padding: 0.5ex 1.5em;
	}
	table.wordbooker_errorlogs th { background-color: #999; }
	table.wordbooker_errorlogs tr.error td { background-color: #f66; }
	table.wordbooker_errorlogs tr.diag td { background-color: #CCC; }
	.DataForm label
	{
	    display: inline-block;
	    vertical-align:top;
	}
	.pluginFaviconButton{display:inline-block;background-color:#5f78ab;color:#fff;cursor:pointer;vertical-align:top}
.pluginFaviconButtonIcon, .pluginFaviconButtonIconActive, .pluginFaviconButtonIconThrobber, .pluginFaviconButtonIconThrobber .img, .pluginFaviconButtonIconDisabled{vertical-align:top}
.pluginFaviconButton:active .pluginFaviconButtonIcon, .pluginFaviconButtonEnabled .pluginFaviconButtonIconActive, form.async_saving .pluginFaviconButtonIcon, form.async_saving .pluginFaviconButton:active .pluginFaviconButtonIconActive, .pluginFaviconButtonIconThrobber{display:none}
.pluginFaviconButtonEnabled:active .pluginFaviconButtonIconActive, form.async_saving .pluginFaviconButtonIconThrobber{display:inline-block}
.pluginFaviconButtonBorder, form.async_saving .pluginFaviconButtonBorder:active{display:inline-block;border-top:1px solid #29447e;border-right:1px solid #29447e;border-bottom:1px solid #1a356e}
.pluginFaviconButtonText, form.async_saving .pluginFaviconButtonEnabled:active .pluginFaviconButtonText{display:inline-block;border-top:1px solid #879ac0;white-space:nowrap}
.pluginFaviconButtonEnabled:active .pluginFaviconButtonText{border-top-color:#50609c}
.fcb{color:#fff}
.fcg{color:gray}
.fcw{color:#fff}.sp_login-button{background-image:url(http://static.ak.fbcdn.net/rsrc.php/v2/yx/r/j_i0CTUUUEe.png);background-size:auto;background-repeat:no-repeat;display:inline-block;height:39px;width:39px}
.sx_login-button_medium{width:22px;height:22px;background-position:0 -132px}
.sx_login-button_mediuma{width:22px;height:22px;background-position:0 -155px}
i.img u{position:absolute;top:-9999999px}.uiLayer{outline:none}._1qp5{outline:none}
	</style>
	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=254577506873";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php
}

function wordbooker_option_notices() {
	global $user_ID, $wp_version,$blog_id;
	wordbooker_upgrade();
	$doy=date ( 'z');
	//$doy="22hhfff";
	$schemacheck=wordbooker_get_option('schema_check');
	// If we've not run the schema check today then lets run it - just in case someone has done something stupid.
	if($doy!=$schemacheck) {wordbooker_db_crosscheck();}
	wordbooker_trim_postlogs();
	wordbooker_trim_errorlogs();
	$errormsg = null;
	if (!function_exists('json_decode')) {
	 	$errormsg .=   __('Wordbooker needs the JSON PHP extension.  Please install / enable it and try again ','wordbooker').'<br />';
	}
	if (!function_exists('simplexml_load_string')) {
		$errormsg .=   __('Your PHP install is missing <code>simplexml_load_string()</code> ','wordbooker')."<br />";
	}
	$wbuser = wordbooker_get_userdata($user_ID);
	if (!is_object($wbuser) || strlen($wbuser->access_token)< 50 ) {
		$errormsg .=__("Wordbooker needs to be set up", 'wordbooker')."<br />";
	} else if ($wbuser->facebook_error) {
		$method = $wbuser->facebook_error['method'];
		$error_code = $wbuser->facebook_error['error_code'];
		$error_msg = $wbuser->facebook_error['error_msg'];
		$post_id = $wbuser->facebook_error['postid'];
		$suffix = '';
		if ($post_id != null && ($post = get_post($post_id))) {
			wordbooker_delete_from_postlogs($post_id,$blog_id);
			$suffix = __('for', 'wordbooker').' <a href="'. get_permalink($post_id) . '">'. get_the_title($post_id) . '</a>';
		}
		$errormsg .= sprintf(__("<a href='%s'>Wordbooker</a> failed to communicate with Facebook" . $suffix . ": method = %s, error_code = %d (%s). Your blog is OK, but Facebook didn't get the update.", 'wordbooker'), " ".WORDBOOKER_SETTINGS_URL," ".wordbooker_hyperlinked_method($method)," ".$error_code," ".$error_msg)."<br />";
		wordbooker_clear_userdata_facebook_error($wbuser);
	}
	if ($errormsg) {
?>
	<h3><?php _e('Notices', 'wordbooker'); ?></h3><div class="wordbooker_notices" style="background-color: #f66;"><p><?php echo $errormsg; ?></p></div>
<?php
	}
}

function wordbooker_footer($blah)
{
	if (is_404()) {
		echo "\n<!-- Wordbooker code revision : ".WORDBOOKER_CODE_RELEASE." -->\n";
		return;
	}
	$wordbooker_settings = wordbooker_options();
	if (!isset($wordbooker_settings['wordbooker_fb_disable_api'])) {
		$wplang=wordbooker_get_language();
		$fb_id=$wordbooker_settings["fb_comment_app_id"];
		if (strlen($fb_id)<6) {
			$fb_id=WORDBOOKER_FB_ID;
		}
		if (defined('WORDBOOKER_PREMIUM')) {
			$fb_id=WORDBOOKER_FB_ID;
		}
		$efb_script = '<div id="fb-root"></div> <script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return;js = d.createElement(s); js.id = id; ';
		$efb_script.= 'js.src = "//connect.facebook.net/'.$wplang.'/all.js#xfbml=1&appId='.$fb_id.'";';
		$efb_script.= "fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>";
		echo $efb_script;
	}
	echo "\n<!-- Wordbooker code revision : ".WORDBOOKER_CODE_RELEASE." -->\n";
	return $blah;
}

function wordbooker_header($blah){
	if (is_404()) {return;}
	global $post;
	# Stops the code firing on non published posts
	if ('publish' != get_post_status($post->ID)) {return;}
	$wordbooker_settings = wordbooker_options();
	# Now we just call the wordbooker_og_tags function.
	if (!isset ( $wordbooker_settings['wordbooker_fb_disable_og'])) { wordbooker_og_tags(); }
	return $blah;
}

function wordbooker_get_comments_from_fb_box($comment_approve,$commemail,$fb_comment_box_import) {
	global $wpdb,$blog_id,$post;
	$url = get_permalink();
	$comments = wordbooker_fb_get_box_comments($url);
	if(!isset($comments->$url)) {return "";}
	$output = '<noscript><ol class="commentlist">';
	$current_offset = get_option('gmt_offset');
	foreach ($comments->$url->comments->data as $key => $single_comment) {
		$ts = strtotime($single_comment->created_time);
		$output.= '<li id="'.esc_attr( 'fb-comment-'.$key ).'">';
		$output.='<p><a href="'.esc_url('http://www.facebook.com/'.$single_comment->from->id,array('http','https')) .'"> '.esc_html( $single_comment->from->name ).' </a>:</p>';
		$output.='<p class="commentdata">'.date('F jS, Y',$ts) .' at '.date('g:i a',$ts).'</p> ';
		$output.=$single_comment->message.' </li>';
		if ($fb_comment_box_import==1){
			$sql=$wprdb->prepare("Select fb_comment_id from ".WORDBOOKER_POSTCOMMENTS." where fb_comment_id=%s",$single_comment->id);
			$commq=$wpdb->query($sql);
			if(!$commq) {
				$time = date("Y-m-d H:i:s",$ts);
				$atime = date("Y-m-d H:i:s",$ts+(3600*$current_offset));
				$data = array(
					'comment_post_ID' => $post->ID,
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
				update_comment_meta($newComment, “akismet_result”, true);
				$user_id=0;
				$sql=$wpdb->prepare("Insert into ".WORDBOOKER_POSTCOMMENTS." (fb_post_id,user_id,comment_timestamp,wp_post_id,blog_id,wp_comment_id,fb_comment_id,in_out) values (%s,%d,%d,%d,%d,%d,%s,'in' )",$single_comment->id,$user_id,strtotime($single_comment->created_time),$post->ID,$blog_id,$newComment,$single_comment->id);
				$commq2=$wpdb->query($sql);
			}
		}
	}
	$output.= '</ol></noscript>';
	return $output;
}

function wordbooker_fb_link_friend($atts,$content=null) {
	$fburl='<a href="https://www.facebook.com/'.$atts['id'].'">'.$atts['name'].'</a>';
	return $fburl;
}

function wordbooker_get_cache($user_id,$field=null,$table=0) {
	global $wpdb,$blog_id;
	if (!isset($user_id)) {return;}
	$tname=WORDBOOKER_USERSTATUS;
	$query_fields='facebook_id,name,url,pic,status,updated,facebook_id';
	$blog_lim=' and blog_id='.$blog_id;
	if ($table==1) {$tname=WORDBOOKER_USERDATA;$query_fields='facebook_id,name,url,pic,status,updated,auths_needed,use_facebook';$blog_lim='';}
	if (isset($field)) {$query_fields=$field;}
	if ($user_id==-99){
		$query=$wpdb->prepare("select ".$query_fields." from ".$tname."  where blog_id = %d",$blog_id);
		$result = $wpdb->get_results($query,ARRAY_N );
		foreach($result as $key){ $newkey[]=$key[0];}
		$result = implode(",",$newkey);
	}
	else {
		$query=$wpdb->prepare("select ".$query_fields." from ".$tname."  where user_ID=%d ".$blog_lim,$user_id);
		$result = $wpdb->get_row($query); }
	return $result;
}

function wordbooker_process_post_queue($post_id) {
	global $wpdb,$blog_id;
	# We need to get the lowest post_id from the post_queue which has the lowest priority ID
}

function wordbooker_custom_cron_schedules($schedules){
	$schedules1['10mins'] = array(
	'interval'   => 600,
	'display'   => __('Every 10 Minutes', 'wordbooker'),
	);
	$schedules1['15mins'] = array(
	'interval'   => 900,
	'display'   => __('Every 15 Minutes', 'wordbooker'),
	);
	$schedules1['20mins'] = array(
	'interval'   => 1200,
	'display'   => __('Every 20 Minutes', 'wordbooker'),
	);
	$schedules1['30mins'] = array(
	'interval'   => 1800,
	'display'   => __('Every 30 Minutes', 'wordbooker'),
	);
	$schedules1['45mins'] = array(
	'interval'   => 2700,
	'display'   => __('Every 45 Minutes', 'wordbooker'),
	);
	$schedules1['2hours'] = array(
	'interval'   => 7200,
	'display'   => __('Every 2 Hours', 'wordbooker'),
	);
	return array_merge($schedules,$schedules1);
}


function wordbooker_init () {
	load_plugin_textdomain ('wordbooker',false,basename(dirname(__FILE__)).'/languages');
	add_image_size( 'wordbooker_og', 700, 700 );
	add_filter('cron_schedules','wordbooker_custom_cron_schedules');
}

function wordbooker_schema($attr) {
    #    $att2 = " xmlns:fb=\"http://www.facebook.com/2008/fbml\" xmlns:og=\"http://ogp.me/ns#\" ";

	if ( (is_single() || is_page()) && !is_front_page() && !is_category() && !is_home()) {
		 $att2=' xmlns:fb="http://ogp.me/ns/fb#" xmlns:article="http://ogp.me/ns/article#"';}
	else {
	   	 $att2= ' xmlns:fb="http://ogp.me/ns#  xmlns:website="http://ogp.me/ns/website#"';}
  if (preg_match('/(prefix\s*=\s*[\"|\'])/i', $attr)) {
    $attr = preg_replace('/(prefix\s*=\s*[\"|\'])/i', '${1}" og: http://ogp.me/ns#"', $attr);
  } else {
   $attr .= ' prefix="og: http://ogp.me/ns#" ';
   //  $attr .= $att2;
  }
  return $attr;
}

function wordbooker_get_avatar($avatar, $comment, $size="50"){
	if (is_null($comment) || !is_object($comment )) {return $avatar;}
	if ( !@$comment->comment_ID) {return $avatar;}
	$author_url = $comment->comment_author_url;
	$grav_url="";
	$fb_id=get_comment_meta($comment->comment_ID,'fb_uid',true);
	if (strlen($fb_id)<11) {
	  if(strlen($author_url) < 11) {return $avatar;}
	  $parse_author_url = (parse_url($author_url));
	  if(is_array($parse_author_url) && isset($parse_author_url['path'])) {
		  $fb_id_array = explode('/',$parse_author_url['path']);
		  $sizer = count($fb_id_array) -1;
		  $fb_id =  $fb_id_array[$sizer];
	  }
	  if ($parse_author_url['host']=='plus.google.com') {
	      $grav_url= "https://profiles.google.com/s2/photos/profile/".$fb_id;
	  }
	  if ($parse_author_url['host']=='www.facebook.com') {
	      $grav_url= "https://graph.facebook.com/".$fb_id."/picture?type=square";
	  }
	} else
	 {
	$grav_url= "https://graph.facebook.com/".$fb_id."/picture?type=square";
	}
	if (strlen($grav_url)>3) {
	$avatar = "<img src='".$grav_url."'  height='".$size."' width='".$size."' class='avatar avatar-40 photo' /> ";
	}
	return $avatar;
}

function wordbooker_comment_row ( $actions, $comment ) {
	global $user_ID, $wpdb,$blog_id,$wp;
	$sql=$wpdb->prepare('SELECT 1 FROM ' . WORDBOOKER_POSTCOMMENTS . ' WHERE wp_comment_id = %d and blog_id=%d',$comment->comment_ID,$blog_id);
	$result = $wpdb->query($sql);
	if ($result>0){
		  $nonce = wp_create_nonce("wordbooker_comment_nonce");
	    $link = admin_url('admin-ajax.php?action=wordbookercommentflip&id='.$comment->comment_ID.'&_wbnonce='.$nonce);
		$actions['wordbooker'] = '<a href="' . $link . '">' . __( 'Remove Wordbooker Record', 'wordbooker' ) . '</a>';
	}
	return $actions;
}

function wordbookercommentflip () {
	global $wpdb,$blog_id;$wp;
	$wp_list_table = _get_list_table('WP_Comments_List_Table');
	$pagenum = $wp_list_table->get_pagenum();
	$nonce =$_REQUEST['_wbnonce'];
	$redirect_to = remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'spammed', 'unspammed', 'approved', 'unapproved', 'ids' ), wp_get_referer() );
	$redirect_to = add_query_arg( 'paged', $pagenum, $redirect_to );
	if ( ( wp_verify_nonce($nonce, 'wordbooker_comment_nonce')) && ('wordbookercommentflip' == $_REQUEST['action'] )) {
		$comment_id = absint( $_REQUEST['id'] );
		$r = wordbooker_delete_comment_from_commentlogs($comment_id,$blog_id);
	}
	wp_safe_redirect($redirect_to);
}

function wordbooker_bulk_admin_footer() {
	global $post_type;
	if($post_type == 'post') {
		?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('<option>').val('wbookdel').text('<?php _e('Delete Wordbooker Meta')?>').appendTo("select[name='action']");
					jQuery('<option>').val('wbookdel').text('<?php _e('Delete Wordbooker Meta')?>').appendTo("select[name='action2']");
				});
			</script>
		<?php
	}
}

function wordbooker_bulk_action() {
	global $typenow;
	$post_type = $typenow;
	if($post_type == 'post') {
		// get the action
		$wp_list_table = _get_list_table('WP_Posts_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
		$action = $wp_list_table->current_action();
		$allowed_actions = array("wbookdel");
		if(!in_array($action, $allowed_actions)) return;

		// security check
		check_admin_referer('bulk-posts');
		// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
		if(isset($_REQUEST['post'])) {
			$post_ids = array_map('intval', $_REQUEST['post']);
		}
		if(empty($post_ids)) return;

		// this is based on wp-admin/edit.php
		$sendback = remove_query_arg( array('wbookdel', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
		if ( ! $sendback ) $sendback = admin_url( "edit.php?post_type=$post_type" );
		$pagenum = $wp_list_table->get_pagenum();
		$sendback = add_query_arg( 'paged', $pagenum, $sendback );
		switch($action) {
			case 'wbookdel':
				$wbdel = 0;
				foreach( $post_ids as $post_id ) {

					if ( !wordbooker_delete_meta($post_id) )
						wp_die( __('Error Deleting Meta.') );
					$wbdel++;
				}
				$sendback = add_query_arg( array('wbookdel' => $wbdel, 'ids' => join(',', $post_ids) ), $sendback );
			break;
			default: return;
		}
		$sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );
		wp_redirect($sendback);
		exit();
	}
}

function wordbooker_delete_meta($post_id) {
	$x=delete_post_meta($post->ID, '_wordbooker_options');
	$y=delete_post_meta($post->ID, '_wordbooker_ogimages');
	$z=delete_post_meta($post->ID, '_wordbooker_thumb');
	return $x;
}

function wordbooker_bulk_admin_notices() {
	global $post_type, $pagenow;
	if($pagenow == 'edit.php' && $post_type == 'post' && isset($_REQUEST['wbookdel']) && (int) $_REQUEST['wbookdel']) {
		$message = sprintf( _n( 'Wordbooker Meta deleted.', '%s posts processed.', $_REQUEST['wbookdel'] ), number_format_i18n( $_REQUEST['wbookdel'] ) );
		echo "<div class=\"updated\"><p>{$message}</p></div>";
	}
}

# Includes - trying to keep my code base tidy.
if (!function_exists('wordbooker_delete_comment')){
	include("includes/wordbooker_db_functions.php");
	include("includes/wordbooker_db_maint.php");
	include("includes/wordbooker_misc_functions.php");
}
if (is_admin()){
	include("includes/wordbooker_options.php");
}

register_activation_hook(__FILE__, 'wordbooker_activate');
add_action ('init', 'wordbooker_init');
add_action('delete_user', 'wordbooker_remove_user');

function wordbooker_load_process_post_data($newstatus, $oldstatus, $post){
	if (!function_exists('wordbooker_publish')){
		include("includes/wordbooker_process_functions.php");
		include("includes/wordbooker_posting.php");
	}
		wordbooker_process_post_data($newstatus, $oldstatus, $post) ;
}

$wordbooker_disabled=wordbooker_get_option('wordbooker_disabled');

# If they've disabled Wordbooker then we don't need to load any of these.
if (!isset($wordbooker_disabled)){
	$wordbooker_disable_og=wordbooker_get_option('wordbooker_fb_disable_og');
 if (is_admin()){
		add_action('delete_post', 'wordbooker_delete_post');
		add_action('delete_comment', 'wordbooker_delete_comment');
		add_action('admin_footer-edit.php','wordbooker_bulk_admin_footer');
		add_action('load-edit.php', 'wordbooker_bulk_action');
		add_action('admin_notices', 'wordbooker_bulk_admin_notices');
	}
	include("includes/wordbooker_opengraph.php");
	if  (!isset($wordbooker_disable_og)){
		add_filter( 'jetpack_enable_open_graph', '__return_false', 99 );
	}
	add_filter('the_content', 'wordbooker_append_post1');
	add_filter('the_excerpt','wordbooker_append_post2');
	add_action('wp_head', 'wordbooker_header');
	add_action('wp_footer', 'wordbooker_footer');
	add_shortcode('wb_fb_like', 'wordbooker_fb_like_inline');
	add_shortcode('wb_fb_send', 'wordbooker_fb_send_inline');
	add_shortcode('wb_fb_share', 'wordbooker_fb_share_inline');
	add_shortcode('wb_fb_comment', 'wordbooker_fb_comment_inline');
	add_shortcode('wb_fb_read','wordbooker_fb_read_inline');
	add_shortcode('wb_fb_f','wordbooker_fb_link_friend');
	$wordbooker_fb_gravatars=wordbooker_get_option('wordbooker_no_facebook_gravatars');
	if (!isset($wordbooker_fb_gravatars)){
		add_filter('get_avatar','wordbooker_get_avatar',1, 3 );
	}
	add_action('transition_post_status', 'wordbooker_load_process_post_data',10,3);
	if (!function_exists('wordbooker_poll_comments')){
		include("includes/wordbooker_cron.php");
		include("includes/wordbooker_comments.php");
		include("includes/wordbooker_wb_widget.php");
		include("includes/wordbooker_fb_widget.php");
		#include("includes/custom_quick_edit.php");
	}
	add_action('wb_cron_job', 'wordbooker_poll_facebook');
	add_action('wb_comment_job', 'wordbooker_poll_comments');
	add_filter('language_attributes', 'wordbooker_schema');
	add_filter('comment_row_actions', 'wordbooker_comment_row', 10, 2 );
	add_action( 'wp_ajax_wordbookercommentflip', 'wordbookercommentflip');

// If simple facebook connect is installed and enabled then we can pass the WB locale onto its own FB API launcher.
if (function_exists('jfb_output_facebook_init')) {
	add_filter('wpfb_output_facebook_locale', 'wordbooker_get_language');
}
}

function wordbooker_bbpress_like_share($post_cont) {
	//wordbooker_og_tags();
	echo '<script type="text/javascript" defer="defer">';
	echo 'function fbshare(url) { window.open ("http://www.facebook.com/sharer.php?u="+url, "Facebook Share","menubar=1,resizable=1,width=500,height=400");}';
	echo '</script>';
	$post_cont2=wordbooker_append_post1(" ");
	echo $post_cont2;
}

add_filter('bbp_template_before_pagination_loop','wordbooker_bbpress_like_share');

# This is for support for alternative posting processes.
$wordbooker_use_curl=wordbooker_get_option('wordbooker_use_curl');
if (isset($wordbooker_use_curl)){
		include("includes/wordbooker_facebook_curl.php");
	}
	else {
		include("includes/wordbooker_facebook_wp_http.php");
	}
?>