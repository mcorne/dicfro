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
 * Queries an indexed dictionary database
 *
 * TODO: process volume for all, to use optionally depending if absolute page numbering (readonly volume)
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
        if ($this->useVolume) {
            $result = $this->fetch("SELECT * FROM word ORDER BY volume ASC, page ASC LIMIT 1");

        } else {
            $result = $this->fetch("SELECT * FROM word ORDER BY page ASC LIMIT 1");
        }

        return $result;
    }

    /**
     * Goes to the last page of the dictionary
     *
     * @return array
     */
    public function goToLastPage()
    {
        if ($this->useVolume) {
            $result = $this->fetch("SELECT * FROM word ORDER BY volume DESC, page DESC LIMIT 1");

        } else {
            $result = $this->fetch("SELECT * FROM word ORDER BY page DESC LIMIT 1");
        }

        return $result;
    }

    /**
     * Goes to the next page or the last one if none
     *
     * @param  numeric $page the page from where to go next
     * @param  numeric $page the page from where to go next
     * @return array
     */
    public function goToNextPage($volume, $page)
    {
        if ($this->useVolume) {
            $sql = "SELECT * FROM word WHERE (volume = :volume AND page > :page OR volume > :volume) ORDER BY volume ASC, page ASC LIMIT 1";
            $parameters = [':page' => $page, ':volume' => $volume];

        } else {
            $sql = "SELECT * FROM word WHERE page > :page ORDER BY page ASC LIMIT 1";
            $parameters = [':page' => $page];
        }

        if (! $result = $this->fetch($sql, $parameters)) {
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
    public function goToPage($volume, $page)
    {
        if ($this->useVolume) {
            $sql = "SELECT * FROM word WHERE (volume = :volume AND page >= :page OR volume > :volume) ORDER BY volume ASC, page ASC LIMIT 1";
            $parameters = [':page' => $page, ':volume' => $volume];

        } else {
            $sql = "SELECT * FROM word WHERE page >= :page ORDER BY page ASC LIMIT 1";
            $parameters = [':page' => $page];
        }

        if (! $result = $this->fetch($sql, $parameters)) {
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
    public function goToPreviousPage($volume, $page)
    {
        if ($this->useVolume) {
            $sql = "SELECT * FROM word WHERE (volume = :volume AND page < :page OR volume < :volume) ORDER BY volume DESC, page DESC LIMIT 1";
            $parameters = [':page' => $page, ':volume' => $volume];

        } else {
            $sql = "SELECT * FROM word WHERE page < :page ORDER BY page DESC LIMIT 1";
            $parameters = [':page' => $page];
        }

        if (! $result = $this->fetch($sql, $parameters)) {
            $result = $this->goToFirstPage();
        }

        return $result;
    }

    /**
     * Searches words in same page as a word
     *
     * @param  int $volume
     * @param  int $page
     * @return array
     */
    public function searchCurrentEntries($volume, $page)
    {
        if ($this->useVolume) {
            $sql = "SELECT * FROM word WHERE volume = :volume AND page = :page AND original != '' LIMIT 10";
            $parameters = [':page' => $page, ':volume' => $volume];

        } else {
            $sql = "SELECT * FROM word WHERE page = :page AND original != '' LIMIT 10";
            $parameters = [':page' => $page];
        }

        return $this->fetchAll($sql, $parameters);
    }

    /**
     * Searches words after a word
     *
     * @param  int $volume
     * @param  int $page
     * @return array
     */
    public function searchNextEntries($volume, $page)
    {
        if ($this->useVolume) {
            $sql = "SELECT * FROM word WHERE (volume = :volume AND page > :page OR volume > :volume) AND original != '' ORDER BY volume ASC, page ASC LIMIT 10";
            $parameters = [':page' => $page, ':volume' => $volume];

        } else {
            $sql = "SELECT * FROM word WHERE page > :page AND original != '' ORDER BY page ASC LIMIT 10";
            $parameters = [':page' => $page];
        }

        return $this->fetchAll($sql, $parameters);
    }

    /**
     * Searches words before a word
     *
     * @param  int $volume
     * @param  int $page
     * @return array
     */
    public function searchPreviousEntries($volume, $page)
    {
        if ($this->useVolume) {
            $sql = "SELECT * FROM word WHERE (volume = :volume AND page < :page OR volume < :volume) AND original != '' ORDER BY volume DESC, page DESC LIMIT 10";
            $parameters = [':page' => $page, ':volume' => $volume];

        } else {
            $sql = "SELECT * FROM word WHERE page < :page AND original != '' ORDER BY page DESC LIMIT 10";
            $parameters = [':page' => $page];
        }


        return array_reverse($this->fetchAll($sql, $parameters));
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

        if ($result = $this->fetch($sql, [':ascii' => $ascii])) {
            // found same or next word, searches previous word if different
            // note: need to find the first occurence of the previous word, ex. first page with "A"
            // note: previous is empty if this is the first page
            $result['ascii'] == $ascii or
            empty($result['previous']) or
            $result = $this->fetch($sql, [':ascii' => $result['previous']]);

        } else {
            // no word found,  returns the last one
            $result = $this->goToLastPage();
        }

        return $result;
    }
}
