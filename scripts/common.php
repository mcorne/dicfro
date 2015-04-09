<?php
/**
 * Dicfro
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2015 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

$dataDir = realpath(__DIR__ . "/../application/data");
$dictionaries = glob("$dataDir/*", GLOB_ONLYDIR);
$dictionaries = array_map('basename', $dictionaries);

if (empty($dictionary) or ! in_array($dictionary, $dictionaries)) {
    die('you must choose a valid dictionary among: '. implode(', ', $dictionaries));
}

$applicationDir = __DIR__ . '/../application';
$config = require "$applicationDir/config.php";
$dictionaryConfig = isset($config['dictionaries'][$dictionary]) ? $config['dictionaries'][$dictionary] : [];

set_include_path($applicationDir);

require_once 'Base/String.php';
$string = new Base_String;