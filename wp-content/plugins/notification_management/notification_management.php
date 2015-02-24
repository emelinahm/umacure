<?php
/*
  Plugin Name: Notification Management
  Plugin URI: http://fyaconiello.github.com/wp-plugin-template
  Description: Statistic number of tab notification for Android, iOS.
  Version: 1.0
  Author: NTQ-SOFT
 */

/**
 * Description of notification_management
 * 
 * Require: 
 * This plugin only support for push_notification plugin. 
 * So you have to install Push Notification first and have "$wpdb->db . 'post_notifications'" table.
 * If have not that table you have create new table : 
 * (
 *    `notification_id` int(11) NOT NULL,
 *    `object_id` int(11) NOT NULL,
 *    `token` varchar(255) NOT NULL,
 *    `is_read` tinyint(1) DEFAULT '0',
 *    `created_at` datetime DEFAULT NULL,
 *    `tab_datetime` datetime DEFAULT NULL,// Optional
 *    `is_tab` tinyint(1) NOT NULL DEFAULT '0'//Optional
 *  );
 * 
 * Todo: 
 * - Static number of tab notification for Android , iOS
 * - Filter by datetime.Return : datetime, device ID, type(iOS|Android)
 *
 * @author Emelina
 */
global $wpdb;
//define('NM_VERSION', '4.0.1');
//define('NM_PLUGIN_BASENAME', plugin_basename(__FILE__));
//define('NM_PLUGIN_NAME', trim(dirname(WPCF7_PLUGIN_BASENAME), '/'));
//define('NM_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));
define('NM_PLUGIN_DIR', untrailingslashit(dirname(__FILE__)));
define('NM_PLUGIN_CLASSES_DIR', NM_PLUGIN_DIR . '/classes');
define('NM_PLUGIN_VIEW_DIR', NM_PLUGIN_DIR . '/view');
$notification_management_table_name = $wpdb->prefix . "post_notifications";
define("NM_TABLE", $notification_management_table_name);
require_once NM_PLUGIN_CLASSES_DIR . '/notification.php';
require_once NM_PLUGIN_VIEW_DIR . '/table.php';

if (!class_exists('NotificationManagement')) {

    class NotificationManagement {
        /*
         * Request parameter names
         */

        protected $param_names = array();

        /*
         * Request parameters
         */
        protected $parameters = array();

        /*
         * Request parameters
         */
        protected $accept_parameters = array(
            'from' => '',
            'to' => '',
            's' => ''
        );

        /**
         * Construct the plugin object
         */
        public function __construct() {
            //Register actions.
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array($this, 'admin_menu'));

            //Accept parameter
            $this->param_names = array(
                'from' => '',
                'to' => '',
                's' => ''
            );
            $this->init_api_params();
        }

        public static function activate() {
            //@TODO: create table
            global $wpdb;
            $sql = "ALTER TABLE " . NM_TABLE . "
            ADD COLUMN `tab_datetime` datetime DEFAULT NULL,
            ADD COLUMN `is_tab` tinyint(1) NOT NULL DEFAULT '0';";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            $wpdb->query($sql);
        }

        public static function deactivate() {
            
        }

        public static function uninstall() {
            global $wpdb;

            //Delete table            
            $sql = "ALTER TABLE " . NM_TABLE
                    . " DROP COLUMN `tab_datetime`,"
                    . " DROP COLUMN `is_tab`";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $wpdb->query($sql);
        }

        public function admin_init() {
            $this->init_settings();
        }

        /**
         * Initialize some custom settings
         */
        public function init_settings() {
            //register_setting('wp_plugin')
        }

        /**
         * Add link to setting menu and render template.
         */
        function admin_menu() {
            add_options_page('Notification Management Settings', '通知統計', 'manage_options', 'notification_management_setting', array(&$this, 'settings_page'));
        }

        function settings_page() {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the settings template
            include(sprintf("%s/templates/statistic_page.php", dirname(__FILE__)));
        }

        /*
         * Initialize the api parameters variable
         */

        protected function init_api_params() {

            $source = $_REQUEST;
            foreach ($this->param_names as $param_name => $type) {
                if (isset($source[$param_name]) && isset($this->accept_parameters[$param_name]) && trim($source[$param_name]) != "") {
                    $value = $source[$param_name];

                    $this->parameters[$param_name] = $type === 'int' ? intval($value) : $value;
                }
            }
        }

        //Get notifications
        public function getNotifications() {
            return filterNotifications($this->parameters);
        }

        //Get notifications statistic data
        public function getNotificationStatistic() {
            return statisticNotifications($this->parameters);
        }

        /**
         * Render list of notification
         */
        function my_render_list_page() {
            $myListTable = new My_Example_List_Table();
            echo '</pre><div class="wrap"><h2>My List Table Test</h2>';
            $myListTable->prepare_items();
            ?>
            <form method="post">
                <input type="hidden" name="page" value="ttest_list_table">
            <?php
            $myListTable->search_box('search', 'search_id');

            $myListTable->display();
            echo '</form></div>';
        }

    }

}

if (class_exists('NotificationManagement')) {
    //Installation and Uninstallation hooks
    register_activation_hook(__FILE__, array('NotificationManagement', 'activate'));
    register_deactivation_hook(__FILE__, array('NotificationManagement', 'deactivate'));
    register_uninstall_hook(__FILE__, array('NotificationManagement', 'uninstall'));

    //Instantiate the plugin class
    $wp_plugin_template = new NotificationManagement();
}