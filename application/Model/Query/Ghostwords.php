<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Query
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Model/Query.php';

/**
 * Queries the ghostwords database
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Query
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Query_Ghostwords extends Model_Query
{
    /**
     * Constructor
     *
     * @param  string $directory the dictionaries directory
     * @return void
     */
    public function __construct($directory = '.')
    {
        parent::__construct($directory . '/ghostwords');
    }

    /**
     * Search words between two words or from a word
     *
     * @param  string $wordFrom the word to search from including this word
     * @param  string $wordTo   the word to search to excluding this word or null
     * @return string the list of words between those two words
     */
    public function searchWords($wordFrom, $wordTo)
    {
        if ($wordTo) {
            return $this->searchWordsBetween($wordFrom, $wordTo);
        } else {
            return $this->searchWordsFrom($wordFrom);
        }
    }

    /**
     * Search words between two words
     *
     * @param  string $wordFrom the word to search from including this word
     * @param  string $wordTo   the word to search to excluding this word
     * @return string the list of words between those two words
     */
    public function searchWordsBetween($wordFrom, $wordTo)
    {
        $wordFrom = $this->string->utf8toASCII($wordFrom);
        $wordTo = $this->string->utf8toASCII($wordTo);

        $sql = "SELECT original FROM word WHERE ascii >= :wordFrom AND ascii < :wordTo";

        return $this->execute($sql, array(':wordFrom' => $wordFrom, ':wordTo' => $wordTo), PDO::FETCH_COLUMN);
    }

    /**
     * Search words from a word
     *
     * @param  string $wordFrom the word to search from including this word
     * @return string the list of words between those two words
     */
    public function searchWordsFrom($wordFrom)
    {
        $wordFrom = $this->string->utf8toASCII($wordFrom);

        $sql = "SELECT original FROM word WHERE ascii >= :wordFrom ";

        return $this->execute($sql, array(':wordFrom' => $wordFrom), PDO::FETCH_COLUMN);
    }
}