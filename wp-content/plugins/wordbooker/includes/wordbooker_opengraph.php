<?php
/*
Extension Name: Wordbooker Open Graph
Extension URI: http://wordbooker.tty.org.uk
Version: 2.2
Description: Functions for handling Open Graph Tags.
Author: Steve Atty
*/
function wordbooker_append_post1($post_cont) {
	// Front pages are a special case of excerpts so we want to process them properly.
	 if (is_front_page()) {return wordbooker_append_post($post_cont);}
	 if (!is_single()) {return $post_cont;}
	return wordbooker_append_post($post_cont);
 }

function wordbooker_append_post2($post_cont) {
	return wordbooker_append_post($post_cont);
 }

function wordbooker_og_tags(){
	global $post;
	$bname=get_bloginfo('name');
	$bdesc=get_bloginfo('description');
	$blink=site_url().'/';
	$meta_string="";
	$wplang=wordbooker_get_language();
	$wordbooker_settings = wordbooker_options();
	# Always put out the tags because even if they are not using like/share it gives Facebook stuff to work with.
	$wordbooker_post_options= get_post_meta($post->ID, '_wordbooker_options', true);
	$wpuserid=$post->post_author;
	if (is_array($wordbooker_post_options)){
		if  (@$wordbooker_post_options["wordbooker_default_author"] > 0 ) {$wpuserid=$wordbooker_post_options["wordbooker_default_author"];}
	}
	$blog_name=get_bloginfo('name');
	echo '<!-- Wordbooker generated tags -->';
	echo '<meta property="og:locale" content="'.$wplang.'" /> ';
	echo '<meta property="og:site_name" content="'.$bname.' - '.$bdesc.'" /> ';
	if (strlen($wordbooker_settings["fb_comment_app_id"])<6) {
	if (isset($wordbooker_settings['wordbooker_fb_comments_admin'])) {
		$xxx=wordbooker_get_cache(-99,'facebook_id',1);
			if (!is_null($xxx)) {
			echo '<meta property="fb:admins" content="'.$xxx.'" /> ';
		}
	} else {
		 $xxx=wordbooker_get_cache( $wpuserid,'facebook_id',1);
		if (isset($xxx->facebook_id)) {
			echo '<meta property="fb:admins" content="'.$xxx->facebook_id.'" /> ';
		}
	 }
	}
	if (strlen($wordbooker_settings["fb_comment_app_id"])>6) {
		echo '<meta property = "fb:app_id" content = "'.$wordbooker_settings["fb_comment_app_id"].'" /> ';
	}
	if (defined('WORDBOOKER_PREMIUM')) {
		echo '<meta property = "fb:app_id" content = "'.WORDBOOKER_FB_ID.'" /> ';
	}
	if ( (is_single() || is_page()) && !is_front_page() && !is_category()  && !is_home() ) {
		$post_link = get_permalink($post->ID);
		$post_title=$post->post_title;
		echo '<meta property="og:type" content="article" /> ';
		echo '<meta property="og:title" content="'.htmlspecialchars(strip_tags($post_title),ENT_QUOTES).'"/> ';
		echo '<meta property="og:url" content="'.$post_link.'" /> ';
		echo '<meta property="article:published_time" content="'.get_the_date('c').'" /> ';
		echo '<meta property="article:modified_time" content="'.get_the_modified_date('c').'" /> ';
		if ( isset( $post->post_author ) ) {
		  echo '<meta property="article:author" content="'.get_author_posts_url( $post->post_author ).'" /> ';
		}
		$cat_ids = get_the_category();
		if ( ! empty( $cat_ids ) ) {
			$cat = get_category( $cat_ids[0] );
			if ( ! empty( $cat ) )
				$meta_tags['section'] = $cat->name;
			//output the rest of the categories as tags
			unset( $cat_ids[0] );
			if ( ! empty( $cat_ids ) ) {
				$meta_tags['tags'] = array();
				foreach( $cat_ids as $cat_id ) {
					$cat = get_category( $cat_id );
					$meta_tags['tags'][] = $cat->name;
					unset( $cat );
				}
			}
		}
		$tags = get_the_tags();
		# var_dump($tags);
		if ( $tags ) {
			$meta_tags['tags'] = array();
			foreach ( $tags as $tag ) {
				$meta_tags['tags'][] = $tag->name;
			}
		}
		if(!isset($meta_tags)){} else{
			echo '<meta property="article:section" content="'.$meta_tags['section'].'" />';
			if (isset($meta_tags['tags']) && count($meta_tags['tags'])>0) {foreach($meta_tags['tags'] as $tag){
				echo '<meta property="article:tag" content="'.$tag.'" />';
			}
		}
		}
		$ogimage=get_post_meta($post->ID, '_wordbooker_thumb', TRUE);
		if (strlen($ogimage)<6 ) {
			$images=wordbooker_return_images($post->post_content,$post->ID,0);
			$ogimage=$images[0]['src'];
			update_post_meta($post->ID, '_wordbooker_thumb', $ogimage);
		}
		$curr_image=preg_split('|/|',$ogimage);
		$curr_image=end($curr_image);
		if (isset($wordbooker_settings["wordbooker_use_this_image"]) && (strlen($wordbooker_settings["wb_wordbooker_default_image"])>4) && ($curr_image=="wordbooker_blank.jpg")) {
			$ogimage=$wordbooker_settings["wb_wordbooker_default_image"];}
		if (strlen($ogimage)<4) {
			if (isset($wordbooker_settings["wordbooker_use_this_image"]) && (strlen($wordbooker_settings["wb_wordbooker_default_image"]>4))) {
				$ogimage=$wordbooker_settings["wb_wordbooker_default_image"];}
			else {
				$ogimage=plugins_url().'/wordbooker/includes/wordbooker_blank.jpg';
			}
		}
		if (strlen($ogimage)>4) {
			echo '<meta property="og:image" content="'.$ogimage.'" /> ';
		}
		// put out multiple OG Image tags if we can.
		$ogimages=get_post_meta($post->ID, '_wordbooker_ogimages');
		if (count($ogimages)==0) {
			 wordbooker_update_post_meta($post);
			 $ogimages=get_post_meta($post->ID, '_wordbooker_ogimages');}
		if (count($ogimages)>0){
			$ogimages2=explode(",",$ogimages[0]);
			if (count($ogimages2)>0){
				foreach($ogimages2 as $ogimg){
				if (strlen($ogimg)>4) {  echo '<meta property="og:image" content="'.$ogimg.'" /> '; }
				}
			}
		}
	}
	else
	{ # Not a single post so we only need the og:type tag and the og:image
		echo '<meta property="og:title" content="'.$bname.' - '.$bdesc.'" /> ';
		echo '<meta property="og:type" content="website" /> ';
		echo '<meta property="og:url" content="'.$blink.'" /> ';
		if (isset($wordbooker_settings["wordbooker_use_this_image"]) && (strlen($wordbooker_settings["wb_wordbooker_default_image"])>4)) {
			$ogimage=$wordbooker_settings["wb_wordbooker_default_image"];}
		else {
			$ogimage=get_bloginfo('wpurl').'/wp-content/plugins/wordbooker/includes/wordbooker_blank.jpg';
		}
		if (strlen($ogimage)>4) {
			echo '<meta property="og:image" content="'.$ogimage.'" /> ';
		}
	}
	$meta_length=0;
	$meta_length = $meta_length + wordbooker_get_option('wordbooker_description_meta_length');
	if (is_single() || is_page()) {
		$excerpt=get_post_meta($post->ID, '_wordbooker_extract', TRUE);
		if(strlen($excerpt) < 5 ) {
			$excerpt=wordbooker_post_excerpt($post->post_content,250);
			update_post_meta($post->ID, '_wordbooker_extract', trim($excerpt));
		}
		# If we've got an excerpt use that instead
		if ((strlen($post->post_excerpt)>3) && (strlen($excerpt) <=5)) {
			$excerpt=$post->post_excerpt;
			$description = str_replace('"','&quot;',$post->post_content);
			$excerpt = wordbooker_post_excerpt($description,1000);
			$excerpt = preg_replace('/(\r|\n)+/',' ',$excerpt);
			$excerpt = preg_replace('/\s\s+/',' ',$excerpt);

			update_post_meta($post->ID, '_wordbooker_extract', trim($excerpt));
		}
		# Now if we've got something put the meta tag out.
			if (isset($excerpt) && strlen(trim($excerpt))>2 ){
			if ($meta_length > 0 ) {$meta_string .= sprintf("<meta name=\"description\" content=\"%s\" /> ", htmlspecialchars(trim($excerpt),ENT_QUOTES));}
			$meta_string .= sprintf("<meta property=\"og:description\" content=\"%s\" /> ", htmlspecialchars(trim($excerpt),ENT_QUOTES));
			# convert blank lines into spaces.
			$meta_string=str_replace("\r\n", "   ", $meta_string);
			echo $meta_string;
		}
	}
else
	{
		if ($meta_length > 0 ) {$meta_string .= sprintf("<meta name=\"description\" content=\"%s\" /> ", $bdesc); }
		$meta_string .= sprintf("<meta property=\"og:description\" content=\"%s\" /> ", trim($bdesc));
		echo $meta_string;
	}
	echo '<!-- End Wordbooker og tags -->';
}


