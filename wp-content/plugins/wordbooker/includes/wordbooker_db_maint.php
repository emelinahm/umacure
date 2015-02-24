<?php
/*
Extension Name: Wordbooker DB Maintenance functions
Extension URI: http://wordbooker.tty.org.uk
Version: 2.2
Description: DB Table creation and updating code
Author: Steve Atty
*/

function wordbooker_activate() {
	global $wpdb, $table_prefix;
	wp_cache_flush();
	$errors = array();
	$result = $wpdb->query('
		CREATE TABLE IF NOT EXISTS ' . WORDBOOKER_POSTLOGS . '  (
			  `post_id` bigint(20) NOT NULL,
			  `blog_id` bigint(20) NOT NULL,
			  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
			  PRIMARY KEY  (`blog_id`,`post_id`)
			) DEFAULT CHARSET=utf8;
		');
		$x=$wpdb->last_error;
		if (strlen($x)>0) trigger_error($x,E_USER_ERROR);

	$result = $wpdb->query('
		CREATE TABLE IF NOT EXISTS ' . WORDBOOKER_ERRORLOGS . ' (
			`timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
			  `user_ID` bigint(20) unsigned NOT NULL,
			  `method` longtext NOT NULL,
			  `error_code` int(11) NOT NULL,
			  `error_msg` longtext NOT NULL,
			  `post_id` bigint(20) NOT NULL,
			  `blog_id` bigint(20) NOT NULL,
			   `sequence_id` bigint(20) NOT NULL auto_increment,
		           `diag_level` int(4) default NULL,
		           PRIMARY KEY  (`sequence_id`),
		           KEY `timestamp_idx` (`timestamp`),
		           KEY `blog_idx` (`blog_id`)
			) DEFAULT CHARSET=utf8;
		');
		$x=$wpdb->last_error;
		if (strlen($x)>0) trigger_error($x,E_USER_ERROR);

	$result = $wpdb->query('
		CREATE TABLE IF NOT EXISTS ' . WORDBOOKER_USERDATA . ' (
			`user_ID` bigint(20) unsigned NOT NULL,
			  `uid` varchar(80) default NULL,
			  `expires` varchar(80) default NULL,
			  `access_token` varchar(255) default NULL,
			  `sig` varchar(80) default NULL,
			  `use_facebook` tinyint(1) default 1,
			  `onetime_data` longtext,
			  `facebook_error` longtext,
			  `secret` varchar(80) default NULL,
			  `session_key` varchar(80) default NULL,
			  `facebook_id` varchar(80) default NULL,
			  `name` varchar(250) default NULL,
			  `status` varchar(2048) default NULL,
			  `updated` int(20) default NULL,
			  `url` varchar(250) default NULL,
			  `pic` varchar(250) default NULL,
			  `pages` longtext,
			  `auths_needed` int(1) default NULL,
			  `blog_id` bigint(20) default NULL,
			  PRIMARY KEY  (`user_ID` , `blog_id` ) ,
			  KEY `facebook_idx` (`facebook_id`)
			) DEFAULT CHARSET=utf8;
		');
		$x=$wpdb->last_error;
		if (strlen($x)>0) trigger_error($x,E_USER_ERROR);

	$result = $wpdb->query('
		CREATE TABLE IF NOT EXISTS ' . WORDBOOKER_POSTCOMMENTS . ' (
			  `fb_post_id` varchar(240) default NULL,
			  `user_id` bigint(20) NOT NULL,
			  `comment_timestamp` int(20) NOT NULL,
			  `wp_post_id` int(11) NOT NULL,
			  `blog_id` bigint(20) NOT NULL,
			  `wp_comment_id` int(20) NOT NULL,
			  `fb_comment_id` varchar(240) default NULL,
			  `in_out` varchar(20) default NULL,
			  `FB_USER_ID` varchar(120) NOT NULL,
			  `FB_TARGET_ID` varchar(120) NOT NULL,
			  UNIQUE KEY `fb_comment_id_idx` (`fb_comment_id`),
			  KEY `in_out_idx` (`in_out`),
			  KEY `main_index` (`blog_id`,`wp_post_id`,`fb_post_id`,`wp_comment_id`),
			  KEY `timestamp` (`comment_timestamp`)
			)  DEFAULT CHARSET=utf8;
		');
		$x=$wpdb->last_error;
		if (strlen($x)>0) trigger_error($x,E_USER_ERROR);

	$result = $wpdb->query('
		CREATE TABLE IF NOT EXISTS ' . WORDBOOKER_USERSTATUS . ' (
			  `user_ID` bigint(20) unsigned NOT NULL,
			  `name` varchar(250)  default NULL,
			  `status` varchar(2048)  default NULL,
			  `updated` int(20) default NULL,
			  `url` varchar(250)  default NULL,
			  `pic` varchar(250)  default NULL,
			  `blog_id` bigint(20) NOT NULL default 0,
			  `facebook_id` varchar(80) default NULL,
			  PRIMARY KEY  (`user_ID`,`blog_id`)
			)  DEFAULT CHARSET=utf8;
		');
		$x=$wpdb->last_error;
		if (strlen($x)>0) trigger_error($x,E_USER_ERROR);

	$result = $wpdb->query(' CREATE TABLE IF NOT EXISTS ' . WORDBOOKER_FB_FRIENDS . ' (
			  `user_id` int(11) NOT NULL,
			  `blog_id` bigint(20) NOT NULL,
			  `facebook_id` varchar(80) NOT NULL,
			  `name` varchar(200) NOT NULL,
			  `list_type` varchar(80) NOT NULL,
			  PRIMARY KEY  (`user_id`,`facebook_id`,`blog_id`),
			  KEY `user_id_idx` (`user_id`),
			  KEY `fb_id_idx` (`facebook_id`),
			  FULLTEXT KEY `name_idx` (`name`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		 ' );
		$x=$wpdb->last_error;
		if (strlen($x)>0) trigger_error($x,E_USER_ERROR);

		$result = $wpdb->query('CREATE TABLE IF NOT EXISTS ' . WORDBOOKER_FB_FRIEND_LISTS . ' (
		  `user_id` int(11) NOT NULL,
		  `flid` varchar(240) NOT NULL,
		  `owner` varchar(80) NOT NULL,
		  `name` varchar(240) NOT NULL,
		  `list_type` varchar(80) NOT NULL,
		  PRIMARY KEY  (`user_id`,`flid`),
		  KEY `list_type_idx` (`list_type`)
		)  DEFAULT CHARSET=utf8;
			');
		$x=$wpdb->last_error;
		if (strlen($x)>0) trigger_error($x,E_USER_ERROR);

	$result = $wpdb->query(' CREATE TABLE IF NOT EXISTS ' . WORDBOOKER_PROCESS_QUEUE . ' (
	  `entry_type` varchar(20) NOT NULL,
	  `blog_id` int(11) NOT NULL,
	  `post_id` int(11) NOT NULL,
	  `priority` int(11) NOT NULL,
	  `status` varchar(20) NOT NULL,
	  PRIMARY KEY  (`blog_id`,`post_id`)
	) DEFAULT CHARSET=utf8;
		');
		$x=$wpdb->last_error;
		if (strlen($x)>0) trigger_error($x,E_USER_ERROR);

	if ($errors) { wordbooker_db_crosscheck();}
	$wordbooker_settings=wordbooker_options();
	if (! isset($wordbooker_settings['schema_vers'])) {
	if ($wordbooker_settings['schema_vers'] != WORDBOOKER_SCHEMA_VERSION ) { wordbooker_db_crosscheck();}
	#Setup the cron. We clear it first in case someone did a dirty de-install.
	$dummy=wp_clear_scheduled_hook('wb_cron_job');
	$dummy=wp_schedule_event(time(), 'hourly', 'wb_cron_job');
	wordbooker_set_option('schema_vers', WORDBOOKER_SCHEMA_VERSION );}
}

function wordbooker_db_crosscheck() {
	global $wpdb;
	$table_array= array (WORDBOOKER_ERRORLOGS,WORDBOOKER_POSTLOGS,WORDBOOKER_USERDATA,WORDBOOKER_USERSTATUS,WORDBOOKER_POSTCOMMENTS,WORDBOOKER_PROCESS_QUEUE,WORDBOOKER_FB_FRIENDS,WORDBOOKER_FB_FRIEND_LISTS);
	$sql_run="";
	$wordbooker_columns[WORDBOOKER_ERRORLOGS]=array('timestamp','user_ID','method','error_code','error_msg','post_id','blog_id','sequence_id','diag_level');
	$wordbooker_columns[WORDBOOKER_POSTLOGS]=array('post_id','blog_id','timestamp');
	$wordbooker_columns[WORDBOOKER_USERDATA]=array('user_ID','uid','expires','access_token','sig','use_facebook','onetime_data','facebook_error','secret','session_key','facebook_id','name','status','updated','url','pic','pages','auths_needed','blog_id');
	$wordbooker_columns[WORDBOOKER_USERSTATUS]=array('user_ID','name','status','updated','url','pic','blog_id','facebook_id');
	$wordbooker_columns[WORDBOOKER_POSTCOMMENTS]=array('fb_post_id','user_id','comment_timestamp','wp_post_id','blog_id','wp_comment_id','fb_comment_id','in_out','FB_USER_ID','FB_TARGET_ID');
	$wordbooker_columns[WORDBOOKER_PROCESS_QUEUE]=array('entry_type','blog_id','post_id','priority','status');
	$wordbooker_columns[WORDBOOKER_FB_FRIENDS]=array('user_id','blog_id','facebook_id','name','list_type');
	$wordbooker_columns[WORDBOOKER_FB_FRIEND_LISTS]=array('user_id','flid','owner','name','list_type');

	$wordbooker_column_def[WORDBOOKER_ERRORLOGS]=array ('timestamp'=>'timestamp','user_ID'=>'bigint(20) unsigned','method'=>'longtext','error_code'=>'int(11)','error_msg'=>'longtext','post_id'=>'bigint(20)','blog_id'=>'bigint(20)','sequence_id'=>'bigint(20)','diag_level'=>'int(4)');
	$wordbooker_column_def[WORDBOOKER_POSTLOGS]=array ('post_id'=>'bigint(20)','blog_id'=>'bigint(20)','timestamp'=>'timestamp',);
	$wordbooker_column_def[WORDBOOKER_USERDATA]=array ('user_ID'=>'bigint(20) unsigned','uid'=>'varchar(80)','expires'=>'varchar(80)','access_token'=>'varchar(255)','sig'=>'varchar(80)','use_facebook'=>'tinyint(1)','onetime_data'=>'longtext','facebook_error'=>'longtext','secret'=>'varchar(80)','session_key'=>'varchar(80)','facebook_id'=>'varchar(80)','name'=>'varchar(250)','status'=>'varchar(2048)','updated'=>'int(20)','url'=>'varchar(250)','pic'=>'varchar(250)','pages'=>'longtext','auths_needed'=>'int(1)','blog_id'=>'bigint(20)');
	$wordbooker_column_def[WORDBOOKER_USERSTATUS]=array ('user_ID'=>'bigint(20) unsigned','name'=>'varchar(250)','status'=>'varchar(2048)','updated'=>'int(20)','url'=>'varchar(250)','pic'=>'varchar(250)','blog_id'=>'bigint(20)','facebook_id'=>'varchar(80)');
	$wordbooker_column_def[WORDBOOKER_POSTCOMMENTS]=array ('fb_post_id'=>'varchar(240)','user_id'=>'bigint(20)','comment_timestamp'=>'int(20)','wp_post_id'=>'int(11)','blog_id'=>'bigint(20)','wp_comment_id'=>'int(20)','fb_comment_id'=>'varchar(240)','in_out'=>'varchar(20)','FB_USER_ID'=>'varchar(120)','FB_TARGET_ID'=>'varchar(120)');
	$wordbooker_column_def[WORDBOOKER_PROCESS_QUEUE]=array ('entry_type'=>'varchar(20)','blog_id'=>'int(11)','post_id'=>'int(11)','priority'=>'int(11)','status'=>'varchar(20)');
	$wordbooker_column_def[WORDBOOKER_FB_FRIENDS]=array ('user_id'=>'int(11)','blog_id'=>'bigint(20)','facebook_id'=>'varchar(80)','name'=>'varchar(200)','list_type'=>'varchar(80)');
	$wordbooker_column_def[WORDBOOKER_FB_FRIEND_LISTS]=array ('user_id'=>'int(11)','flid'=>'varchar(240)','owner'=>'varchar(80)','name'=>'varchar(240)','list_type'=>'varchar(80)');

	$wordbooker_index_def[WORDBOOKER_ERRORLOGS]= array ('PRIMARY' => 'sequence_id' ,'timestamp_idx' => 'timestamp' ,'blog_idx' => 'blog_id' );
	$wordbooker_index_def[WORDBOOKER_POSTLOGS]= array ('PRIMARY' => 'blog_id, post_id' );
	$wordbooker_index_def[WORDBOOKER_USERDATA]= array ('PRIMARY' => 'user_ID, blog_id' ,'facebook_idx' => 'facebook_id' );
	$wordbooker_index_def[WORDBOOKER_USERSTATUS]= array ('PRIMARY' => 'user_ID, blog_id' );
	$wordbooker_index_def[WORDBOOKER_POSTCOMMENTS]= array ('fb_comment_id_idx' => 'fb_comment_id' ,'in_out_idx' => 'in_out' ,'main_index' => 'blog_id, wp_post_id, fb_post_id, wp_comment_id' ,'timestamp' => 'comment_timestamp' );
	$wordbooker_index_def[WORDBOOKER_PROCESS_QUEUE]= array ('PRIMARY' => 'blog_id, post_id' );
	$wordbooker_index_def[WORDBOOKER_FB_FRIENDS]= array ('PRIMARY' => 'user_id, facebook_id, blog_id' ,'user_id_idx' => 'user_id' ,'fb_id_idx' => 'facebook_id' ,'name_idx' => 'name' );
	$wordbooker_index_def[WORDBOOKER_FB_FRIEND_LISTS]= array ('PRIMARY' => 'user_id, flid' ,'list_type_idx'=> 'list_type') ;
	$wordbooker_index_fix[WORDBOOKER_ERRORLOGS]= array ('PRIMARY' => '  ADD PRIMARY KEY (sequence_id);' ,'timestamp_idx' => ' ADD INDEX timestamp_idx (`timestamp`);' ,'blog_idx' => 'ADD INDEX blog_idx(blog_id);' );
	$wordbooker_index_fix[WORDBOOKER_POSTLOGS]= array ('PRIMARY' => ' ADD PRIMARY KEY (blog_id, post_id);' );
	$wordbooker_index_fix[WORDBOOKER_USERDATA]= array ('PRIMARY' => 'ADD PRIMARY KEY (user_ID, blog_id);' ,'facebook_idx' => 'ADD INDEX facebook_idx (facebook_id);' );
	$wordbooker_index_fix[WORDBOOKER_USERSTATUS]= array ('PRIMARY' => 'ADD PRIMARY KEY (user_ID, blog_id);' );
	$wordbooker_index_fix[WORDBOOKER_POSTCOMMENTS]= array ('fb_comment_id_idx' => ' ADD INDEX fb_comment_id_idx (fb_comment_id);' ,'in_out_idx' => 'ADD INDEX in_out_idx (in_out);' ,'main_index' => 'ADD INDEX main_index (blog_id, wp_post_id, fb_post_id, wp_comment_id);' ,'timestamp' => 'ADD INDEX `timestamp` (comment_timestamp);' );
	$wordbooker_index_fix[WORDBOOKER_PROCESS_QUEUE]= array ('PRIMARY' => 'ADD PRIMARY KEY (blog_id, post_id)' );
	$wordbooker_index_fix[WORDBOOKER_FB_FRIENDS]= array ('PRIMARY' => 'ADD PRIMARY KEY (user_id, facebook_id, blog_id);' ,'user_id_idx' => 'ADD INDEX user_id_idx (user_id);' ,'fb_id_idx' => 'ADD INDEX fb_id_idx (facebook_id);' ,'name_idx' => 'ADD FULLTEXT INDEX name_idx (name);' );
	$wordbooker_index_fix[WORDBOOKER_FB_FRIEND_LISTS]= array ('PRIMARY' => 'ADD PRIMARY KEY (user_id, flid);'  ,'list_type_idx' => 'ADD INDEX list_type_idx (list_type);' ) ;

	$wordbooker_storage[WORDBOOKER_ERRORLOGS]="Not important";
	$wordbooker_storage[WORDBOOKER_POSTLOGS]="Not important";
	$wordbooker_storage[WORDBOOKER_USERDATA]="Not important";
	$wordbooker_storage[WORDBOOKER_USERSTATUS]="Not important";
	$wordbooker_storage[WORDBOOKER_POSTCOMMENTS]="Not important";
	$wordbooker_storage[WORDBOOKER_PROCESS_QUEUE]="Not important";
	$wordbooker_storage[WORDBOOKER_FB_FRIENDS]="MyISAM";
	$wordbooker_storage[WORDBOOKER_FB_FRIEND_LISTS]="Not important";

# this is used by Steve to build new data sets
/*
	foreach ($table_array as $table) {
				   $sql='describe '.$table;
				   echo "<br /> ------------------------------------------<br />";
				   echo $sql."<br />";
			$rows =  $wpdb->get_results($sql,ARRAY_A);
					foreach ($rows as $row ) {
				echo "'".$row['Field']."',";
			}
			echo "<br />";
			foreach ($rows as $row ) {
				echo "'".$row['Field']."'=>'".$row['Type']."',";
			}
			echo "<br />";
		}
			foreach ($table_array as $table) {
				   $sql='show create table '.$table. '';
				   echo "<br /> ------------------------------------------<br />";
				   echo $sql."<br />";
			$rows =  $wpdb->get_results($sql,ARRAY_A);
					foreach ($rows as $row ) {
			//	echo "'".$row['Create Table']."',";
				$x=preg_split("/ ENGINE=/",$row['Create Table']);
				$x2=preg_split("/ /",$x[1]);
				var_dump($x2[0]);
			}
		}
	foreach ($table_array as $table) {
				   $sql='show index from '.$table;
		//		   echo "<br /> ------------------------------------------<br />";
		//		   echo $sql."<br />";
			$rows =  $wpdb->get_results($sql,ARRAY_A);
	//		foreach ($rows as $row ) {
		//		echo "'".$row['Key_name']."',";
	//		}
			echo "<br />";
			$last='Wooble';
			$idx_line='';
			foreach ($rows as $row ) {
				if($last!=$row['Key_name']) {
				//	echo $idx_line."<br />";
					if(strlen($idx_line)>6){$idx_lines[$table][]=$idx_line."'";}
					 $idx_line="'".$row['Key_name']."'='".$row['Column_name'];
					 }
				else {$idx_line.=", ".$row['Column_name'];}
				$last=$row['Key_name'];
			}
			$idx_lines[$table][]=$idx_line."'";
			//var_dump($idx_lines[$table]);
			$cur_ind=array();
			foreach($idx_lines[$table] as $fruit){
		//		 echo $fruit;echo "<br />";
				 $junk=explode("=",$fruit);
				 $cur_ind[$junk[0]]=$junk[1];
				 }
				 print_r($cur_ind);
			echo "<br />";
		}
*/
	// Cross check Table columns
	foreach ($table_array as $table) {
		$working_table=$wordbooker_columns[$table];
		$working_table_def=$wordbooker_column_def[$table];
		$sql='describe '.$table;
		$rows =  $wpdb->get_results($sql,ARRAY_A);
		foreach ($working_table as $chardata){
			$found=0;
			foreach ($rows as $row ) {
				if (strcasecmp($chardata,$row['Field'])==0) {
					$col_status=" present";$correct_sql='zed';
					if (strcasecmp($working_table_def[$chardata],$row['Type'])==0) {$col_def_status=" matches";$correct_def_sql='zed';}
					else {$col_status=" mismatches"; $correct_def_sql="alter table ".$table." change ".$chardata." ".$chardata." ".$working_table_def[$chardata];}
					$found=1;
					break;
				}
			}
			if ($found==0){
					$col_status=" missing"; $correct_sql="alter table ".$table." add ".$chardata." ".$working_table_def[$chardata];
				}
				if ($correct_sql!='zed') {$sql_run[]=$correct_sql;}
				if ($correct_def_sql!='zed') {$sql_run[]=$correct_def_sql;}
				}
	}
	# Cross check storage....
	foreach ($table_array as $table) {
		$sql='show create table '.$table. '';
		$rows =  $wpdb->get_results($sql,ARRAY_A);
		foreach ($rows as $row ) {
			$x=preg_split("/ ENGINE=/",$row['Create Table']);
			$x2=preg_split("/ /",$x[1]);;
			if($wordbooker_storage[$table]!=$x2[0] && $wordbooker_storage[$table]!='Not important') {
				$sql_run[]='ALTER TABLE '.$table.' ENGINE = '.$wordbooker_storage[$table];
			}
		}
	}
	// Cross check Indexes
	foreach ($table_array as $table) {
		$working_index_fix=$wordbooker_index_fix[$table];
		$working_index_def=$wordbooker_index_def[$table];
		$sql='show index from '.$table;
		$rows =  $wpdb->get_results($sql,ARRAY_A);
		$last='Wooble';
		$idx_line='';
		foreach ($rows as $row ) {
			if($last!=$row['Key_name']) {
				if(strlen($idx_line)>6){$idx_lines[$table][]=$idx_line;}
				$idx_line=$row['Key_name']."=".$row['Column_name'];
			}
			else {$idx_line.=", ".$row['Column_name'];}
			$last=$row['Key_name'];
		}
		$idx_lines[$table][]=$idx_line;
		foreach($idx_lines[$table] as $fruit){
			$junk=explode("=",$fruit);
			$cur_ind[$junk[0]]=$junk[1];
		}
		foreach ($working_index_def as $key=>$chardata){
			$found=0;
			foreach ($rows as $row ) {;
				if (strcasecmp($key,$row['Key_name'])==0) {
					$found=1;
					$col_status=" present";
					$correct_sql='zed';
					$correct_sql2='zed';
					if (strcasecmp($working_index_def[$key],$cur_ind[$key])==0) {$col_def_status=" matches";$correct_def_sql='zed';}
					else {
						if ($row['Key_name']='PRIMARY') {
						$col_status=" mismatches";
						 $correct_sql="alter table ".$table." drop ".$row['Key_name']." key";
						 $correct_sql2="Alter table ".$table." ".$working_index_fix[$key];}
						 else {
						$col_status=" mismatches";
						$correct_sql="alter table ".$table." drop index ".$row['Key_name'];
						$correct_sql2="Alter table ".$table." ".$working_index_fix[$key];}
						 }
						break;
					}
				}
				if ($found==0){
					$col_status=" missing"; $correct_sql="Alter table ".$table." ".$working_index_fix[$key];
				}
			if ($correct_sql!='zed') {$sql_run[]=$correct_sql;}
			if ($correct_sql2!='zed') {$sql_run[]=$correct_sql2;}
		}
	}
	if (is_array($sql_run)) {
		echo '<div id="message" class="updated fade"><p>';
		_e("Schema differences found - fixing up ", 'wordbooker');
		echo '<br /></p></div>';
		foreach($sql_run as $sql_fix) {
			wordbooker_debugger("SQL Fixup : ",$sql_fix,-7,99);
			$result=$wpdb->get_results($sql_fix);
			if (strlen($wpdb->last_error)>4) {wordbooker_debugger("SQL Fixup Fail : ",$wpdb->last_error,-7,99);}
		}
		echo "<br />";
	}
	wordbooker_set_option('schema_vers', WORDBOOKER_SCHEMA_VERSION );
	$doy=date ( 'z');
	wordbooker_set_option('schema_check', $doy );
	$dummy=wp_clear_scheduled_hook('wb_cron_job');
	$dummy=wp_schedule_event(time(), 'hourly', 'wb_cron_job');
}

function wordbooker_upgrade() {
	global $wpdb, $table_prefix,$blog_id;
	$errors = array();
	$wordbooker_settings=wordbooker_options();
	if (! isset($wordbooker_settings['schema_vers'])) {wordbooker_activate(); return;}
	if ($wordbooker_settings['schema_vers'] != WORDBOOKER_SCHEMA_VERSION ) {
	#tidies up after an SVN crapout
	if(file_exists(dirname(__FILE__).'/trunk')) {if (filetype(dirname(__FILE__).'/trunk') == 'dir') {wordbooker_rrmdir(dirname(__FILE__).'/trunk');}}
	# Removes an unwanted file.
	if(file_exists(dirname(__FILE__).'/includes/wordbooker_channel.php')) {unlink(dirname(__FILE__).'/includes/wordbooker_channel.php'); }
	# We use this to make changes to Schema versions. We need to get the current schema version the user is using and then "upgrade" the various tables.
		 _e("Database changes being applied", 'wordbooker');
		  wordbooker_db_crosscheck();
	} else {
		return;
	}
	wp_cache_flush();
}
?>