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
 * Queries the Whitaker database
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Query
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Query_Whitaker extends Model_Query
{
    /**
     * Constructor
     *
     * @param  string $directory the dictionaries directory
     * @return void
     */
    public function __construct($directory = '.')
    {
        parent::__construct($directory . '/whitaker');
    }

    /**
     * Searches a word form
     *
     * @param  string $word the word form to search
     * @return array  the list of words matching that form
     */
    public function searchWords($word)
    {
        $string = new Base_String;
        $latin = $string->toLatin($word);

        $query = "SELECT DISTINCT info, frequency FROM word, entry ";
        $query .= "WHERE latin = :latin AND entry.line = word.line ";
        $query .= "ORDER BY frequency, info";

        $result = $this->execute($query, array(':latin' => $latin), PDO::FETCH_COLUMN | PDO::FETCH_UNIQUE);

        return array_keys($result);
    }
}