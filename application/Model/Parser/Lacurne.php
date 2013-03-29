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

require_once 'Model/Parser/GaffiotLike.php';

/**
 * Parser of the Lacurne dictionary
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Parser_Lacurne extends Model_Parser_GaffiotLike
{
    public $lineTpl = '~^(.+?)__BR____BR__image=><@_(\d\d-\d\d\d).tiff_>LacEdic__BR____BR__~';
    public $endWord = 'zzz';

    public $dictionary = 'lacurne';
    public $sourceFile = 'txt';

    public function addMissingImages($wordData, $noCheckOrder = false)
    {
        // cannot add missing images as they are not ordered, assuming there is none missing
        return '';
    }

    public function fixImageNumber($imageNumber)
    {
        list($volume, $page) = explode('-', $imageNumber);

        return $volume . '00' . $page;
    }

    public function preProcessing($lines)
    {
        $sorted = array();

        foreach($lines as $line) {
            // extracts first word of a line
            list($word) = explode('__', $line);
            $word = $this->string->utf8toASCIIorDigit($word);
            $sorted[$word] = $line;
        }

        // note that txt file has been somewhat reordered but not consistently
        // and that the dictionary is itself not sorted consistently
        // sorts line according to ascii which should be acceptable to search words in most cases
        ksort($sorted);

        return array_values($sorted);
    }
}