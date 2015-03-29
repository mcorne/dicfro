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
 * Queries the Tcaf database
 */
class Model_Query_Tcaf extends Model_Query
{
    public $dictionaryId = 'tcaf';

    /**
     * Searches the conjugation tables of a verb
     *
     * @param  string $word the verb to search for in any of its forms
     * @return array  the conjugation tables
     */
    public function searchVerbConjugation($word)
    {
        $ascii = $this->string->utf8toASCII($word);

        $query = "SELECT tense, conjugation, original FROM entry ";
        $query .= "WHERE ascii IN (SELECT DISTINCT infinitive_ascii FROM word WHERE ascii = :ascii AND tense <> 'comp.') ";
        $query .= "AND tense <> 'comp.' ";
        $query .= "ORDER BY ascii";

        $result = $this->fetchAll($query, [':ascii' => $ascii]);

        return $result;
    }

    /**
     * Searches the conjugation models of a verb
     *
     * @param  string $word the verb to search for in any of its forms
     * @return array  the conjugation tables of model verbs
     */
    public function searchModelConjugation($word)
    {
        $ascii = $this->string->utf8toASCII($word);

        $query = "SELECT tense, conjugation, original FROM entry ";
        $query .= "WHERE ascii IN (SELECT DISTINCT infinitive_ascii FROM word WHERE ascii = :ascii AND tense = 'comp.') ";
        $query .= "AND tense <> 'comp.' ";
        $query .= "ORDER BY ascii";

        $result = $this->fetchAll($query, [':ascii' => $ascii]);

        return $result;
    }

    /**
     * Searches the verbs composed on a verb
     *
     * @param  string $word the verb to search for in any of its forms
     * @return array  the list of composed verbs
     */
    public function searchComposedVerbs($word)
    {
        $ascii = $this->string->utf8toASCII($word);

        $query = "SELECT DISTINCT original, infinitive, composed FROM word ";
        $query .= "WHERE infinitive_ascii IN (SELECT DISTINCT infinitive_ascii FROM word WHERE ascii = :ascii AND tense <> 'comp.') ";
        $query .= "AND tense = 'comp.' ";
        $query .= "ORDER BY ascii";

        $result = $this->fetchAll($query, [':ascii' => $ascii]);

        return $result;
    }

    /**
     * Searches the forms of any verb same as a given form
     *
     * @param  string $word the form to search for
     * @return array  the list forms
     */
    public function searchVerbs($word)
    {
        $ascii = $this->string->utf8toASCII($word);

        $query = "SELECT original, infinitive, tense, person FROM word ";
        $query .= "WHERE ascii = :ascii AND tense <> 'comp.' ";
        $query .= "ORDER BY ascii";

        $result = $this->fetchAll($query, [':ascii' => $ascii]);

        return $result;
    }
}
