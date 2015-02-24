<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of statistic_page.php
 * Render html
 *
 * @author Emelina
 */
//Our class extends the WP_List_Table class, so we need to make sure that it's there

function render_statistic_notification() {
    $myListTable = new Statistic_Table();
    echo '</pre><div class="wrap"><h2>Statistics on the number of user tab Notification</h2>';
    $myListTable->prepare_items();
    ?>
    <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">
    <?php
    $myListTable->search_box(array(
        array(
            'text'=> 'search',
            'input_id'=> 's',
            'place_holder' => 'キーワードをご入力ください。'
        ),
        array(
            'text'=> 'from',
            'input_id'=> 'from',
            'place_holder' => 'yyyy/mm/dd'
        ),
        array(
            'text'=> 'To',
            'input_id'=> 'to',
            'place_holder' => 'yyyy/mm/dd'
        )
    ));

    $myListTable->display();
    echo '</form></div>';
}

render_statistic_notification();