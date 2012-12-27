<?php
/**
 * Dicfro
 *
 * Dictionary search
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2012 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

@list(, $dictionary, $method, $param1, $param2) = $argv;

require_once 'common.php';

if (! isset($config['dictionaries'][$dictionary])) {
    die('you may not search a dictionary with no config');
}

if (empty($dictionaryConfig['internal'])) {
    die('you may only search an internal dictionary');
}

$class = isset($dictionaryConfig['search']['class'])? $dictionaryConfig['search']['class'] : 'Model_Search_Generic';
$file = str_replace('_', '/', $class) . '.php';
require_once $file;

$properties = isset($dictionaryConfig['search']['properties'])? $dictionaryConfig['search']['properties'] : array();
$properties['dictionary'] = $dictionary;
$query = isset($dictionaryConfig['query'])? $dictionaryConfig['query'] : array();
$search = new $class($config['data-dir'], $properties, $query);

// extracts "search" and "go to page" like methods
$methods = get_class_methods($search);
$methods = preg_grep('~^(search|goTo).+$~', $methods);

if (! in_array($method, $methods)) {
    die('you must choose a valid method among: ' . implode(', ', $methods));
}

$param1 = $string->internalToUtf8($param1);
$param2 = $string->internalToUtf8($param2);
$result = $search->$method($param1, $param2);
$result = $string->utf8ToInternal($result);

print_r($result);