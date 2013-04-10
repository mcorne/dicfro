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

require_once 'Model/Parser/GdfLike.php';

/**
 * Parser of the Godefroy lexicon
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Parser_Gdflex extends Model_Parser_GdfLike
{
    public $imageNumberTpl = '0000%s';
    public $lineTpl = '~^(.+?)(?:➣|\[|_]).+?<@__(\d+).tif_>.+$~';
    public $separator = '~';

    public function postParseLine($wordData, $line)
    {
        @list(, $errata) = explode('[Errata]__BR____BR__ ✦', $line);

        if ($errata) {
            $errata = str_replace('__BR__ ✦', '<br />', $errata);
            $errata = str_replace('__BR__', '', $errata);
        } else {
            $errata = '';
        }

        $wordData['errata'] = $errata;

        return $wordData;
    }
}