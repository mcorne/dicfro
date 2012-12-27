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
 * Parser of the Van Daele dictionary
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Parser_Vandaele extends Model_Parser_GdfLike
{
    public $lineTpl = '~^__BR__Image=><@_VanDaele(\d+)\.TIF_>VanDaele__BR____BR__(.+?) +\[~';
    public $ignoredLineTpl = '~^__BR__Image=><@_VanDaele(\d+)\.TIF_>VanDaele__BR__$~';

    public $dictionary = 'vandaele';
    public $sourceFile = 'Txt.v3.corrected';

    public function extractWordAndImage($line, $lineNumber)
    {
        preg_match($this->lineTpl, $line, $matches) or
        $this->error('wrong format: ' . $this->string->utf8ToInternal($line), true, $lineNumber);
        list(, $imageNumber, $word) = $matches;

        return array($word, $imageNumber);
    }

    public function fixImageNumber($imageNumber)
    {
        return "0000$imageNumber";
    }

    public function isLineIgnored($line)
    {
        return preg_match($this->ignoredLineTpl, $line);
    }
}