<?php

  if (!defined('ABSPATH')) die();

  global $der_framework;
  
  // Detect locale or use a specific one
  if (isset($_GET['lang'])) {
    $locale = $_GET['lang'];
  } else {
    global $locale;
  }
  
  // $locale = 'es_DO';
  
  $ajaxurl = wp_nonce_url(admin_url('admin-ajax.php?action=translator_action'));
  
  $languages = $der_framework->locales;
  
  $langpath = get_template_directory() . '/includes/languages';
  
  $pofiles = $der_framework->get_files('includes/languages', 'po');
  
  $locale_available = in_array($locale . '.po', $pofiles);
  
  $buf = ($locale_available) ? file_get_contents(sprintf('%s/%s.po', $langpath, $locale)) : file_get_contents(sprintf('%s/%s', $langpath, 'template.pot'));
  
?>
<div id="wpbody-content" class="poedit">

  <div class="wrap">

    <div id="icon-options-general" class="icon32"><br></div><h2>Edit Translations</h2>

    <div class="fileedit-sub">

      <div class="alignleft">
<?php


    $lang = (array_key_exists($locale, $languages)) ? $languages[$locale] : 'Unknown Language';
      
    printf("<h3>%s <span>(%s.po)</span></h3>", $lang, $locale);
    
?>
        
      </div>

      <div class="alignright">
        
        <form action="<?php echo $ajaxurl ?>" method="post">

          <strong><label for="lang">Select language to edit: &nbsp;</label></strong>

          <select name="lang" id="language-select">
<?php

    printf("\n" . '            <option value="%s" selected="selected">%s</option>', $locale, $lang);
    
    foreach ($pofiles as $lc) {
      
      $lc = preg_replace('/.po$/', '', $lc);

      if ($lc == $locale) continue;
      
      $lng = (array_key_exists($lc, $languages)) ? $languages[$lc] : "Unknown Language";
      
      printf('            <option value="%s">%s</option>', $lc, $lng);
      
    }

?>
          </select>
          
          <input type="hidden" name="operation" value="select" />
          
          <?php $der_framework->nonce(); ?>

          <input type="submit" class="button" value="Select">
          
        </form>
        
      </div>

      <br class="clear">

    </div>
    
    <div id="templateside" style="float: right;">
      
      	<h3>Add new Language</h3>
      	
      	<form style="margin-top: 1em;" action="<?php echo $ajaxurl ?>" method="post">
      	  <select name="lang" style="margin-bottom: 0.5em;">
<?php

        foreach ($der_framework->locales as $code => $alias) {
          printf("\n" . '      	    <option value="%s">%s</option>', $code, $alias);
        }

?>
      	  </select>
      	  
          <input type="hidden" name="operation" value="add" />
          
          <?php $der_framework->nonce(); ?>

          <input type="submit" class="button" value="Add Language">
          
        </form>
        
        <br/><br/>
        
        <h3>Editing Files</h3>
        
        <p>Replace the contents of each of the <strong>msgstr</strong> lines with the corresponding translation.</p>
        
        <p>The corresponding .mo file is automatically updated, and your site reflects the changes immediately.</p> 
        
         <p>Additionally, you can edit your .po files in your computer using <a href="http://www.poedit.net/download.php">Poedit</a>.</p>
         
         <p>If you edit the .po files locally, make sure you upload the .mo/po files to your server via FTP when you're done.</p>
        
    </div>

    <form name="pofile" id="template" action="<?php echo $ajaxurl ?>" method="post">

      <textarea cols="70" rows="30" name="newcontent" id="newcontent" tabindex="1"><?php
        
      echo $buf;
        
      ?></textarea>

        <input type="hidden" name="operation" value="update">
        <input type="hidden" name="lang" value="<?php echo $locale ?>">
        <input type="hidden" name="scrollto" id="scrollto" value="0">
        
        <?php $der_framework->nonce(); ?>

      </div>

      <div>
        <p class="submit">
          <input type="submit" class="button-primary" value="Update File" tabindex="2" style="margin-right: 20px;" />
        </p>		
      </div>
    </form>
    <br class="clear">
  </div>
  <script type="text/javascript">
  /* <![CDATA[ */
  jQuery(document).ready(function($){
    $('#template').submit(function(){ $('#scrollto').val( $('#newcontent').scrollTop() ); });
    $('#newcontent').scrollTop( $('#scrollto').val() );
  });
  /* ]]> */
  </script>

  <div class="clear"></div></div><!-- wpbody-content -->
  <div class="clear"></div>