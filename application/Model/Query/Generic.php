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
 * Queries a generic dictionary database
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Query
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Query_Generic extends Model_Query
{

    /**
     * List of additional columns to select, format: ", <column-name>, <column-name>, ..."
     * @var string
     */
    public $extraColumns = '';

    /**
     * Goes to the first page of the dictionary
     *
     * @return array the first two rows
     */
    public function goToFirstPage()
    {
        return $this->execute("SELECT ascii, image, original {$this->extraColumns} FROM word ORDER BY image ASC LIMIT 2");
    }

    /**
     * Goes to the last page of the dictionary
     *
     * @return array the last row
     */
    public function goToLastPage()
    {
        return $this->execute("SELECT ascii, image, original {$this->extraColumns} FROM word ORDER BY image DESC LIMIT 1");
    }

    /**
     * Goes to the next page
     *
     * @param  numeric $imageNumber the page from where to go next
     * @return array   the next two rows, or goes to the last row if none
     */
    public function goToNextPage($imageNumber)
    {
        $sql = "SELECT ascii, image, original {$this->extraColumns} FROM word WHERE image > :image LIMIT 2";

        $result = $this->execute($sql, array(':image' => $imageNumber)) or
        $result = $this->goToLastPage();

        return $result;
    }

    /**
     * Goes to a given page
     *
     * @param  numeric $imageNumber the page to go to
     * @return array   the given page row and the next,
     *                 or goes to the first page if before, or to the last page if after
     */
    public function goToPage($imageNumber)
    {
        $sql = "SELECT ascii, image, original {$this->extraColumns} FROM word WHERE image >= :image LIMIT 2";

        $result = $this->execute($sql, array(':image' => $imageNumber)) or
        $result = $this->goToLastPage();

        return $result;
    }

    /**
     * Goes to the previous page
     *
     * @param  numeric $imageNumber the page from where to go
     * @return array   the previous row and the current row, or goes to the first page
     */
    public function goToPreviousPage($imageNumber)
    {
        $sql = "SELECT ascii, image, original {$this->extraColumns} FROM word WHERE image <= :image ORDER BY image DESC LIMIT 2";

        $result = array_reverse($this->execute($sql, array(':image' => $imageNumber))) and
        count($result) >= 2 or
        $result = $this->goToFirstPage();

        return $result;
    }

    /**
     * Searches a word
     *
     * @param  string $word the word in UTF-8
     * @return array  two rows including the page where the word is or would be located
     */
    public function searchWord($word)
    {
        $ascii = $this->string->utf8toASCII($word);

        $sql = "SELECT ascii, image, original, previous {$this->extraColumns} FROM word WHERE ascii >= :ascii LIMIT 2";

        if ($result = $this->execute($sql, array(':ascii' => $ascii))) {
            // found same or next word, searches previous word if different
            // note: need to find the first occurence of the previous word, ex. first page with "A"
            // note: previous is empty if this is the first page
            $result[0]['ascii'] == $ascii or empty($result[0]['previous']) or
            $result = $this->execute($sql, array(':ascii' => $result[0]['previous']));

        } else {
            // no word found,  returns the last one
            $result = $this->goToLastPage();
        }

        return $result;
    }
}