function display_wordbooker_fb_share() {
	global $post;
	$wordbooker_settings = wordbooker_options();
	$do_share=0;
	$wordbooker_post_options= get_post_meta($post->ID, '_wordbooker_options', true);
	if (!isset($wordbooker_settings['wordbooker_share_on'])) {return ;}
	if ($wordbooker_post_options['wordbooker_share_button_post']==2 && !is_page()) {return ;}
	if ($wordbooker_post_options['wordbooker_share_button_page']==2 && is_page()) {return ;}
	if (isset($wordbooker_settings['wordbooker_share_button_post']) && is_single()  ) {$do_share=1;}
	if (isset($wordbooker_settings['wordbooker_share_button_page']) && is_page() )  {$do_share=1;}
	if (isset($wordbooker_settings['wordbooker_share_button_frontpage'])  && is_front_page() ) {$do_share=1;}
	if (isset($wordbooker_settings['wordbooker_share_button_category']) &&  is_category()  ) {$do_share=1;}
	if (isset($wordbooker_settings['wordbooker_no_share_stick']) &&  is_sticky()  ) {$do_share=0; }
	if ( $do_share==1  &&
	((isset($wordbooker_settings['wordbooker_share_button_post']) && is_single()  )
          || (isset($wordbooker_settings['wordbooker_share_button_page']) && is_page() )
	  || (isset($wordbooker_settings['wordbooker_share_button_frontpage'])  && is_front_page() )
	  || (isset($wordbooker_settings['wordbooker_share_button_category']) &&  is_category()  ))
	  )
	{
	$post_link = get_permalink($post->ID);
	$btype="button";
	if (is_single() || is_page()) {
	$btype="button_count";
	}
	if (isset($wordbooker_settings['wordbooker_iframe'])) {
		 $share_code='<!-- Wordbooker created FB tags --> <a name="fb_share" type="'.$btype.'" share_url="'.$post_link.'"></a>';
	}
	else {
			$share_code='<!-- Wordbooker created FB tags --> <div class="fb-share-button" data-href="'.$post_link.'" data-type="'.$btype.'" data-width="'.$wordbooker_settings["wordbooker_share_width"].'"></div>';
	}
	if (isset($wordbooker_settings['wordbooker_time_button'])) {
		if (isset($wordbooker_settings['wordbooker_iframe'])) {
			 $share_code='<!-- Wordbooker created FB tags --> <iframe src="https://www.facebook.com/plugins/add_to_timeline.php?show-faces=true&amp;mode=button&amp;appId=277399175632726" style="border:none; overflow:hidden;"></iframe>';
		}
		else {
			$share_code='<!-- Wordbooker created FB tags -->  <div class="fb-add-to-timeline" data-show-faces="false" data-mode="button"></div>';
		}
	}

	echo $share_code;
	}
}

