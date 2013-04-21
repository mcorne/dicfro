<?php
/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Language
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

/**
 * Language handling
 *
 * @category   DicFro
 * @package    Model
 * @subpackage language
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Language
{
    public $languages = array(
        'en'  => array('english' => 'English'    , 'original' => 'English'),
        'fr'  => array('english' => 'French'     , 'original' => 'Français'),
        'fro' => array('english' => 'Old French' , 'original' => 'Français ancien', 'no-ui' => true),
        'la'  => array('english' => 'Latin'      , 'original' => 'Latin'          , 'no-ui' => true),
    );

    /**
     * Detects the user language
     *
     * @return string the language, ex. "en", "fr", "zh-CHT"
     */
    public function detectLanguage()
    {
        global $_root_directory;

        $browser_languages = $this->getBrowserLanguage();
        $browser_languages = array_map(array($this, 'fixLanguageCode'), $browser_languages);
        $possible_languages = array_intersect($browser_languages, array_keys($this->languages));
        // selects the first possible language
        $language = current($possible_languages);

        if (empty($language)) {
            // browser languages are not available, defauts to english
            $language = 'en';
        }

        return $language;
    }

    /**
     * Fixes the language code to comply with the available language codes
     *
     * @param  string $language_code the language code, ex. "en", "fr-FR", "zh-TW"
     * @return string the fixed language code, ex. "en", "fr", "zh-CHT"
     */
    public function fixLanguageCode($language_code)
    {
        @list($language, $region) = explode('-', $language_code);
        // converts language to lower case
        $language = strtolower($language);
        // converts region to upper case
        $region = strtoupper($region);

        // fixes language with a region
        if ($language == 'zh') {
            // this is a chinese language
            if ($region == 'TW' or $region == 'CHT') {
                // this is traditional chinese
                $language = 'zh-CHT';
            } else {
                // defaults to simplified chinese
                $language = 'zh-CHS';
            }
        }
        // else: the region is ignored from the language code by default

        return $language;
    }

    /**
     * Returns the browser accepted languages
     *
     * @return array the list of accepted languages and their quality
     *               ex: array("fr" => 1, "de" => 0.8)
     */
    public function getBrowserLanguage()
    {
        $languages = array();

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            // there are accepted languages, ex: "hu, en-us;q=0.66, en;q=0.33", "hu,en-us;q=0.5"
            $accepted_languages = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);

            foreach($accepted_languages as $accepted_language) {
                if (preg_match('~([a-z-]+)(?:;q=([0-9\\.]+))?~i', $accepted_language, $match)) {
                    $language = $match[1];
                    // defaults quality to 1
                    $quality = isset($match[2]) ? (float) $match[2] : 1.0;
                    $languages[$language] = $quality;
                }
            }
        }

        arsort($languages, SORT_NUMERIC);

        return array_keys($languages);
    }
}