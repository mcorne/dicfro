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
 * Parser of the ghostword database
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Parser_Ghostwords extends Model_Parser
{
    const LINE_TPL = '~value="([^"]+)"~';

    public $dictionary = 'ghostwords';
    public $sourceFile = 'fantomes.txt';

    public function duplicateWord($word)
    {
        // ex. duplicates "DEF(F)ROQUER" into "DEFROQUER" and "DEFFROQUER"
        return [preg_replace('~\([^)]+\)~', '', $word), preg_replace('~[()]~', '', $word)];
    }

    public function duplicateWords($words)
    {
        $duplicated = [];

        foreach($words as $word) {
            $duplicated = array_merge($duplicated, $this->duplicateWord($word));
        }

        return $duplicated;
    }

    public function parseLine($line, $lineNumber)
    {
        // trims the line
        $line = trim($line);

        if (!$line or substr($line, 0, 2) == '//') {
            // ignores empty lines or comments
            return [];
        }

        if (! preg_match(self::LINE_TPL, $line, $matches)) {
            // extracts word
            $this->error('wrong format: ' . $this->string->utf8ToInternal($line), true, $lineNumber);
        }

        list(, $word) = $matches;
        $original = $word = trim($word);

        list($word) = explode(' (', $word); // removes parenthesis, ex. "ACHAUPER (sei)", "ENSERVE (l')"
        $word = preg_replace('~\d+~', '', $word); // removes digits, ex. "ACONQUESTER1"
        $word = preg_replace('~(UN|UNE|LE|LA|LES|DE|DES|L\') ~', '', $word); // removes articles, ex. "APPELLÉ DE MALADIE"
        $words = preg_split('~[ ,]+~', $word); // splits words
        $words = $this->duplicateWords($words); // duplicates words, ex. splits "DEF(F)ROQUER"
        $words = array_map([$this->string, 'utf8toASCII'], $words); // converts to upper case ASCII
        sort($words);
        $words = array_unique($words);

        $wordsData = [];

        foreach($words as $word) {
            $wordData = [
                'ascii'    => $word,
                'line'     => $lineNumber,
                'original' => $original,
            ];

            $wordsData[] = implode('|', $wordData);
        }

        return [implode("\n", $wordsData)];
    }
}