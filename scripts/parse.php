<?php
/**
 * Dicfro
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2015 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

@list(, $dictionary, $noExitOnError, $verbose, $lineStart, $lineCount) = $argv;

require_once 'common.php';

if (! empty($dictionaryConfig['parser']) and is_string($dictionaryConfig['parser'])) {
    $dictionaryConfig['parser'] = ['class' => $dictionaryConfig['parser']];
}

if (isset($dictionaryConfig['parser']['class'])) {
    // the parser is specified in the dictionary config
    $class = $dictionaryConfig['parser']['class'];
    $file = str_replace('_', '/', $class) . '.php';

} else {
    $sufix = $string->dash2CamelCase($dictionary, true);
    $file = "Model/Parser/$sufix.php";

    if (file_exists("$applicationDir/$file")) {
        // there is a specific parser named after the dictionary
        $class = "Model_Parser_$sufix";

    } else if (isset($dictionaryConfig['type'])) {
        // defaults to the generic parser for this dictionary type
        $class = 'Model_Parser_' . ucfirst($dictionaryConfig['type']);
        $file = str_replace('_', '/', $class) . '.php';

    } else {
        throw new Exception('missing dictionary type');
    }
}

if (! isset($dictionaryConfig['parser']['properties']['dictionary'])) {
    $dictionaryConfig['parser']['properties']['dictionary'] = $dictionary;
}

require $file;

try {
    $parser = new $class($dataDir, $dictionaryConfig['parser']['properties'], $dictionaryConfig, $noExitOnError, $verbose);
    $parser->create($lineStart, $lineCount);
} catch (Exception $e) {
    die($e->getMessage());
}