function wordbooker_fb_share_inline() {
	global $post;
	$wordbooker_settings = wordbooker_options();
	$do_share=0;
	$wordbooker_post_options= get_post_meta($post->ID, '_wordbooker_options', true);
	if (!isset($wordbooker_settings['wordbooker_share_on'])) {return ;}
	if ($wordbooker_post_options['wordbooker_share_button_post']==2 && !is_page()) {return ;}
	if ($wordbooker_post_options['wordbooker_share_button_page']==2 && is_page()) {return ;}
	if (isset($wordbooker_settings['wordbooker_share_button_post']) && is_single() && !is_front_page() ) {$do_share=1;}
	if (isset($wordbooker_settings['wordbooker_share_button_page']) && is_page()  && !is_front_page() )  {$do_share=1;}
	if (isset($wordbooker_settings['wordbooker_share_button_frontpage'])  && is_front_page() ) {$do_share=1;}
	if (isset($wordbooker_settings['wordbooker_share_button_category']) &&  is_category()  ) {$do_share=1;}
	if (isset($wordbooker_settings['wordbooker_no_share_stick']) &&  is_sticky()  ) {$do_share=0; }
	if ( $do_share==1  &&
	((isset($wordbooker_settings['wordbooker_share_button_post']) && is_single()  )
          || (isset($wordbooker_settings['wordbooker_share_button_page']) && is_page() )
	  || (isset($wordbooker_settings['wordbooker_share_button_frontpage'])  && is_front_page() )
	  || (isset($wordbooker_settings['wordbooker_share_button_category']) &&  is_category()  ))
	  )
	{
	$post_link = get_permalink($post->ID);
	$btype="button";
	if (is_single() || is_page()) {
	$btype="button_count";
	}
	if (isset($wordbooker_settings['wordbooker_iframe'])) {
		 $share_code='<!-- Wordbooker created FB tags --> <a name="fb_share" type="'.$btype.'" share_url="'.$post_link.'"></a>';
	}
	else {
	//	$share_code='<!-- Wordbooker created FB tags --> <fb:share-button class="meta" type="'.$btype.'" href="'.$post_link.'" > </fb:share-button>';
		$share_code='<!-- Wordbooker created FB tags --> <div class="fb-share-button" data-href="'.$post_link.'" data-type="'.$wordbooker_settings["wordbooker_fbshare_button"].'" data-width="'.$wordbooker_settings["wordbooker_share_width"].'"></div>';
	}
	if (isset($wordbooker_settings['wordbooker_time_button'])) {
		if (isset($wordbooker_settings['wordbooker_iframe'])) {
			 $share_code='<!-- Wordbooker created FB tags --> <iframe src="https://www.facebook.com/plugins/add_to_timeline.php?show-faces=true&amp;mode=button&amp;appId=277399175632726" frameborder="0" style="border:none; overflow:hidden;" ></iframe>';
		}
		else {
			$share_code='<!-- Wordbooker created FB tags --> <div class="fb-add-to-timeline" data-show-faces="false" data-mode="button"></div>';

		}
	}

	 return $share_code;
	}
}
function display_wordbooker_fb_send() {
	global $post,$q_config;
	$wordbooker_settings = wordbooker_options();
	$wordbooker_post_options= get_post_meta($post->ID, '_wordbooker_options', true);
	$post_link = get_permalink($post->ID);
	if (isset($wordbooker_settings['wordbooker_like_button_post']) && $wordbooker_post_options['wordbooker_like_button_post']==2 && !is_page()) {return ;}
	if (isset($wordbooker_settings['wordbooker_like_button_page']) && $wordbooker_post_options['wordbooker_like_button_page']==2 && is_page()) {return ;}
	if (isset($wordbooker_settings['wordbooker_fblike_send_combi']) && $wordbooker_settings['wordbooker_fblike_send_combi']=='true') {return;}

	$do_like=0;
	if (isset($wordbooker_settings['wordbooker_like_button_post']) && is_single() && !is_front_page() ) {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_like_button_page']) && is_page() && !is_front_page())  {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_like_button_frontpage'])  && is_front_page() ) {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_like_button_category']) &&  is_category() && !is_front_page() ) {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_no_like_stick']) &&  is_sticky()  ) { $do_like=0;}
	if ( $do_like==1  &&
		((isset($wordbooker_settings['wordbooker_like_button_post']) && is_single()  )
          || (isset($wordbooker_settings['wordbooker_like_button_page']) && is_page() )
	  || (isset($wordbooker_settings['wordbooker_like_button_frontpage'])  && is_front_page() )
	  || (isset($wordbooker_settings['wordbooker_like_button_category']) &&  is_category()  ))
	  )
	{
		if (isset($wordbooker_settings['wordbooker_iframe'])) {
			$px=35;
			$wplang=wordbooker_get_language();
			if ($wordbooker_settings['wordbooker_fblike_faces']=='true') {$px=80;}
			$like_code='<!-- Wordbooker created FB tags --> <iframe src="https://www.facebook.com/plugins/send.php?locale='.$wplang.'&amp;href='.$post_link.'&amp;layout='.$wordbooker_settings['wordbooker_fblike_button'].'&amp;show_faces='.$wordbooker_settings['wordbooker_fblike_faces'].'&amp;width='.$wordbooker_settings["wordbooker_like_width"].'&amp;action='.$wordbooker_settings['wordbooker_fblike_action'].'&amp;colorscheme='.$wordbooker_settings['wordbooker_fblike_colorscheme'].'&amp;font='.$wordbooker_settings['wordbooker_fblike_font'].'&amp;height='.$px.'px" style="border:none; overflow:hidden; width:'.$wordbooker_settings["wordbooker_like_width"].'px; height:'.$px.'px;" ></iframe>';

		}
		else {
			$like_code='<!-- Wordbooker created FB tags --> <div class="fb-like" data-href="'.$post_link.'" data-width="'.$wordbooker_settings["wordbooker_like_width"].' " data-layout="'.$wordbooker_settings['wordbooker_fblike_button'] .'"data-action="'.$wordbooker_settings['wordbooker_fblike_action'].'" data-show-faces="'.$wordbooker_settings['wordbooker_fblike_faces'].'" data-share="'.$wordbooker_settings['wordbooker_fblike_send_combi'].'" data-colorscheme="'.$wordbooker_settings['wordbooker_fblike_colorscheme'].'"> </div>';
		}
		echo $like_code;
	}
}

