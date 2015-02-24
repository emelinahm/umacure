<?php

  if (!defined('ABSPATH')) die();

  global $der_framework, $locale;

  $lang = $_POST['lang'];
  
  switch ($_POST['operation']) {
    
    case 'select':
    case 'add':

      // Redirect
      poedit_redirect($lang, $locale);

      break;
      
    case 'update':
    
      // Get POST variables
      $newcontent = stripslashes($_POST['newcontent']);
      $lang = $_POST['lang'];
      
      // Update file
      $po = $der_framework->path('includes/languages/' . $lang . '.po');
      $f = fopen($po, 'w');
      fwrite($f, $newcontent);
      fclose($f);
      
      // Build binary .mo file
      require(TEMPLATEPATH . '/framework/lib/php-mo.php');
      phpmo_convert($der_framework->path("includes/languages/${lang}.po"), $der_framework->path("includes/languages/${lang}.mo"), true);
      
      // Redirect
      poedit_redirect($lang, $locale);
      
      break;
      
    default:
      die('-1:2');
      break;
    
  }
  
  /* Redirects based on language */
  
  function poedit_redirect($lang, $locale) {
    // Redirect to the poedit default url
    if ($lang == $locale) {
      header(sprintf('Location: %s', admin_url('admin.php?page=translator')));
    // Redirect to the language-specific uri
    } else {
      header(sprintf('Location: %s&lang=%s', admin_url('admin.php?page=translator'), $lang));
    }
    exit();
  }
  
?>