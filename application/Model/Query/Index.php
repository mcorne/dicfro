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
 * Queries an indexed dictionary database
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Query
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Query_Index extends Model_Query
{
    /**
     * Goes to the first page of the dictionary
     *
     * @return array
     */
    public function goToFirstPage()
    {
        return $this->fetch("SELECT * FROM word ORDER BY page ASC LIMIT 1");
    }

    /**
     * Goes to the last page of the dictionary
     *
     * @return array
     */
    public function goToLastPage()
    {
        return $this->fetch("SELECT * FROM word ORDER BY page DESC LIMIT 1");
    }

    /**
     * Goes to the next page or the last one if none
     *
     * @param  numeric $page the page from where to go next
     * @return array
     */
    public function goToNextPage($page)
    {
        $sql = "SELECT * FROM word WHERE page > :page LIMIT 1";

        if (! $result = $this->fetch($sql, array(':page' => $page))) {
            $result = $this->goToLastPage();
        }

        return $result;
    }

    /**
     * Goes to a given page, the first page if before, or the last page if after
     *
     * @param  numeric $page the page to go to
     * @return array
     */
    public function goToPage($page)
    {
        $sql = "SELECT * FROM word WHERE page >= :page LIMIT 1";

        if (! $result = $this->fetch($sql, array(':page' => $page))) {
            $result = $this->goToLastPage();
        }

        return $result;
    }

    /**
     * Goes to the previous page, or the first page if none
     *
     * @param  numeric $page the page from where to go
     * @return array
     */
    public function goToPreviousPage($page)
    {
        $sql = "SELECT * FROM word WHERE page < :page ORDER BY page DESC LIMIT 1";

        if (! $result = $this->fetch($sql, array(':page' => $page))) {
            $result = $this->goToFirstPage();
        }

        return $result;
    }

    /**
     * Searches a word
     *
     * @param  string $word            the word in UTF-8
     * @return array  the page where the word is or would be located
     */
    public function searchWord($word)
    {
        $ascii = $this->string->utf8toASCII($word);

        $sql = "SELECT * FROM word WHERE ascii >= :ascii LIMIT 1";

        if ($result = $this->fetch($sql, array(':ascii' => $ascii))) {
            // found same or next word, searches previous word if different
            // note: need to find the first occurence of the previous word, ex. first page with "A"
            // note: previous is empty if this is the first page
            $result['ascii'] == $ascii or
            empty($result['previous']) or
            $result = $this->fetch($sql, array(':ascii' => $result['previous']));

        } else {
            // no word found,  returns the last one
            $result = $this->goToLastPage();
        }

        return $result;
    }
}