function wordbooker_fb_send_inline() {
	global $post;
	$wordbooker_settings = wordbooker_options();
	$wordbooker_post_options= get_post_meta($post->ID, '_wordbooker_options', true);
	if (isset($wordbooker_post_options['wordbooker_like_button_post']) && $wordbooker_post_options['wordbooker_like_button_post']==2 && !is_page()) {return ;}
	if (isset($wordbooker_post_options['wordbooker_like_button_page']) && $wordbooker_post_options['wordbooker_like_button_page']==2 && is_page()) {return ;}
	if (isset($wordbooker_post_options['wordbooker_fblike_send_combi']) && $wordbooker_settings['wordbooker_fblike_send_combi']=='true') {return;}
	$post_link = get_permalink($post->ID);
	$do_like=0;
	if (isset($wordbooker_settings['wordbooker_like_button_post']) && is_single() && !is_front_page() ) {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_like_button_page']) && is_page() && !is_front_page())  {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_like_button_frontpage'])  && is_front_page() ) {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_like_button_category']) &&  is_category() && !is_front_page() ) {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_no_like_stick']) &&  is_sticky()  ) { $do_like=0;}
	if ( $do_like==1  &&
		((isset($wordbooker_settings['wordbooker_like_button_post']) && is_single()  )
          || (isset($wordbooker_settings['wordbooker_like_button_page']) && is_page() )
	  || (isset($wordbooker_settings['wordbooker_like_button_frontpage'])  && is_front_page() )
	  || (isset($wordbooker_settings['wordbooker_like_button_category']) &&  is_category()  ))
	  )
	{
	if ($wordbooker_settings['wordbooker_fblike_align']=='right') {$wordbooker_settings["wordbooker_like_width"]=1;}
		if (isset($wordbooker_settings['wordbooker_iframe'])) {
			$px=35;
			$wplang=wordbooker_get_language();
			if ($wordbooker_settings['wordbooker_fblike_faces']=='true') {$px=80;}
			$like_code='<!-- Wordbooker created FB tags --> <iframe src="https://www.facebook.com/plugins/send.php?locale='.$wplang.'&amp;href='.$post_link.'&amp;layout='.$wordbooker_settings['wordbooker_fblike_button'].'&amp;show_faces='.$wordbooker_settings['wordbooker_fblike_faces'].'&amp;width='.$wordbooker_settings["wordbooker_like_width"].'&amp;action='.$wordbooker_settings['wordbooker_fblike_action'].'&amp;colorscheme='.$wordbooker_settings['wordbooker_fblike_colorscheme'].'&amp;font='.$wordbooker_settings['wordbooker_fblike_font'].'&amp;height='.$px.'px" style="border:none; overflow:hidden; width:'.$wordbooker_settings["wordbooker_like_width"].'px; height:'.$px.'px;" ></iframe>';

		}
		else {
			$like_code='<!-- Wordbooker created FB tags --> <div class="fb-like" data-href="'.$post_link.'" data-width="'.$wordbooker_settings["wordbooker_like_width"].' " data-layout="'.$wordbooker_settings['wordbooker_fblike_button'] .'"data-action="'.$wordbooker_settings['wordbooker_fblike_action'].'" data-show-faces="'.$wordbooker_settings['wordbooker_fblike_faces'].'" data-share="'.$wordbooker_settings['wordbooker_fblike_send_combi'].'" data-colorscheme="'.$wordbooker_settings['wordbooker_fblike_colorscheme'].'"> </div>';
		}
		return $like_code;
	}
}

