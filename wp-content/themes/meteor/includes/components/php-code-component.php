<?php

  function php_code_component($atts, $content='', $code='') { global $der_framework;

    $defaults = array(
      'code' => null
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    $output = ($code == 'php_code');
    
    if ($output) {
      $open = $der_framework->render('
<div class="php-code post-content {{{container_class}}}{{#visibility}} {{{visibility}}}{{/visibility}}"{{#inline_css}} style="{{{inline_css}}}"{{/inline_css}}>
{{#title_heading}}<h2 class="title-heading">{{title_heading}}</h2>{{/title_heading}}', $args);
      $close = "\n</div><!-- .php-code -->";
    } else {
      $open = $close = '';
    }

    $func = create_function('', $args['code']);
    
    echo $open;
    echo $func(); // Using echo to catch return value (if any)
    echo $close;

    return null;

  }

?>