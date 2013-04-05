<?php
/**
 * Dicfro
 *
 * Dictionary database query
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2012 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

@list(, $dictionary, $method, $param1, $param2) = $argv;

require_once 'common.php';

$directory = $config['data-dir'];

if (isset($dictionaryConfig['query']['class'])) {
    // the class name is specified for this dictionary in the config file
    $class = $dictionaryConfig['query']['class'];

} else {
    $class = 'Model_Query_' . $string->dash2CamelCase($dictionary, true);
    $file = $applicationDir . '/' . str_replace('_', '/', $class) . '.php';

    if (! file_exists($file)) {
        // there is no specific class for this dictionary
        $directory .= '/' . $dictionary;

        if ($dictionaryConfig['type'] == 'internal') {
            $class = 'Model_Query_Internal';
        } else if ($dictionaryConfig['type'] == 'index') {
            $class = 'Model_Query_Index';
        } else {
            die('missing query class');
        }
    }
}

$file = str_replace('_', '/', $class) . '.php';
require_once $file;

if (isset($dictionaryConfig['query']['properties'])) {
    $properties = $dictionaryConfig['query']['properties'];
} else {
    $properties = array();
}

$query = new $class($directory, $properties);

// extracts "search" and "go to page" like methods
$methods = get_class_methods($query);
$methods = preg_grep('~^(search|goTo).+$~', $methods);

if (! in_array($method, $methods)) {
    die('you must choose a valid method among: ' . implode(', ', $methods));
}

$param1 = $string->internalToUtf8($param1);
$param2 = $string->internalToUtf8($param2);
$result = $query->$method($param1, $param2);
$result = $string->utf8ToInternal($result);

print_r($result);