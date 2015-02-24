<?php

  if (!defined('ABSPATH')) die();

  /////////////////////////////////////////////////
  // VALIDATION
  /////////////////////////////////////////////////

  global $der_framework, $raw_message;

  // Validate with form keys - level 0
  if (empty($_POST) OR empty($_POST['__metadata__'])) die('-1:0');
  
  // Validate active theme - level 1
  if (empty($der_framework)) die('-1:1');
  
  // Get metadata from POST
  $metadata = $der_framework->decrypt_array($_POST['__metadata__']);
  
  // Provides: $from_name, $from_email, $recipient, $subject, $keys, $cc, $bcc
  extract($metadata);
  
  // Validate email - level 3
  if (!preg_match('/^[^@](.+)@(.+)[^@]$/', $recipient)) die('-1:2');
  

  /////////////////////////////////////////////////
  // MESSAGE PROCESSING
  /////////////////////////////////////////////////

  // Prepare message
  $message_arr = array();
  $alldata = array();
  $all_labels = array();
  $checkbox_keys = array();
  
  // Process keys
  foreach ($keys as $key) {
    $data = mailer_get_data($key);
    if ($data) {
      $alldata[] = $data;
      $all_labels[$data['label']] = $data['val'];
      $text = ( isset($data['checkbox']) && $data['checkbox'] ) ? implode(', ', $data['val']) : $data['val'];
      $message_arr[] = sprintf('<strong style="color: #282828;">%s</strong>: %s', esc_html($data['label']), esc_html($text));
    }
  }
  
  // Template replacements
  $subject = do_template($subject, $all_labels);
  
  // Set default subject
  if (empty($subject) && $from_name && $from_email) {
    $subject = do_template(sprintf("%s {{%s}} <{{%s}}>", __("Message from", "theme"), $from_name, $from_email), $all_labels);
  }
  
  // Process Sender & Email
  $from_name = do_template(sprintf('{{%s}}', $from_name), $all_labels);
  $from_email = do_template(sprintf('{{%s}}', $from_email), $all_labels);
  
  // Implode the messages
  $message = implode("\n\n", $message_arr);
  
  // Convert plain text to html
  $message = apply_filters('the_excerpt', $message);
  
  // Make links from relevant content
  $message = make_clickable($message);
  
  // Style links
  $message = preg_replace('/<a href=/', '<a style="color: #316ef3;" href=', $message);
  
  // Add coming from
  $message .= sprintf('<div style="word-spacing: 0.1em; margin-top: 2em; border-top: solid 1px #eee; padding: 1em 0; font-weight: 300; font-size: 0.9em; color: #cecece;">
%s <a style="color: #838383; text-decoration: none;" title="%s" href="%s">%s</a>.&nbsp; %s: %s
</div>', __("Sent from", "theme"), get_bloginfo('description'), get_home_url(), get_bloginfo('name'), __("Sender's IP Address", "theme"), $_SERVER['REMOTE_ADDR']);
  
  // Add formatting to message
  $message = '
<!DOCTYPE html>
<body style="margin: 0 2em; padding: 0;">
<div style="width: 100%; height: 100%;">
<div style="
  font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;
  font-size: 13px;
  line-height: 1.4em;
  font-weight: normal;
  margin: 2em 0;
  padding: 0; 
  border: 1px solid #ccc;
  background: white;
  color: #5a5a5a;
  box-shadow: 0 0 6px rgba(0,0,0,0.15);
  -webkit-box-shadow: 0 0 6px rgba(0,0,0,0.15);
  -moz-box-shadow: 0 0 6px rgba(0,0,0,0.15); ">                                                                
<div style="
  padding: 2em 3em;
  margin: 0 0 2em 0;
  background: #efefef;
  border-bottom: solid 1px #d5d5d5;
  box-sizing: border-box;
  -webkit-box-sizing: border-box; 
  -moz-box-sizing: border-box;">
  <h1 style="margin: 0; font-size: 1.5em; font-weight: 300; line-height: 1.3em;">' . esc_html($subject) . '</h1></div>
<div style="padding: 0 3em;">
' . $message . '
</div>
</body>
</html>';

  // Create raw message

  $raw_message = "\n" . $subject . "\n\n---\n\n";
  $raw_message .= strip_tags(implode("\n\n", $message_arr));
  $raw_message .= sprintf("\n\n---\n\n%s %s [%s]. %s: %s", "Sent from", get_bloginfo('name'), get_home_url(), "Sender's IP Address", $_SERVER['REMOTE_ADDR']);

  // Send message
  
  add_action('phpmailer_init', 'mailer_add_raw');
  
  mailer_send(array(
    'from_name' => $from_name,
    'from_email' => $from_email,
    'subject' => $subject,
    'recipient' => $recipient,
    'message' => $message,
    'cc' => $cc,
    'bcc' => $bcc
  ));


  /////////////////////////////////////////////////
  // FUNCTION DEFINITIONS
  /////////////////////////////////////////////////

  function mailer_send($args) {
    
    // http://core.trac.wordpress.org/browser/branches/3.5/wp-includes/pluggable.php

    // Provides: $from_name, $from_email, $recipient, $message, $cc, $bcc
    extract($args);
    
    $fail = 'ERR: ' . __("Unable to send your message.", "theme");

    if ( empty($from_name) || empty($from_email) ) die($fail);

    if (!is_valid_email($from_email)) die("ERR: " . "Invalid email address.");

    $success = __("Your message has been sent!", "theme");

    if (defined('DISABLE_MAILER') && DISABLE_MAILER) exit($success);

    // The blogname option is escaped with esc_html
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    $wp_email = 'wordpress@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));

    $from = "From: \"${blogname}\" <${wp_email}>";

    $reply_to = "Reply-To: \"${from_name}\" <${from_email}>";

    $headers = $from . "\n";
    $headers .= $reply_to . "\n";
    
    if (!empty($cc)) $headers .= sprintf("Cc: %s\n", trim($cc));
    if (!empty($bcc)) $headers .= sprintf("Bcc: %s\n", trim($bcc));

    $headers .= "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\n";
    
    // pre($headers);

    $sent = @wp_mail( $recipient, $subject, $message, $headers );

    // Note: sending the message doesn't actually mean the message was sent
    // correctly. It just means that the mail function returned true.
    $sent ? exit($success) : die($fail);

  }
  
  function mailer_add_raw($phpmailer) { global $raw_message;
    $phpmailer->AltBody = $raw_message;
  }

  function mailer_get_data($key) {
    $out = array();
    preg_match('/^(.+)\[(.+)\]\[(.+)\]$/', $key, $matches);
    if ($matches) {
      
      global $checkbox_keys;
      
      // Checkbox match
      $id = $matches[1];
      
      if (!empty($checkbox_keys) && in_array($id, $checkbox_keys)) return null;
      
      $checkbox_keys[] = $id;
      
      $subkey = $matches[2];
      $data = $_POST[$id][$subkey];

      $out['id'] = $id;
      $out['label'] = base64_decode($subkey);
      $out['val'] = array();
      $out['checkbox'] = true;

      if (is_array($data)) {
        foreach ($data as $k => $v) {
          if ($v == 'on') $out['val'][] = base64_decode($k);
        }
      }
    } else {
      // Input match
      preg_match('/^(.+)\[(.+)\]$/', $key, $matches);
      $id = $matches[1];
      $subkey = $matches[2];
      $data = $_POST[$id][$subkey];
      
      $out['id'] = $id;
      $out['label'] = base64_decode($subkey);
      $out['val'] = is_string($data) ? stripslashes($data) : '';
      
    }
    return $out;
  }
  

  function is_valid_email($email) {
  
    // Uses code from http://www.linuxjournal.com/article/9585?page=0,1
  
    $isValid = true;
    $atIndex = strrpos($email, "@");
  
    if (is_bool($atIndex) && !$atIndex) {
    
      $isValid = false;

    } else {
    
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
    
      if ($localLen < 1 || $localLen > 64) {
        // local part length exceeded
        $isValid = false;
      } else if ($domainLen < 1 || $domainLen > 255) {
        // domain part length exceeded
        $isValid = false;
      } else if ($local[0] == '.' || $local[$localLen-1] == '.') {
        // local part starts or ends with '.'
        $isValid = false;
      } else if (preg_match('/\\.\\./', $local)) {
        // local part has two consecutive dots
        $isValid = false;
      } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
        // character not valid in domain part
        $isValid = false;
      } else if (preg_match('/\\.\\./', $domain)) {
        // domain part has two consecutive dots
        $isValid = false;
      } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
        // character not valid in local part unless 
        // local part is quoted
        if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
          $isValid = false;
        }
      }
    
    }

    return $isValid;

  }
  
?>