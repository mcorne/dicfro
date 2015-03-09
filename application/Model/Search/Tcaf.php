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
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Model/Query/Tcaf.php';

/**
 * Search the Conjugation tables dictionary
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Search
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Search_Tcaf
{
    /**
     * Construtor
     *
     * @param  string $directory  the dictionaries directory
     * @return void
     */
    public function __construct($directory)
    {
        $this->query = new Model_Query_Tcaf($directory);
    }

    /**
     * Searches a word in the dictionary
     *
     * @param  string $word the word to search
     * @return array  the word details
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