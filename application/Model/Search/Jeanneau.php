<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Search
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Base/String.php';
require_once 'Model/Search/External.php';

/**
 * Search the Jeanneau dictionary
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Search
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Search_Jeanneau extends Model_Search_External
{
    /**
     * Template of a page URL
     */
    const URL_MULTI_PAGE = 'Dico-%s%02s.html#%s';
    const URL_SINGLE_PAGE = 'Dico-%s.htm#%s';

    /**
     * Mapping of the first letters of the top word of the pages
     * @var array
     */
    public $map = array(
        'a' => array('a', 'ac', 'ad', 'ado', 'ael', 'al', 'am', 'an', 'ap', 'ar', 'as', 'at'),
        'b' => array('b'),
        'c' => array('ca', 'can', 'cas', 'ce', 'ch', 'cis', 'cle', 'cog', 'com', 'cona', 'cong', 'cont', 'cr', 'cuj'),
        'd' => array('da', 'ded', 'der', 'di', 'dir', 'dit', 'duo'),
        'e' => array('e', 'eg', 'em', 'es', 'exb', 'exo', 'ext'),
        'f' => array('fa', 'fe', 'fi', 'fl', 'fr', 'fu'),
        'g' => array('g', 'glau'),
        'h' => array('h'),
        'i' => array('ia', 'imi', 'imu', 'ind', 'infi', 'inn', 'inst', 'inter', 'intes', 'invi'),
        'j' => array('j'),
        'k' => array('k'),
        'l' => array('la', 'lau', 'lep', 'lob', 'luc'),
        'm' => array('ma', 'mam', 'me', 'mi', 'mo', 'mu'),
        'n' => array('na', 'ne', 'neo', 'ni', 'no', 'nu'),
        'o' => array('o', 'obn', 'obum', 'olo', 'or'),
        'p' => array('pa', 'pas', 'pen', 'perd', 'perm', 'pert', 'pie', 'po', 'pos', 'praeh', 'praet', 'pro', 'pron', 'prov'),
        'q' => array('qua', 'que', 'qui', 'quo'),
        'r' => array('ra', 're', 'rel', 'ret', 'ri'),
        's' => array('sa', 'sc', 'se', 'si', 'so', 'sp', 'st', 'sua', 'suc', 'suo'),
        't' => array('ta', 'te', 'th', 'tom', 'tran', 'tro'),
        'u' => array('u', 'un', 'us', 'uti'),
        'v' => array('va', 've', 'ves', 'vig', 'vo'),
        'x' => array('x'),
        'y' => array('x'),
        'z' => array('z'),
    );

    /**
     * Searches a word
     *
     * @param  string $word the word to search
     * @return array  the word details
     */
    public function searchWord($word)
    {
        $string = new Base_String;

        $word = $string->utf8toASCII($word);
        $word = strtolower($word);

        // defaults empty word to "a"
        if ($word) {
            $firstLetter = $word[0];
        } else {
            $firstLetter = 'a';
        }

        if (isset($this->map[$firstLetter])) {
            // this is a valid letter
            $map = array_values($this->map[$firstLetter]);
            $map = array_reverse($map, true);

            foreach($map as $number => $letters) {
                if ($word >= $letters) {
                    // this is the page where the word is (possibly) located
                    break;
                }
            }

            if (count($map) == 1) {
                $location = sprintf(self::URL_SINGLE_PAGE, $firstLetter, $word);
            } else {
                $location = sprintf(self::URL_MULTI_PAGE, $firstLetter, ++$number, $word);
            }

        } else {
            // this is an invalid letter, ex. "y"
            $location = sprintf(self::URL_MULTI_PAGE, 'a', 1, '');
        }

        return parent::searchWord($location);
    }
}