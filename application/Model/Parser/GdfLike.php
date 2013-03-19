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
 * Parser of the Godefroy like dictionaries
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

abstract class Model_Parser_GdfLike extends Model_Parser
{
    public $lineTpl = '~^(.+?)(?:➣|\[|_]).+?<@_(\d+).tif_>.+$~';

    public $search;
    public $separator = '|';

    public function __construct($config, $verbose = false)
    {
        parent::__construct($config, $verbose);
        $this->createSearchObject();
    }

    public function addMissingImages($wordData)
    {
        static $prevWordData = null;
        static $prevVolume = null;
        static $prevPage = null;

        $missingImages = '';
        list($volume, $page) = $this->search->extractVolumeAndPage($wordData['image']);

        is_null($prevPage) or
        $prevVolume < $volume or
        $prevVolume == $volume and $prevPage < $page or
        $this->error("adding missing image: {$wordData['image']}", true);

        while($prevWordData !== null and $prevVolume == $volume and ++$prevPage != $page) {
            $prevWordData['image'] = $this->search->setImageNumber($volume, $prevPage);
            $missingImages .= $this->setWordData($prevWordData) . "\n";
        }

        $prevVolume = $volume;
        $prevPage = $page;
        $prevWordData = $wordData;

        return $missingImages;
    }

    public function checkNoSeparatorInData($wordData)
    {
        if (strpos(implode('', $wordData), $this->separator) !== false) {
            $this->error("separator used in data: {$wordData['image']}", true);
        }
    }

    public function createSearchObject()
    {
        $dictionaryConfig = $this->config['dictionaries'][$this->dictionary];

        if (isset($dictionaryConfig['search']['class'])) {
            $class = $dictionaryConfig['search']['class'];
        } else {
            $class = 'Model_Search_Generic';
        }

        if (isset($dictionaryConfig['search']['properties'])) {
            $properties = $dictionaryConfig['search']['properties'];
        } else {
            $properties = array();
        }

        $file = str_replace('_', '/', $class) . '.php';
        require_once $file;

        if (isset($dictionaryConfig['query'])) {
            $query = $dictionaryConfig['query'];
        } else {
            $query = array();
        }

        $this->search = new $class($this->config['data-dir'], $properties, $query);
    }

    public function extractWordAndImage($line, $lineNumber)
    {
        if (! preg_match($this->lineTpl, $line, $matches)) {
            $this->error('wrong format: ' . $this->string->utf8ToInternal($line), true, $lineNumber);
        }

        list(, $word, $imageNumber) = $matches;

        return array($word, $imageNumber);
    }

    public function fixImageNumber($imageNumber)
    {
        return $imageNumber;
    }

    public function isEndOfData($line)
    {
        static $endOfData = false;

        if (! $endOfData) {
            // detects the end of valid data, subsequent lines will be ignored
            $endOfData = substr($line, 0, 2) == 'ZZ';
        }

        return $endOfData;
    }

    public function parseLine($line, $lineNumber)
    {
        static $prevLine = null;
        static $prevWord = null;

        // trims the line
        $line = trim($line);

        if ($line == $prevLine or $this->isEndOfData($line) or $this->isLineIgnored($line)) {
            // ignores duplicated lines or comments ...
            return array();
        }
        $prevLine = $line;

        list($word, $imageNumber) = $this->extractWordAndImage($line, $lineNumber);

        // extracts word
        list($word) = explode('➣', $word);
        list($word) = explode('[', $word);
        list($word) = explode('_', $word);
        $original = trim($word);
        $original = $this->string->toUpper($original); // nice to have for Gaffiot
        // removes double entries, ex. "XIRIER,Y"
        list($word) = explode(',', $word);
        // converts to ASCII
        $word = $this->string->utf8toASCIIorDigit($word);

        $imageNumber = $this->fixImageNumber($imageNumber);

        $wordData = array(
            'ascii'    => $word,
            'image'    => $imageNumber,
            'original' => $original,
            'line'     => $lineNumber,
            'previous' => $prevWord,
        );

        $this->validateWordOrder($word, $lineNumber);

        $wordData = $this->postParseLine($wordData, $line);

        $this->checkNoSeparatorInData($wordData);

        $prevWord = $word;

        return array($this->addMissingImages($wordData) . $this->setWordData($wordData));
    }

    public function postParseLine($wordData, $line)
    {
        return $wordData;
    }

    public function setWordData($wordData)
    {
        ksort($wordData);

        return implode($this->separator, $wordData);
    }
}