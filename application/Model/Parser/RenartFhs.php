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
 * Parser of the Glossary of the Roman de Renart by FHS
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Parser_RenartFhs extends Model_Parser_GdfLike
{
    public $lineTpl = '~^(.+?);(\d+)~';

    public $dictionary = 'renart-fhs';
    public $sourceFile = 'Txt.v1.csv';

    public function fixImageNumber($imageNumber)
    {
        return "0000$imageNumber";
    }
}