<?php
/**
 * Dicfro
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

// exit('Dicfro is down for maintenance - Dicfro est en cours de maintenance');

$_time = microtime(true);

if (getenv('ENVIRONMENT') == 'production') {
    $applicationPath = '/../../cgi-bin/dicfro';
} else {
    $applicationPath = '/../application';
}

$applicationPath = realpath(__DIR__ . $applicationPath);
set_include_path($applicationPath);

require_once 'Base/Application.php';
$application = new Base_Application($applicationPath . '/config.php');
$application->run();