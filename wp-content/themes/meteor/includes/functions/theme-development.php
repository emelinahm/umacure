<?php

  function theme_development_javascript() { global $der_framework;
    
    $out = array();
    $json = @file_get_contents($der_framework->path('core/js/data/meteor-core.json'));
    $load = json_decode($json);

    foreach ($load->libraries as $js) {
      $out[] = sprintf('<script type="text/javascript" src="%s"></script>', $der_framework->uri($js, THEME_VERSION));
    }
    
    echo implode("\n", $out) . "\n";

  }

?>