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
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Model/Parser.php';

/**
 * Parser of the indexed dictionaries
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Parser_Index extends Model_Parser
{
    public $batchFileTemplate = 'index.sql';
    public $wordSeparator     = null;
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
        @list($page, $entries, $imageNumber, $volume, $fix) = $cells;

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

                if ($this->entryReplacements) {
                    $word = preg_replace($this->entryReplacements['pattern'], $this->entryReplacements['replacement'], $word);
                }

                if ($this->wordSeparator) {
                    list($word) = preg_split($this->wordSeparator, $word, 2);
                }

                $word = str_replace('-', '', $word);
                $word = $this->string->utf8toASCIIorDigit($word);

                $this->validateWordOrder($word, $lineNumber);
            }

            $wordData = array(
                'ascii'    => $word,
                'fix'      => $fix,
                'image'    => $imageNumber,
                'line'     => $lineNumber,
                'original' => str_replace(array('[', ']'), '', $entry),
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