function display_wordbooker_fb_like() {
	global $post;
	$wordbooker_settings = wordbooker_options();

	$wordbooker_post_options= get_post_meta($post->ID, '_wordbooker_options', true);
	if ($wordbooker_post_options['wordbooker_like_button_post']==2 && !is_page()) {return ;}
	if ($wordbooker_post_options['wordbooker_like_button_page']==2 && is_page()) {return ;}
	if (!isset($wordbooker_settings['wordbooker_like_button_show'])) {return;}
	$do_like=0;
	$post_link = get_permalink($post->ID);
	if (isset($wordbooker_settings['wordbooker_like_button_post']) && is_single() && !is_front_page() ) {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_like_button_page']) && is_page() && !is_front_page())  {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_like_button_frontpage'])  && is_front_page() ) {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_like_button_category']) &&  is_category() && !is_front_page() ) {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_no_like_stick']) &&  is_sticky()  ) { $do_like=0;}
	if ( $do_like==1  &&
		((isset($wordbooker_settings['wordbooker_like_button_post']) && is_single()  )
          || (isset($wordbooker_settings['wordbooker_like_button_page']) && is_page() )
	  || (isset($wordbooker_settings['wordbooker_like_button_frontpage'])  && is_front_page() )
	  || (isset($wordbooker_settings['wordbooker_like_button_category']) &&  is_category()  ))
	  )
	{
		if (isset($wordbooker_settings['wordbooker_iframe'])) {
			$px=35;
			$wplang=wordbooker_get_language();
			if ($wordbooker_settings['wordbooker_fblike_faces']=='true') {$px=95;}
			$like_code='<!-- Wordbooker created FB tags --> <iframe src="https://www.facebook.com/plugins/like.php?locale='.$wplang.'&amp;href='.$post_link.'&amp;layout='.$wordbooker_settings['wordbooker_fblike_button'].'&amp;show_faces='.$wordbooker_settings['wordbooker_fblike_faces'].'&amp;width='.$wordbooker_settings["wordbooker_like_width"].'&amp;action='.$wordbooker_settings['wordbooker_fblike_action'].'&amp;colorscheme='.$wordbooker_settings['wordbooker_fblike_colorscheme'].'&amp;font='.$wordbooker_settings['wordbooker_fblike_font'].'&amp;height='.$px.'px" style="border:none; overflow:hidden; width:'.$wordbooker_settings["wordbooker_like_width"].'px; height:'.$px.'px;" ></iframe>';

		}
		else {
			$like_code='<!-- Wordbooker created FB tags --> <div class="fb-like" data-href="'.$post_link.'" data-width="'.$wordbooker_settings["wordbooker_like_width"].' " data-layout="'.$wordbooker_settings['wordbooker_fblike_button'] .'"data-action="'.$wordbooker_settings['wordbooker_fblike_action'].'" data-show-faces="'.$wordbooker_settings['wordbooker_fblike_faces'].'" data-share="'.$wordbooker_settings['wordbooker_fblike_send_combi'].'" data-colorscheme="'.$wordbooker_settings['wordbooker_fblike_colorscheme'].'"> </div>';
		}
		echo $like_code;
	}
}

