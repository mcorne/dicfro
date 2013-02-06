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
        if (empty($word['pof'])) {
            return $word['lemma'];
        } else {
            return sprintf(self::LEMMA_POF_TPL, $word['lemma'], $word['pof']);
        }
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
            if (! empty($word[$type])) {
                $forms = array_merge($forms, $this->getForms($word, $type));
            }
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
            if (empty($word['main']) and empty($word['variants'])) {
                $forms = array_merge($forms, $this->getForms($word));
            }
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
        if ($type) {
            $words = preg_split(self::FORM_SEPARATOR, $word[$type]);
        } else {
            $words = array($word['lemma']);
        }

        $forms = array();

        foreach($words as $form) {
            if ($type) {
                $text = sprintf(self::TEXT_TPL, $form, $lemmaAndPof);
            } else {
                $text = $lemmaAndPof;
            }

            $forms[] = array(
                'value' => $form,
                'text'  => $text,
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