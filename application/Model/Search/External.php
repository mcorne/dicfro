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
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Model/Search.php';

/**
 * Search an external dictionary
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Search
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Search_External extends Model_Search
{
    /**
     * Searches a word in the dictionary
     *
     * @param  string $word the word to search
     * @return array  the word details
     */
    public function searchWord($word)
    {
        if (isset($this->convert)) {
            require_once 'Base/String.php';
            $string = new Base_String;
            $word = $string->{$this->convert}($word);
        }

        if (empty($word) and ! empty($this->emptyWord)) {
            $url = $this->emptyWord;
        } else {
            $url = $this->url . $word;

            if (isset($this->suffix)) {
                $url .= $this->suffix;
            }
        }

        return ['externalDict' => $url];
    }
}