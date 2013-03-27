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
 * Parser of the Lexique Roman dictionary
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Parser_Lexromv extends Model_Parser_GaffiotLike
{
    public $lineTpl        = '~^(.+?)__BR____BR__image=><@_tx(\d+)\.tif_>LexRomEdic__BR____BR__~';
    public $ignoredlineTpl = '~^\d=\d+__BR____BR__image=><@_\d-\d+\.tif_>LexRomEdic__BR____BR__~';

    public $dictionary = 'lexromv';
    public $sourceFile = 'txt';

    public function fixImageNumber($imageNumber)
    {
        return "0000$imageNumber";
    }

    public function isLineIgnored($line)
    {
        return (preg_match($this->ignoredlineTpl, $line) or parent::isLineIgnored($line));
    }
}