function wordbooker_fb_like_inline() {
	global $post;
	$wordbooker_settings = wordbooker_options();
	$wordbooker_post_options= get_post_meta($post->ID, '_wordbooker_options', true);
	if (isset($wordbooker_post_options['wordbooker_like_button_post']) && $wordbooker_post_options['wordbooker_like_button_post']==2 && !is_page()) {return ;}
	if (isset($wordbooker_post_options['wordbooker_like_button_page']) && $wordbooker_post_options['wordbooker_like_button_page']==2 && is_page()) {return ;}
	if (!isset($wordbooker_settings['wordbooker_like_button_show'])) {return;}
	$do_like=0;
	$post_link = get_permalink($post->ID);
	if (isset($wordbooker_settings['wordbooker_like_button_post']) && is_single() && !is_front_page() ) {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_like_button_page']) && is_page() && !is_front_page())  {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_like_button_frontpage'])  && is_front_page() ) {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_like_button_category']) &&  is_category() && !is_front_page() ) {$do_like=1;}
	if (isset($wordbooker_settings['wordbooker_no_like_stick']) &&  is_sticky()  ) { $do_like=0;}
	if ( $do_like==1  &&
		((isset($wordbooker_settings['wordbooker_like_button_post']) && is_single()  )
          || (isset($wordbooker_settings['wordbooker_like_button_page']) && is_page() )
	  || (isset($wordbooker_settings['wordbooker_like_button_frontpage'])  && is_front_page() )
	  || (isset($wordbooker_settings['wordbooker_like_button_category']) &&  is_category()  ))
	  )
	{
	//if ($wordbooker_settings['wordbooker_fblike_align']=='right') {$wordbooker_settings["wordbooker_like_width"]=1;}
		if (isset($wordbooker_settings['wordbooker_iframe'])) {
			$px=35;
			$wplang="en_US";
			if (strlen(WPLANG) > 2) {$wplang=WPLANG;}
			# then we check if WPLANG is actually set to anything sensible.
			if ($wplang=="WPLANG" ) {$wplang="en_US";}
			if ($wordbooker_settings['wordbooker_fblike_faces']=='true') {$px=95;}
			$like_code='<!-- Wordbooker created FB tags --> <iframe src="https://www.facebook.com/plugins/like.php?locale='.$wplang.'&amp;href='.$post_link.'&amp;layout='.$wordbooker_settings['wordbooker_fblike_button'].'&amp;show_faces='.$wordbooker_settings['wordbooker_fblike_faces'].'&amp;width='.$wordbooker_settings["wordbooker_like_width"].'&amp;action='.$wordbooker_settings['wordbooker_fblike_action'].'&amp;colorscheme='.$wordbooker_settings['wordbooker_fblike_colorscheme'].'&amp;font='.$wordbooker_settings['wordbooker_fblike_font'].'&amp;height='.$px.'px" style="border:none; overflow:hidden; width:'.$wordbooker_settings["wordbooker_like_width"].'px; height:'.$px.'px;" ></iframe>';

		}
		else {
			$like_code='<!-- Wordbooker created FB tags --> <div class="fb-like" data-href="'.$post_link.'" data-width="'.$wordbooker_settings["wordbooker_like_width"].' " data-layout="'.$wordbooker_settings['wordbooker_fblike_button'] .'"data-action="'.$wordbooker_settings['wordbooker_fblike_action'].'" data-show-faces="'.$wordbooker_settings['wordbooker_fblike_faces'].'" data-share="'.$wordbooker_settings['wordbooker_fblike_send_combi'].'" data-colorscheme="'.$wordbooker_settings['wordbooker_fblike_colorscheme'].'"> </div>';
	}
		return $like_code;
	}
}


