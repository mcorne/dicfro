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

require_once 'Model/Parser/GdfLike.php';

/**
 * Parser of a Gaffiot like dictionary
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

abstract class Model_Parser_GaffiotLike extends Model_Parser_GdfLike
{
    public $endWord = 'zz';

    public function fixImageNumber($imageNumber)
    {
        return "000$imageNumber";
    }

    public function isEndOfData($line)
    {
        static $endOfData = false;

        if (! $endOfData) {
            // detects the end of valid data, subsequent lines will be ignored
            $endOfData = substr($line, 0, strlen($this->endWord)) == $this->endWord;
        }

        return $endOfData;
    }

    public function isLineIgnored($line)
    {
        static $parsedImage = array();

        $ignore = false;

        if (preg_match($this->lineTpl, $line, $match)) {
            $ignore = isset($parsedImage[$match[2]]);
            $parsedImage[$match[2]] = true;
        }

        return $ignore;
    }
}