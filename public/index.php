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

// sets application path
$applicationPath = strpos($_SERVER['REQUEST_URI'], '/dicfro') === 0? '/../../cgi-bin/dicfro' : '/../application'; // dicfro domain subpath autodetection, default is none
// $applicationPath = '/../application';                                                                          // installation with no domain subpath, eg local installation
// $applicationPath = '/../../cgi-bin/xyz';                                                                       // installation with "xyz" domain subpath

$applicationPath = realpath(dirname(__FILE__) . $applicationPath);
set_include_path($applicationPath);

require_once 'Base/Application.php';
$application = new Base_Application($applicationPath . '/config.php');
$application->run();