function wordbooker_append_post($post_cont) {
	global $post;
	$do_share=0;
	if ($post->post_type=='forum') { return;}
	$wordbooker_settings = wordbooker_options();
	if (!isset($wordbooker_settings['wordbooker_like_button_show']) && !isset($wordbooker_settings['wordbooker_like_share_too']) && !isset($wordbooker_settings['wordbooker_use_fb_comments'])) {return $post_cont;}
	$post_cont2=$post_cont;
	$post_link = get_permalink($post->ID);
	$send_code=wordbooker_fb_share_inline();
	$like_code=wordbooker_fb_like_inline();
	//$send_code=wordbooker_fb_send_inline();
	$comment_code=wordbooker_fb_comment_inline();
	//$read_code=wordbooker_fb_read_inline();
		if ($wordbooker_settings['wordbooker_fblike_send_combi']=='true'){
			if ($wordbooker_settings['wordbooker_fblike_location']=='bottom'){
				$post_cont2= $post_cont2."<div class='wp_fbl_bottom' style='text-align:".$wordbooker_settings['wordbooker_fblike_align']."'>".$like_code.'</div>';
			}
			if ($wordbooker_settings['wordbooker_fblike_location']=='top') {
				$post_cont2= "<div class='wp_fbl_top' style='text-align:".$wordbooker_settings['wordbooker_fblike_align']."'>".$like_code.'</div>'.$post_cont2;
			}
		}

	if ($wordbooker_settings['wordbooker_fblike_send_combi']=='false'){
		if ($wordbooker_settings['wordbooker_fblike_location']==$wordbooker_settings['wordbooker_fbshare_location']){
			if ($wordbooker_settings['wordbooker_fblike_location']=='bottom'){
				if ($wordbooker_settings['wordbooker_fblike_align']=='left') {
				$post_cont2=$post_cont2."<div class='wb_fb_bottom' style='text-align:".$wordbooker_settings['wordbooker_fblike_align'].";'>".$like_code.' '.$send_code.'</div>'; } else
				{
				$post_cont2=$post_cont2."<div class='wb_fb_bottom' style='text-align:".$wordbooker_settings['wordbooker_fbshare_align'].";'>".$send_code.' '.$like_code.'</div>'; }
			}
			if ($wordbooker_settings['wordbooker_fblike_location']=='top'){
			if ($wordbooker_settings['wordbooker_fblike_align']=='left') {
				$post_cont2="<div class='wb_fb_bottom' style='text-align:".$wordbooker_settings['wordbooker_fblike_align'].";'>".$like_code.' '.$send_code.'</div>'.$post_cont2; } else
				{
				$post_cont2="<div class='wb_fb_bottom' style='text-align:".$wordbooker_settings['wordbooker_fbshare_align'].";'>".$send_code.' '.$like_code.'</div>'.$post_cont2;  }
			}
		} else {
		if ($wordbooker_settings['wordbooker_fblike_location']=='bottom'){
			$post_cont2= $post_cont2."<div class='wp_fbl_bottom' style='text-align:".$wordbooker_settings['wordbooker_fblike_align'].";'>".$like_code.'</div>';
		}
		if ($wordbooker_settings['wordbooker_fblike_location']=='top') {
			$post_cont2= "<div class='wp_fbl_top' style='text-align:".$wordbooker_settings['wordbooker_fblike_align'].";'>".$like_code.'</div>'.$post_cont2;
		}
			if ($wordbooker_settings['wordbooker_fbshare_location']=='bottom'){
			$post_cont2= $post_cont2."<div class='wp_fbs_bottom' style='text-align:".$wordbooker_settings['wordbooker_fblike_align'].";'>".$send_code.'</div>';
		}
		if ($wordbooker_settings['wordbooker_fbshare_location']=='top') {
			$post_cont2= "<div class='wp_fbs_top' style='text-align:".$wordbooker_settings['wordbooker_fblike_align'].";'>".$send_code.'</div>'.$post_cont2;
		}
	}}

	if ($wordbooker_settings['wordbooker_comment_location']=='bottom') { $post_cont2=$post_cont2."<div class='wb_fb_comment'><br/>".$comment_code."</div>"; }
	return $post_cont2;
}

