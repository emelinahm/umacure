<?php

theme_metabox(array(
  'id' => 'pricing-metabox',
  'title' => 'Package Details',
  'callback' => 'pricing_metabox_callback',
  'post_type' => 'pricing',
));

function pricing_metabox_callback() { global $der_framework, $metabox;

  $metabox->set_nonce();
  
  $metabox->text(array(
    'key' => 'price',
    'title' => "Package Price",
    'desc' => "Price to use for this package."
  ));
  
  $metabox->text(array(
    'key' => 'method',
    'title' => "Billing Method",
    'desc' => "How is this package billed. Examples: <code>/mo, /year, /day, /hour</code>"
  ));
  
  $metabox->text(array(
    'key' => 'url',
    'title' => "Package URL",
    'desc' => "URL containing information about the package."
  ));
    
  $metabox->select(array(
    'key' => 'active',
    'title' => "Active Package",
    'width' => "100",
    'desc' => "If enabled, this entry will be highlighted as the main package.",
    'default' => "no",
    'options' => array(
      'yes' => "Yes",
      'no' => "No"
    )
  ));
  
  $metabox->text(array(
    'key' => 'order',
    'title' => "Order",
    'desc' => "Position in which this package is displayed."
  ));
  
}

?>