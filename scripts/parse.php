<?php
/**
 * Dicfro
 *
 * Dictionary parsing
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2012 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

@list(, $dictionary, $verbose, $lineStart, $lineCount) = $argv;

require_once 'common.php';

$dictionary = $string->dash2CamelCase($dictionary, true);
$file = "Model/Parser/$dictionary.php";

if (! file_exists("$applicationDir/$file")) {
    die("invalid file: $file");
}

require $file;

try {
    $class = "Model_Parser_$dictionary";
    $parser = new $class($config, $verbose);
    $parser->create($lineStart, $lineCount);
} catch (Exception $e) {
    die($e->getMessage());
}
