<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tools
 *
 * @author NTQ-SOFT
 */
class Tools {

    public static function array_sort_by_column(&$array, $column, $direction = SORT_DESC) {
        $reference_array = array();
        
        foreach ($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }

        array_multisort($reference_array, $direction, $array);
    }
}
