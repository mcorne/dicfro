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
 * Verbs View Helper
 *
 * @category   DicFro
 * @package    View
 * @subpackage Helper
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class View_Helper_Verbs extends View_Helper_Base
{

    /**
     * Verb form template
     */
    const FORM_TPL = '%s %s, %s';

    /**
     * Infinitive template
     */
    const INFINITIVE_TPL = '%s, %s';

    /**
     * List of persons
     * @var array
     */
    public $person = array(
        '1:' => '(je)',
        '2:' => '(tu)',
        '3:' => '(il)',
        '4:' => '(nous)',
        '5:' => '(vous)',
        '6:' => '(ils)',
    );

    /**
     *
     * Person numbers
     * @var array
     */
    public $personNumbers;

    /**
     *
     * Person texts
     * @var array
     */
    public $personTexts;

    /**
     * List of tenses
     * @var array
     */
    public $tenses = array(
        'inf.' => 'Infinitif',
        'ind.prés.' => 'Indicatif Présent',
        'ind.impf.' => 'Indicatif Imparfait',
        'pass.simp.' => 'Indicatif Passé simple',
        'pass.arch.' => 'Indicatif Passé archaïque',
        'futur' => 'Indicatif Futur',
        'subj.prés.' => 'Subjonctif Présent',
        'subj.impf.' => 'Subjonctif Imparfait',
        'cond.' => 'Conditionnel',
        'impé.' => 'Impératif',
        'part.prés.' => 'Participe Présent',
        'part.pass.' => 'Participe Passé',
    );

    /**
     * Cleans with original verb
     *
     * @param  string $original the orginal verb
     * @return string the clean verb
     */
    public function cleanOriginalVerb($original)
    {
        return str_replace('*', '', $original);
    }

    /**
     * Converts the person
     *
     * @param  string $person the person to convert, ex. "1:"
     * @return string the converted person, ex. "(je)"
     */
    public function convertPersons($person)
    {
        $person .= ':';

        return isset($this->person[$person])? $this->person[$person] : "(?)";
    }

    /**
     * Converts the tense
     *
     * @param  string $tense the tense to convert, ex. "inf."
     * @return string the converted tense, ex. "infinitive"
     */
    public function convertTense($tense)
    {
        return isset($this->tenses[$tense])? $this->tenses[$tense] : $tense . ' (?)';
    }

    /**
     * Extracts the composed or similar (model) verbs
     *
     * @param  boolean $isComposed extracts composed verbs if true, similar verbs if false
     * @return array   the list of verbs by model
     */
    public function extractComposedVerbs($isComposed)
    {
        $this->view->composedVerbs or $this->view->composedVerbs = array();
        $verbs = array();

        foreach($this->view->composedVerbs as $verb) {
            if ($verb['composed'] == $isComposed) {
                $original = $this->cleanOriginalVerb($verb['original']);
                $verbs[$verb['infinitive']][] = array('value' => $original, 'text' => $original);
            }
        }

        return $verbs;
    }

    /**
     * Extracts identified verbs
     *
     * @return array the identified verbs
     */
    public function extractIdentifiedVerbs()
    {
        $this->view->identifiedVerbs or $this->view->identifiedVerbs = array();
        $verbs = array();

        foreach($this->view->identifiedVerbs as $verb) {
            $verbs[$verb['infinitive']][] = array(
                'value' => $verb['infinitive'],
                'text' => $this->formatVerbForm($verb),
            );
        }

        return $verbs;
    }

    /**
     * Extracts infinitives
     *
     * @param  array  $verbs the list of infinitives
     * @return string the list of infinitives
     */
    public function extractInfinitives($verbs)
    {
        return implode(', ', array_keys($verbs));
    }

    /**
     * Formats the verb form
     *
     * @param  array $verb the verb details
     * @return mixed the formatted verb
     */
    public function formatVerbForm($verb)
    {
        return $verb['person']?
            sprintf(self::FORM_TPL, $this->convertPersons($verb['person']), $verb['original'], $verb['tense']) :
            sprintf(self::INFINITIVE_TPL, $verb['original'], $verb['tense']);
    }

    /**
     * Returns the conjugation tables
     *
     * @return array the conjugation tables
     */
    public function getConjugationTables()
    {
        $verbs = array();

        foreach($this->view->tcaf as $verb) {
            // extracts homonyms, ex "NOIIER (necare)" and "NOIIER (negare)"
            $homonym = $verb['original'];
            isset($verbs[$homonym]) or $verbs[$homonym] = $this->presetTenses();

            $verbs[$homonym][$verb['tense']] = array(
                'tense' => $this->convertTense($verb['tense']),
                'conjugation' => $this->replacePersons($verb['conjugation']),
            );
        }

        return array_map('array_filter', $verbs);
    }

    /**
     * Helper initialization
     */
    public function init()
    {
        $this->personNumbers = array_keys($this->person);
        $this->personTexts = array_values($this->person);
    }

    /**
     * Presets tenses
     *
     * @return array the tenses as keys with emtpy values
     */
    public function presetTenses()
    {
        return array_fill_keys(array_keys($this->tenses), null);
    }

    /**
     * Replaces person information
     *
     * @param  array $conjugation the conjugation table
     * @return array the conjugation table
     */
    public function replacePersons($conjugation)
    {
        return str_replace($this->personNumbers, $this->personTexts, $conjugation);
    }
}