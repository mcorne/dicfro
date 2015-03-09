<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category  DicFro
 * @package   Base
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2010 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

/**
 * Converts strings
 *
 * @category  DicFro
 * @package   Base
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2010 Michel Corne
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Base_String {
    /**
     * Mapping of accentuated letters to ASCII letters
     *
     * @var array
     */
    public $accentuated = [
        // accentuated letters
        'search' => [
            'Á', 'À', 'Â', 'Ä', 'Ą', 'Ç', 'Ć', 'É', 'È', 'Ê', 'Ë', 'Í', 'Ì', 'Î', 'Ï', 'Ī', 'Ł', 'Ñ', 'Ó', 'Ò', 'Ô', 'Ö', 'Ú', 'Ù', 'Û', 'Ü', 'Ÿ',
            // Latin diacritic vowels (AEIOU): A with macron, A with breve, etc...
            'Ā', 'Ă', 'Ē', 'Ĕ', 'Ī', 'Ĭ', 'Ł', 'Ō', 'Ŏ', 'Ū', 'Ŭ', 'Ś', 'Ţ', 'Z̧'
        ],
        // corresponding ASCII letters
        'replace' => [
            'A', 'A', 'A', 'A', 'A', 'C', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'I', 'L', 'N', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y',
            'A', 'A', 'E', 'E', 'I', 'I', 'L', 'O', 'O', 'U', 'U', 'S', 'T', 'Z'
        ],
    ];

    /**
     * Mapping of non ASCII letters to DOS character map letters
     *
     * @var array
     */
    public $utf8ToCP850 = [// http://en.wikipedia.org/wiki/Code_page_850
        'search' => [
            'Ç', 'ü', 'é', 'â', 'ä', 'à', 'å', 'ç', 'ê', 'ë', 'è', 'ï', 'î', 'ì', 'Ä', 'Å',
            'É', 'æ', 'Æ', 'ô', 'ö', 'ò', 'û', 'ù', 'ÿ', 'Ö', 'Ü', 'ø', '£', 'Ø', '×', 'ƒ',
            'á', 'í', 'ó', 'ú', 'ñ', 'Ñ', 'ª', 'º', '¿', '®', '¬', '½', '¼', '¡', '«', '»',
            '' , '' , '' , '' , '' , 'Á', 'Â', 'À', '©', '' , '' , '' , '' , '¢', '¥', '' ,
            '' , '' , '' , '' , '' , '' , 'ã', 'Ã', '' , '' , '' , '' , '' , '' , '' , '¤',
            'ð', 'Ð', 'Ê', 'Ë', 'È', '' , 'Í', 'Î', 'Ï', '' , '' , '' , '' , '' , 'Ì', '' ,
            'Ó', 'ß', 'Ô', 'Ò', 'õ', 'Õ', 'µ', 'þ', 'Þ', 'Ú', 'Û', 'Ù', 'ý', 'Ý', '¯', '´',
            '' , '±', '' , '¾', '¶', '§', '÷', '¸', '°', '¨', '·', '¹', '³', '²', '' , '' ,
            // others
            '’',
        ],

        'replace' => [
            "\x80", "\x81", "\x82", "\x83", "\x84", "\x85", "\x86", "\x87", "\x88", "\x89", "\x8A", "\x8B", "\x8C", "\x8D", "\x8E", "\x8F",
            "\x90", "\x91", "\x92", "\x93", "\x94", "\x95", "\x96", "\x97", "\x99", "\x99", "\x9A", "\x9B", "\x9C", "\x9D", "\x9E", "\x9F",
            "\xA0", "\xA1", "\xA2", "\xA3", "\xA4", "\xA5", "\xA6", "\xA7", "\xAA", "\xA9", "\xAA", "\xAB", "\xAC", "\xAD", "\xAE", "\xAF",
            "\xB0", "\xB1", "\xB2", "\xB3", "\xB4", "\xB5", "\xB6", "\xB7", "\xBB", "\xB9", "\xBA", "\xBB", "\xBC", "\xBD", "\xBE", "\xBF",
            "\xC0", "\xC1", "\xC2", "\xC3", "\xC4", "\xC5", "\xC6", "\xC7", "\xCC", "\xC9", "\xCA", "\xCB", "\xCC", "\xCD", "\xCE", "\xCF",
            "\xD0", "\xD1", "\xD2", "\xD3", "\xD4", "\xD5", "\xD6", "\xD7", "\xDD", "\xD9", "\xDA", "\xDB", "\xDC", "\xDD", "\xDE", "\xDF",
            "\xE0", "\xE1", "\xE2", "\xE3", "\xE4", "\xE5", "\xE6", "\xE7", "\xEE", "\xE9", "\xEA", "\xEB", "\xEC", "\xED", "\xEE", "\xEF",
            "\xF0", "\xF1", "\xF2", "\xF3", "\xF4", "\xF5", "\xF6", "\xF7", "\xFF", "\xF9", "\xFA", "\xFB", "\xFC", "\xFD", "\xFE", "\xFF",
            // others
            "'",
        ],
    ];

    public static $self;

    /**
     * The constructor
     *
     * @return void
     */
    public function __construct()
    {
        // sets the regex encoding to UTF-8
        mb_regex_encoding('UTF-8');
    }

    public static function __callStatic($name, $arguments)
    {
        if (! isset(self::$self)) {
            self::$self = new self;
        }

        $name = substr($name, 1);

        return call_user_func_array([self::$self, $name], $arguments);
    }

    /**
     * Converts a string with dash separated words to camel case
     *
     * @param  string $string the string with dash separated words
     * @return string the camel case string
     */
    public function dash2CamelCase($string, $ucFirst = false)
    {
        $string = str_replace('-', ' ', $string);
        $string = ucwords($string);

        if ($string != '' and ! $ucFirst) {
            $string = strtolower($string[0]) . substr($string, 1);
        }

        return str_replace(' ', '', $string);
    }

    /**
     * Expands the ligatures in a string
     *
     * @param  string $string the string
     * @return string the expanded string
     */
    public function expandLigature($string)
    {
        $string = mb_ereg_replace('Æ', 'AE', $string);
        $string = mb_ereg_replace('Œ', 'OE', $string);

        return $string;
    }

    /**
     * Converts a string from the internal encoding to UTF-8
     *
     * @param  string $string           the string to convert
     * @param  string $internalEncoding the internal encoding
     * @return string the converted string
     */
    public function internalToUtf8($string, $internalEncoding = null)
    {
        if (empty($internalEncoding)) {
            // gets the internal encoding for console displaying purposes
            $internalEncoding = mb_internal_encoding();
        }

        return mb_convert_encoding($string, 'UTF-8', $internalEncoding);
    }

    /**
     * Determines if the process is running as DOS shell
     *
     * @param  string $os   the name of the OS
     * @param  string $sapi the name of the interface
     * @return true   if DOS shell, false otherwise
     */
    public function isDos($os = PHP_OS, $sapi = PHP_SAPI)
    {
        return (stripos($os, 'win') !== false and stripos($sapi, 'cli') !== false);
    }

    /**
     * Removes accents from a string
     *
     * @param  string $string the string to process
     * @return string the string without accents
     */
    public function removeAccents($string)
    {
        return str_replace($this->accentuated['search'], $this->accentuated['replace'], $string);
    }

    /**
     * Converts a string to latin characters
     *
     * @param  string $string the string to process
     * @return string the converted string
     */
    public function toLatin($string)
    {
        // converts to upper case, expands ligatures, removes accents
        $string = $this->utf8toASCII($string);
        $string = strtr($string, 'JU', 'IV');

        return $string;
    }

    /**
     * Converts a string to upper case letters
     *
     * @param  string $string the string to convert
     * @return string the converted string
     */
    public function toUpper($string)
    {
        return mb_convert_case($string, MB_CASE_UPPER, 'UTF-8');
    }

    /**
     * Converts a string from UTF-8 to ASCII uppercase
     *
     * @param  string $string the string to convert
     * @param  string $remove the characters to remove in a regex
     * @return string the converted string
     */
    public function utf8toASCII($string, $remove = '~[^A-Z]~')
    {
        // converts to upper case, expands ligatures, removes accents
        $string = $this->toUpper($string);
        $string = $this->expandLigature($string);
        $string = $this->removeAccents($string);
        // removes dashes, apostrophes ...
        $string = preg_replace($remove, '', $string);

        return $string;
    }

    /**
     * Converts a string from UTF-8 to ASCII or digits
     *
     * @param  string $string the string to convert
     * @return string the converted string
     */
    public function utf8toASCIIorDigit($string)
    {
        // converts to upper case etc... and keeps dashes as in "car-", stars as in "prendre*",
        // and digits except 1 as in "sol3" (see vandaele)
        return $this->utf8toASCII($string, '~[^A-Z2-9\-\*]~');
    }

    /**
     * Converts a string or array of strings from UTF-8 to the internal encoding
     *
     * @param  mixed $mixed the string or array of strings to convert
     * @return mixed the converted string or array of strings
     */
    public function utf8ToInternal($mixed)
    {
        if (is_array($mixed)) {
            $mixed = array_map([$this, __FUNCTION__], $mixed);
        } else {
            $mixed = $this->utf8ToInternalString($mixed);
        }

        return $mixed;
    }

    /**
     * Converts a string from UTF-8 to the internal encoding
     *
     * @param  string  $string           the string to convert
     * @param  string  $internalEncoding the internal encoding
     * @param  boolean $isDos            null for auto-detect, true for DOS shell, false otherwise
     * @return string  the converted string
     */
    public function utf8ToInternalString($string, $internalEncoding = null, $isDos = null)
    {
        if (is_null($isDos)) {
            $isDos = $this->isDos();
        }

        if ($isDos) {
            // the process is running as DOS shell, converts with CP850 charset
            $string = str_replace($this->utf8ToCP850['search'], $this->utf8ToCP850['replace'], $string);

        } else {
            if (empty($internalEncoding)) {
                // gets the internal encoding for console displaying purposes
                $internalEncoding = mb_internal_encoding();
            }

            // converts to the internal encoding
            $string = mb_convert_encoding($string, $internalEncoding, 'UTF-8');
        }

        return $string;
    }
}