function display_wordbooker_fb_comment() {
	global $post;
	if(!is_single || is_front_page() && !is_category() && !is_archive() && !is_home()){return;}
	$wordbooker_settings = wordbooker_options();
	if (!isset($wordbooker_settings['wordbooker_use_fb_comments'])) { return;}
	$wordbooker_post_options= get_post_meta($post->ID, '_wordbooker_options', true);
	if ( isset($wordbooker_post_options['wordbooker_use_facebook_comments'])) {
		$post_link = get_permalink($post->ID);
		$checked_flag=array('on'=>'true','off'=>'false');
		$comment_code= '<fb:comments href="'.$post_link.'" num_posts="'.$wordbooker_settings['fb_comment_box_count'].'" width="'.$wordbooker_settings['fb_comment_box_size'].'" notify="'.$checked_flag[$wordbooker_settings['fb_comment_box_notify']].'" colorscheme="'.$wordbooker_settings['wb_comment_colorscheme'].'" ></fb:comments>';
		echo $comment_code;
	}
}

function wordbooker_fb_comment_inline() {
	global $post;
	if(!is_single()){return;}
	$wordbooker_settings = wordbooker_options();
	if (!isset($wordbooker_settings['wordbooker_use_fb_comments'])) { return;}
	$wordbooker_post_options= get_post_meta($post->ID, '_wordbooker_options', true);
	if ( isset($wordbooker_post_options['wordbooker_use_facebook_comments'])) {
		$post_link = get_permalink($post->ID);
		$checked_flag=array('on'=>'true','off'=>'false');
		$comment_code= '<fb:comments href="'.$post_link.'" num_posts="'.$wordbooker_settings['fb_comment_box_count'].'" width="'.$wordbooker_settings['fb_comment_box_size'].'" notify="'.$checked_flag[$wordbooker_settings['fb_comment_box_notify']].'" colorscheme="'.$wordbooker_settings['wb_comment_colorscheme'].'" ></fb:comments>';
		return $comment_code;
	}
}
?>