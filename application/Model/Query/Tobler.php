<?php
/**
 * Dicfro
 *
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Model/Query.php';

/**
 * Queries the Tobler database
 */
class Model_Query_Tobler extends Model_Query
{
    public $dictionaryId = 'tobler';

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

        $result = $this->fetchAll($query, [':ascii' => $ascii]);

        return $result;
    }
}
