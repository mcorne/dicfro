<?php
/**
 * Dicfro
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2012 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

if ($domainSubpath = getenv('DOMAIN_SUBPATH')) {
    $applicationPath = "/../../$domainSubpath/application";
} else {
    $applicationPath = "/../application";
}

$applicationPath = realpath(dirname(__FILE__) . $applicationPath);
set_include_path($applicationPath);

require_once 'Base/Application.php';
$application = new Base_Application($applicationPath . '/config.php');
$application->run();