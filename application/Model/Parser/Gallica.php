<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Model/Parser.php';

/**
 * Parser of the Gallica like dictionaries
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

abstract class Model_Parser_Gallica extends Model_Parser
{
    public $batchFileTemplate = 'index.sql';
    public $sourceFile        = 'index.csv';

    public function parseLine($line, $lineNumber)
    {
        static $prevWord = null;

        if ($lineNumber == 1) {
            // ignores headers
            return array();
        }

        $line = trim($line);
        $cells = explode("\t", $line);
        $cells = array_map('trim', $cells);
        @list($page, $entries, $imageNumber, $path, $volume, $debug) = $cells; // TODO: remove debug

        $entries = explode(';', $entries);
        $entries = array_map('trim', $entries);

        foreach($entries as $entry) {
            if (empty($entry)) {
                $word = $prevWord;

            } else {
               $word = $entry;
               $word = preg_replace('~\[[^()[\]]*\]~', '', $word);
               $word = preg_replace('~\([^()[\]]*\)~', '', $word);

               if (preg_match('~[()[\]]~', $word)) {
                   $this->error("parenthesis or bracket mismatch in: $word", true, $lineNumber);
               }

               $word = str_replace('-', '', $word);
               $word = $this->string->utf8toASCIIorDigit($word);

               $this->validateWordOrder($word, $lineNumber);
            }

            $wordData = array(
                'ascii'    => $word,
                'image'    => sprintf('%s/f%s.image', $path, $imageNumber),
                'line'     => $lineNumber,
                'original' => str_replace(array('(', '[', ']'), '', $entry),
                'page'     => $page,
                'previous' => $prevWord,
                'volume'   => $volume,
            );

            ksort($wordData);
            $wordsData[] = implode('|', $wordData);

            $prevWord = $word;
        }

        return array(implode("\n", $wordsData));
    }
}