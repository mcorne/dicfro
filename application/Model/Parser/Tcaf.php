<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Model/Parser.php';

/**
 * Parser of the Conjugation Tables
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Parser_Tcaf extends Model_Parser
{
    const LINE_TPL = '~^(.+?) +\[(inf.|ind.prés.|subj.prés.|impé.|ind.impf.|part.prés.|futur|cond.|pass.simp.|pass.arch.|subj.impf.|part.pass.|comp.)\]__BR____BR__ +(.+?)__BR__$~';
    const FORM_SEPARATOR = '__BR__   ';
    const FORM_TPL = '~^(\d): (.+)$~';
    const VERB_SEPARATOR = ' *[,;] *';

    public $dictionary = 'tcaf';
    public $batchFiles = array('entry.sql', 'word.sql');
    public $dataFiles = array('entry' => 'entry.txt', 'word' => 'word.txt');
    public $sourceFile = 'Txt.v3';

    public function parseForm($form)
    {
        if (preg_match(self::FORM_TPL, $form, $match)) {
            list(, $person, $verbs) = $match;
        } else {
            $person = '';
            $verbs = $form;
        }

        return array($person, $this->parseVerbs($verbs));
    }

    public function parseLine($line, $lineNumber)
    {
        // trims the line
        $line = trim($line);

        if (!$line) {
            // ignores empty lines or comments
            return array();
        }

        if (! preg_match(self::LINE_TPL, $line, $matches)) {
            // extracts word
            $this->error('wrong format: ' . $this->string->utf8ToInternal($line), true, $lineNumber);
        }

        list(, $infinitive, $tense, $conjugation) = $matches;

        return array(
            'entry' => $this->setEntryData($infinitive, $tense, $conjugation, $lineNumber),
            'word'  => $this->setWordsData($infinitive, $tense, $conjugation, $lineNumber),
        );
    }

    public function parseVerbs($verbs)
    {
        // removes variants data
        $verbs = str_replace('(', ',', $verbs);
        $verbs = str_replace('VAR.', '', $verbs);
        $verbs = str_replace(')', '', $verbs);

        $verbs = preg_split(self::VERB_SEPARATOR, $verbs);

        return $verbs;
    }

    public function setInfinitiveAscii($infinitive)
    {
        // removes extra-data from verb, ex. "FÖIR (fouir)"
        list($ascii) = explode(' ', $infinitive);

        return $this->string->utf8toASCII($ascii);
    }

    public function setEntryData($infinitive, $tense, $forms, $lineNumber)
    {
        $entryData = array(
            'ascii'       => $this->setInfinitiveAscii($infinitive),
            'original'    => $infinitive,
            'tense'       => $tense,
            'conjugation' => str_replace('__BR__   ', '<br />', $forms),
            'line'        => $lineNumber,
            );

        ksort($entryData);

        return implode('|', $entryData);
    }

    public function setWordData($infinitive, $tense, $form, $lineNumber)
    {
        list($person, $verbs) = $form;

        $wordsData = array();

        foreach($verbs as $verb) {
            $wordData = array(
                'ascii'            => $this->string->utf8toASCII($verb),
                'original'         => $verb,
                'person'           => $person,
                'infinitive'       => $infinitive,
                'infinitive_ascii' => $this->setInfinitiveAscii($infinitive),
                'tense'            => $tense,
                'line'             => $lineNumber,
                'composed'         => (int)($tense == 'comp.' and !strpos($verb, '*')),
            );

            ksort($wordData);

            $wordsData[] = implode('|', $wordData);
        }

        return $wordsData;
    }

    public function setWordsData($infinitive, $tense, $conjugation, $lineNumber)
    {
        $forms = explode(self::FORM_SEPARATOR, $conjugation);
        $forms = array_map(array($this, 'parseForm'), $forms);

        $wordsData = array();

        foreach($forms as $form) {
            $wordsData = array_merge($wordsData, $this->setWordData($infinitive, $tense, $form, $lineNumber));
        }

        return implode("\n", $wordsData);
    }
}