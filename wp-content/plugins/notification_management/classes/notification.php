<?php

/**
 * Description of notification
 * To get data of notifications and render data for view.
 *
 * @author Emelina
 */
function statisticNotifications($parameter) {
    //@TODO: get list of notification
    global $wpdb;
    $sql = "SELECT t.object_id, t.created_at,t.tab_datetime,t.token, tt.token,t.is_tab,tt.type,ttt.post_title, "
            . "sum(case when (tt.type = 0 and t.is_tab = 1) then 1 else 0 end) as iOS_count,"
            . "sum(case when (tt.type = 1 and t.is_tab = 1) then 1 else 0 end) as Android_count"
            . " FROM " . NM_TABLE . " as t";
    
    //Get device infomation 
    $sql .= " LEFT JOIN {$wpdb->prefix}devices as tt ON t.token=tt.token";
    $sql .= " LEFT JOIN {$wpdb->prefix}posts as ttt ON t.object_id=ttt.ID";
    if (count($parameter) > 0) {
        $sql .= " WHERE 1=1 ";
        $sql .= (($parameter['from']) ? " AND t.created_at > '" . $parameter['from'] . "'" : "");
        $sql .= (($parameter['to']) ? " AND t.created_at < '" . $parameter['to'] . "'" : "");
        $sql .= (($parameter['s']) ? " AND ttt.post_title LIKE '%" . $parameter['s'] . "%'" : "");
    }
   
    $sql .= " GROUP BY t.object_id";
    $sql .= " ORDER BY  t.created_at ASC";
    $data = $wpdb->get_results($sql);
    return convertDataToArray($data);
}

function convertDataToArray($data) {
    $arrReturn = array();
    foreach ($data as $value) {
        $arrReturn[] = array(
            "post_id" => $value->object_id,
            "post_title" => $value->post_title,
            "created_at" => $value->created_at,
            "iOS_count" => $value->iOS_count,
            "Android_count" => $value->Android_count,
        );
    }
   
    return $arrReturn;
}