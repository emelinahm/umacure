<?php
/*
Plugin Name: WordTwit Twitter Plugin
Plugin URI: http://wordpress.org/plugins/wordtwit/
Version: 3.6
Description: All-new version of the popular WordPress to Twitter publishing tool!
Author: BraveNewCode
Author URI: http://www.bravenewcode.com
Text Domain: wordtwit-pro
Domain Path: /lang
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html

# 'WordTwit' and 'WordTwit Pro' are unregistered trademarks of BraveNewCode Inc.,
# and cannot be re-used in conjuction with the GPL v2 usage of this software
# under the license terms of the GPL v2 without the express prior written
# permission of BraveNewCode Inc.
*/

// Should not have spaces in it, same as above
define( 'WORDTWIT_VERSION', '3.6' );

// Configuration
require_once( 'include/config.php' );

// Default settings
require_once( 'include/settings.php' );

// Helper classes
require_once( 'include/classes/array-iterator.php' );
require_once( 'include/classes/oauth.php' );
require_once( 'include/classes/debug.php' );

// Administration Panel
require_once( 'admin/admin-panel.php' );
require_once( 'admin/template-tags/account.php' );

// Main WordTwit Class
require_once( 'include/classes/wordtwit.php' );

$wordtwit_pro = new WordTwitPro();

function wordtwit_create_object() {
	global $wordtwit_pro;
        
        //DucNT mod 20141225 start==========================================
        //Fix error in case variable $wordtwit_pro is not initialized
        //Cause error: call initialize() by a non object.
        if (!is_a($wordtwit_pro, 'WordTwitPro')) {
            $wordtwit_pro = new WordTwitPro();
        }
        //DucNT mod 20141225 end============================================
	// Initialize WordTwit, this is where the magic happens
	$wordtwit_pro->initialize();

	require_once( 'include/globals.php' );

	do_action( 'wordtwit_loaded' );
}

add_action( 'plugins_loaded', 'wordtwit_create_object' );
