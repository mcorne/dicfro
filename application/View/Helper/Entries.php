<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    View
 * @subpackage Helper
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'View/Helper/Base.php';

/**
 * Entries View Helper
 *
 * @category   DicFro
 * @package    View
 * @subpackage Helper
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2013 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class View_Helper_Entries extends View_Helper_Base
{
    /**
     * Calculates the hash of an entry
     *
     * @param string $entry
     * @return string
     */
    public function calculate_entry_hash($entry)
    {
        return sprintf('%u', crc32($entry));
    }

    /**
     * Returns the entries for use in a select box
     *
     * @return array
     */
    public function getEntries()
    {
        $options = $this->setOptions($this->view->entries['previous']);

        if (empty($this->view->entries['current'])) {
            $options[] = array('selected' => true, 'text' => '--', 'value' => null);

        } else {
            if (! empty($this->view->entryHash)) {
                // an entry was selected
                $selected = $this->getSelectedHash($this->view->entries['current'], $this->view->entryHash);

            } else if ($this->view->previousAction == 'search') {
                // a word was searched
                $selected = $this->getSelectedWord($this->view->entries['current'], $this->view->word);

            } else {
                $selected = 0;
            }

            $options = $this->setOptions($this->view->entries['current'], $options, $selected);
        }

        $options = $this->setOptions($this->view->entries['next'], $options);

        return $options;
    }

    /**
     * Returns the index of the selected entry ascii-like the the searched word
     *
     * @param array $entries
     * @param string $searchedWord
     * @return int
     */
    public function getSelectedAsciiWord($entries, $searchedWord)
    {
        $string = new Base_String();
        $asciiSearchedWord = $string->utf8toASCII($searchedWord);

        foreach($entries as $index => $entry) {
            if ($entry['ascii'] == $asciiSearchedWord) {
                return $index;
            }
        }

        return 0;
    }

    /**
     * Returns the index of the selected entry same as the searched word
     *
     * @param array $entries
     * @param string $searchedWord
     * @return int
     */
    public function getSelectedExactWord($entries, $searchedWord)
    {
        foreach($entries as $index => $entry) {
            if (mb_strtolower($entry['original'], 'UTF-8') == mb_strtolower($searchedWord, 'UTF-8')) {
                return $index;
            }
        }

        return 0;
    }

    /**
     * Returns the index of the selected entry by its hash
     *
     * @param array $entries
     * @param string $selectedEntryHash
     * @return int
     */
    public function getSelectedHash($entries, $selectedEntryHash)
    {
        foreach($entries as $index => $entry) {
            $entryHash = $this->calculate_entry_hash($entry['original']);

            if ($entryHash == $selectedEntryHash) {
                return $index;
            }
        }

        return 0;
    }

    /**
     * Returns the index of the selected entry whose begining is the same as the begining of the searched word
     *
     * @param array $entries
     * @param string $searchedWord
     * @return int
     */
    public function getSelectedLikeWord($entries, $searchedWord)
    {
        $string = new Base_String();
        $asciiSearchedWord = $string->utf8toASCII($searchedWord);

        foreach($entries as $index => $entry) {
            $length = min(strlen($asciiSearchedWord), strlen($entry['ascii']));

            if (substr($entry['ascii'], 0, $length) == substr($asciiSearchedWord, 0, $length)) {
                return $index;
            }
        }

        return 0;
    }

    /**
     * Returns the index of the selected entry closest to the searched word
     *
     * @param array $entries
     * @param string $searchedWord
     * @return int
     */
    public function getSelectedWord($entries, $searchedWord)
    {
        $selected = $this->getSelectedExactWord($entries, $searchedWord) or
        $selected = $this->getSelectedAsciiWord($entries, $searchedWord) or
        $selected = $this->getSelectedLikeWord($entries, $searchedWord) or
        $selected = 0;

        return $selected;
    }

    /**
     * Sets the option
     *
     * @param array $entry
     * @param int $selected
     * @return array
     */
    public function setOption($entry, $selected = null)
    {
        $entryHash = $this->calculate_entry_hash($entry['original']);

        return array(
            'selected' => $selected,
            'text'     => $entry['original'],
            'value'    => sprintf('%u/%u/%s', $entry['page'], $entry['volume'], $entryHash),
        );
    }

    /**
     * Sets the options
     *
     * @param array $entries
     * @param array $options
     * @param string $selected
     * @return array
     */
    public function setOptions($entries, $options = array(), $selected = null)
    {
        foreach($entries as $index => $entry) {
            $options[] = $this->setOption($entry, $index === $selected);
        }

        return $options;
    }
}