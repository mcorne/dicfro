<?php
/**
 * Dicfro
 *
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Model/Search.php';

/**
 * Searches the conjugation tables dictionary
 */
class Model_Search_Tcaf extends Model_Search
{
    public $queryClass = 'Model_Query_Tcaf';

    /**
     * @param string $word
     * @return array
     */
    public function searchWord($word)
    {
        if (! $conjugation = $this->query->searchVerbConjugation($word)) {
            $conjugation = $this->query->searchModelConjugation($word);
        }

        return [
            'tcaf'          => $conjugation,
            'composedVerbs' => $this->query->searchComposedVerbs($word),
        ];
    }
}
