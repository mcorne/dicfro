<?php
/**
 * Dicfro
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2015 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

// exit('Dicfro is down for maintenance - Dicfro est en cours de maintenance');

// IMPORTANT! keep in sync with .htaccess dictionary rewrite rules
if (getenv('ENVIRONMENT') == 'production') {
    $applicationPath = '/../../cgi-bin/dicfro';
    $dictionaryDir = __DIR__ . '/dictionary/%s';
    $domainSubpath = 'dicfro';
} else {
    $applicationPath = '/../application';
    $dictionaryDir = __DIR__ . '/../../dicfro-dictionary/%s/public';
    $domainSubpath = 'dicfro';
}

$applicationPath = realpath(__DIR__ . $applicationPath);
set_include_path($applicationPath);

require_once 'Base/Application.php';
$application = new Base_Application();
$application->run($dictionaryDir, $domainSubpath);
