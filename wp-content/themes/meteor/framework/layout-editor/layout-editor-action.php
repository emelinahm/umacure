<?php

if (!defined('ABSPATH')) die();

global $der_framework;

// Save layout data
$json = stripslashes($_POST['data_json']);
$json = str_replace(' ', ' ', $json);
$array = (array) json_decode($json, true);

if (is_array($array)) {
  $der_framework->update_option('layouts', $array);
  $der_framework->update_option('layouts_json', $json);
  exit('1');
} else {
  exit('0');
}

?>