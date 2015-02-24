<?php

/**
 * Render notification table
 */

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Statistic_Table extends WP_List_Table {

    var $example_data = array();

    function __construct() {
        parent::__construct(array(
            'singular' => __('notification', 'mylisttable'), //singular name of the listed records
            'plural' => __('notifications', 'mylisttable'), //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));
        add_action('admin_head', $this->admin_header());
        $getNotificationStatistic = new NotificationManagement();
        $this->statistic_data = $getNotificationStatistic->getNotificationStatistic();
        
    }

    /**
     * Add style script for view 
     */
    function admin_header() {
        $page = ( isset($_GET['page']) ) ? esc_attr($_GET['page']) : false;
        if ('notification_management_setting' != $page)
            return;
        echo '<style type="text/css">';
        echo '.wp-list-table .column-post_id { width: 10%; }';
        echo '.wp-list-table .column-created_at { width: 20%; }';
        echo '.wp-list-table .column-iOS_count { width: 10%; }';
        echo '.wp-list-table .column-Android_count { width: 10%;}';
        echo '</style>';
    }

    /**
     * Override function no_items of WP_List_Table
     */
    function no_items() {
        _e('No Push Notification found, dude.');
    }

    
    /**
     * Override function column_default
     * Render value of cell.
     * @param Array $item
     * @param String $column_name
     * @return String
     */
    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'post_id':
            case 'post_title' :
            case 'created_at':
            case 'iOS_count':
            case 'Android_count':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Set enable/disable sort function for each column
     * @return array
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'post_id' => array('post_id', true),
            'post_title' => array('post_id', false),
            'created_at' => array('created_at', true),
            'iOS_count' => array('iOS_count', false),
            'Android_count' => array('Android_count', false)
        );
        return $sortable_columns;
    }
    

    /**
     * Set title of columns
     * @return Array
     */
    function get_columns() {
        $columns = array(
            'post_id' => __('ID', 'mylisttable'),
            'post_title' => __('タイトル', 'mylisttable'),
            'created_at' => __('時間', 'mylistable'),
            'iOS_count' => __('カウント(iOS)', 'mylisttable'),
            'Android_count' => __('カウント（Adnroid)', 'mylisttable')
        );
        return $columns;
    }

    /**
     * Set type of column to sort values
     * Value: string|number|datetime
     * @return Array
     */
    function get_type_columns() {
        $type_columns = array(
            'post_id' => 'number',
            'post_title' => 'string',
            'created_at' => 'datetime',
            'iOS_count' => 'number',
            'Android_count' => 'number'
        );
        return $type_columns;
    }
    
    /**
     * Sort array by string|number|datetime
     * @param type $a
     * @param type $b
     * @return type
     */
    function usort_reorder($a, $b) {
        //Check type of sort
        $type_columns = $this->get_type_columns();
        // If no sort, default to title
        $orderby = (!empty($_GET['orderby']) ) ? $_GET['orderby'] : 'created_at';
        // If no order, default to asc
        $order = (!empty($_GET['order']) ) ? $_GET['order'] : 'desc';
        // Determine sort order
        switch ($type_columns[$_GET['orderby']]) {
            case 'string':
                $result = strcmp($a[$orderby], $b[$orderby]);
                break;
            case 'number':
                $result = 0;
                if ($a[$orderby] > $b[$orderby]) {
                    $result = 1;
                } else if ($a[$orderby] < $b[$orderby]) {
                    $result = -1;
                }
                break;
            case 'datetime' :
            default:
                $result = strcmp($a[$orderby], $b[$orderby]);
                break;
        }
        
        // Send final sort direction to usort
        return ( $order === 'asc' ) ? $result : -$result;
    }


    function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        usort($this->statistic_data, array(&$this, 'usort_reorder'));

        $per_page =10;
        $current_page = $this->get_pagenum();
        $total_items = count($this->statistic_data);

        // only ncessary because we have sample data
        $this->found_data = array_slice($this->statistic_data, ( ( $current_page - 1 ) * $per_page), $per_page);

        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page                     //WE have to determine how many items to show on a page
        ));
        $this->items = $this->found_data;
    }
    
    /**
     * Render search box
     * @param Array $inputs :
     * array(
     *    array(
     *        'text'=> 'search',
     *        'input_id'=> 's',
     *        'place_holder' => 'Type your key words'
     *    )
     * @return echo html
     */
    function search_box($inputs) {
        if (empty($_REQUEST['s']) && empty($_REQUEST['from']) && empty($_REQUEST['to']) && !$this->has_items())
            return;

        $input_id = $input_id . '-search-input';

        if (!empty($_REQUEST['orderby']))
            echo '<input type="hidden" name="orderby" value="' . esc_attr($_REQUEST['orderby']) . '" />';
        if (!empty($_REQUEST['order']))
            echo '<input type="hidden" name="order" value="' . esc_attr($_REQUEST['order']) . '" />';
        if (!empty($_REQUEST['post_mime_type']))
            echo '<input type="hidden" name="post_mime_type" value="' . esc_attr($_REQUEST['post_mime_type']) . '" />';
        if (!empty($_REQUEST['detached']))
            echo '<input type="hidden" name="detached" value="' . esc_attr($_REQUEST['detached']) . '" />';
    ?>
        <p class="search-box">
            <?php
            foreach ($inputs as $value) {
                ?>
                <label class="screen-reader-text" for="<?php echo $value['input_id']; ?>"><?php echo $value['text']; ?>:</label>
                <input type="search" id="<?php echo $value['input_id']; ?>" name="<?php echo $value['input_id']; ?>" value="<?php echo $_REQUEST[$value['input_id']]; ?>" placeholder="<?php  echo $value['place_holder']; ?>" />
            <?php } ?>
        <?php submit_button("検索", 'button', false, false, array('id' => 'search-submit')); ?>
        </p>
        <?php
    }

}