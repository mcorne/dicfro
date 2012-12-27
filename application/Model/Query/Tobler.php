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
 * Queries the Tobler database
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Query
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Query_Tobler extends Model_Query 
{
    /**
     * Constructor
     *
     * @param  string $directory the dictionaries directory
     * @return void  
     */
    public function __construct($directory = '.')
    {
        parent::__construct($directory . '/tobler');
    }

    /**
     * Searches a word form
     *
     * @param  string $word the word form to search
     * @return array  the list of words matching that form
     */
    public function searchWords($word)
    {
        $ascii = $this->string->utf8toASCII($word);

        $query = "SELECT DISTINCT main, variants, pof, lemma FROM word, entry ";
        $query .= "WHERE ascii = :ascii AND entry.line = word.line ";
        $query .= "ORDER BY main, variants, lemma, pof";

        $result = $this->execute($query, array(':ascii' => $ascii));

        return $result;
    }
}