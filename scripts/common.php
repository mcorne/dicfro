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

$config = require __DIR__ . '/../application/config.php';

$dictionaries = glob("{$config['data-dir']}/*", GLOB_ONLYDIR);
$dictionaries = array_map('basename', $dictionaries);

if (empty($dictionary) or ! in_array($dictionary, $dictionaries)) {
    die('you must choose a valid dictionary among: '. implode(', ', $dictionaries));
}

$dictionaryConfig = isset($config['dictionaries'][$dictionary]) ? $config['dictionaries'][$dictionary] : array();

set_include_path($applicationDir);

require_once 'Base/String.php';
$string = new Base_String;