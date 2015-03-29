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
 * Searches an external dictionary
 */
class Model_Search_External extends Model_Search
{
    /**
     * @param string $word
     * @return array
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
