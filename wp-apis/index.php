<?php
ini_set("log_errors", 1);
error_log("\n\n [". date('Y-m-d H:i:s') ."] Start APIs:", 3, 'debug.log');
ini_set( 'error_log', 'debug.log' );

require_once 'bootstrap' . DIRECTORY_SEPARATOR . '__autoload.php';

api_dispatcher::get_instance()->dispath();
