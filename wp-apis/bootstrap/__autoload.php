<?php
/**
 * Coding by Dang Chi Thao - 2014.10.09
 * 
 * Autoload app
 */

spl_autoload_register('load_app');

function load_app($class) {
    $directories = array(
        __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'classes',
        __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap',
        __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'apis'
    );
    
    foreach ($directories as $directory) {
        if (load_class($directory, $class)) {
            break;
        }
    }
}

/*
 * Load a class in a directory
 */
function load_class($directory, $class) {
    if (!is_dir($directory)) {
        return false;
    }
    
    //Load class in current directory
    $file_name = $directory . DIRECTORY_SEPARATOR . $class . '.php';
    if (is_readable($file_name)) {
        require_once $file_name;
        return true;
    }

    //If class could not be found in current directory, then load in sub directories
    $sub_directories = array_diff(scandir($directory), array('..', '.'));
    if (empty($sub_directories)) {
        return false;
    }
    
    foreach($sub_directories as $sub_directory) {
        if (load_class($directory . DIRECTORY_SEPARATOR . $sub_directory, $class)) {
            return true;
        }
    }
    return false;
}