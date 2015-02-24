<?php

  if (!defined('ABSPATH')) die();

  global $der_framework;

  define('TWITTER_CACHE_EXPIRATION', 5*60); // NOTE: php's time() returns seconds
  define('TWITTER_SECURITY_MAX_USER_ENTRIES', 20);
  define('TWITTER_SECURITY_MAX_COUNT_ENTRIES', 20);

  header('Content-Type: application/json;charset=utf-8');

  if (!THEME_RELEASE) exit(@file_get_contents($der_framework->path('data/testdata/twitter-data.json')));

  $out = $nodata = json_encode(array());
  
  $username = (isset($_GET['username'])) ? $_GET['username'] : null;
  
  if ($username && preg_match('/^[@]?[a-zA-Z0-9_]+$/', $username)) {
    
    $updated = false;

    $count = (isset($_GET['count']) && preg_match('/^[0-9]+$/', $_GET['count'])) ? (int) $_GET['count'] : 3;
    
    $twitter_cache = $der_framework->get_option('twitter_cache');

    $twitter_cache = (is_array($twitter_cache)) ? $twitter_cache : array();

    $now = time();

    foreach ($twitter_cache as $account => $data) {
      foreach ($data as $len => $ob) {
        if ($now >= $ob['expires']) {
          unset($twitter_cache[$account][$len]);
          $updated = true;
        }
      }
    }
    
    if (isset($twitter_cache[$username]) && isset($twitter_cache[$username][$count])) {
      
      // Cached version available
      
      $out = $twitter_cache[$username][$count]['data'];
      
    } else {

      // Cache data unavailable or expired

      $auth = array();
      $data = $der_framework->options(array('twitter_consumer_key', 'twitter_consumer_secret', 'twitter_access_token', 'twitter_access_token_secret'));

      foreach ($data as $key => $val) {
        if ($val) {
          $auth[strtoupper(preg_replace('/^twitter_/', '', $key))] = $val;
        }
      }

      if (count(array_keys($auth)) === 4) {
        
        if (count(array_keys($twitter_cache)) > TWITTER_SECURITY_MAX_USER_ENTRIES || (is_array($twitter_cache[$username]) && count(array_keys($twitter_cache[$username])) > TWITTER_SECURITY_MAX_COUNT_ENTRIES)) {
          
          // Do not honor request if limits reached. This prevents malicious users from exploiting the cache and filling up the database.
          // The cached data will automatically be removed on the next request after the expiration time has reached.
          exit($nodata); 

        }
        
        if (!class_exists('TwitterOauth')) require('twitteroauth/twitteroauth.php');
        
        $connection = new TwitterOAuth($auth['CONSUMER_KEY'], $auth['CONSUMER_SECRET'], $auth['ACCESS_TOKEN'], $auth['ACCESS_TOKEN_SECRET']);
        
        $result = $connection->get("statuses/user_timeline", array(
          'screen_name' => $username,
          'count' => $count
        ));

        if ($result) {
          
          $twitter_cache[$username][$count] = array('expires' => $now + TWITTER_CACHE_EXPIRATION);
        
          $out = $twitter_cache[$username][$count]['data'] = json_encode($result);
          
          $updated = true;
          
        }

      }
      
    }
    
    if ($updated) $der_framework->update_option('twitter_cache', $twitter_cache);
    
  }
  
  exit($out);

?>