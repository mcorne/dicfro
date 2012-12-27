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
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'View/Helper/Base.php';

/**
 * Words View Helper
 *
 * @category   DicFro
 * @package    View
 * @subpackage Helper
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class View_Helper_Words extends View_Helper_Base
{

    /**
     * Word forms separator
     */
    const FORM_SEPARATOR = ' *[,;] *';

    /**
     * Lemma and part of speech template
     */
    const LEMMA_POF_TPL = '%s, %s';

    /**
     * Text template
     */
    const TEXT_TPL = '%s : %s';

    /**
     * Returns the lemma and the part of speech of a word
     *
     * @param  array  $word the word details
     * @return string the lemma and the part of speech if any
     */
    public function getLemmaAndPof($word)
    {
        return empty($word['pof'])? $word['lemma'] : sprintf(self::LEMMA_POF_TPL, $word['lemma'], $word['pof']);
    }

    /**
     * Extracts the main forms or variants of a word
     *
     * @param  string $type the type of form, ex. "main"
     * @return array  the word forms
     */
    public function extractForms($type)
    {
        $forms = array();

        foreach($this->view->identifiedWords as $word) {
            empty($word[$type]) or
            $forms = array_merge($forms, $this->getForms($word, $type));
        }

        return $this->sortForms($forms);
    }

    /**
     * Extracts the other forms (not main nor variants) of a word
     *
     * @return array the word forms
     */
    public function extractOtherForms()
    {
        $forms = array();

        foreach($this->view->identifiedWords as $word) {
            empty($word['main']) and empty($word['variants']) and
            $forms = array_merge($forms, $this->getForms($word));
        }

        return $this->sortForms($forms);
    }

    /**
     * Returs the forms of a word
     *
     * @param  array  $word the word details
     * @param  string $type the type of form, ex. "main"
     * @return array  the forms of the word
     */
    public function getForms($word, $type = null)
    {
        $lemmaAndPof = $this->getLemmaAndPof($word);

        // extract the word forms
        $words = $type?
            preg_split(self::FORM_SEPARATOR, $word[$type]) :
            array($word['lemma']);

        $forms = array();

        foreach($words as $form) {
            $forms[] = array(
                'value' => $form,
                'text' => $type?
                    sprintf(self::TEXT_TPL, $form, $lemmaAndPof) :
                    $lemmaAndPof,
            );
        }

        return $forms;
    }

    /**
     * Sorts the forms
     *
     * @param  array $forms the forms to sort
     * @return array the sorted forms
     */
    public function sortForms($forms)
    {
        $sorted = array();

        foreach($forms as $form) {
            list($key) = explode(',', $form['text']);
            $key = $this->string->utf8toASCII($key);
            $sorted[$key] = $form;
        }

        ksort($sorted);

        return array_values($sorted);
    }
}