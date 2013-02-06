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
 * Parser of the Whitaker dictionary
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Parser_Whitaker extends Model_Parser
{
    const ending = '\s+\[(\w)(\w)(\w)(\w)(\w)] :: (?:\|*)(.+)';

    const char = '\s+(?=[A-Z])';
    const remainder = '(.+)';

    const auxiliary = '(est|sum)\s+';
    const case1 = '\s+(ABL|ACC|DAT|GEN|LOC|NOM|VOC|X)';
    const case2 = '\((ABL|ACC|DAT|GEN|LOC|NOM|VOC|X)\)\s+';
    const comparison = '\s+(COMP|POS|SUPER|X)';
    const conjugation = '\s+\((1|2|3|4)(?:st|nd|rd|th)\)';
    const declension = '\s+\((1|2|3|4|5)(?:st|nd|rd|th)\)';
    const gender = '\s+(C|F|M|N|X)';
    const mood = '\s+(IMP|IND|INF|PPL|SUB|X)';
    const number = '\s+(P|S|X)';
    const person = '\s+([0-3])';
    const pronoun = '\s+(ADJECT|DEMONS|INDEF|INTERR|PERS|REFLEX|REL|X)';
    const tense = '\s+(FUT|FUTP|IMPF|PERF|PLUP|PRES|X)';
    const variant = '\s+(\d)';
    const verb = '\s+(ABL|DAT|DEP|GEN|IMPERS|INTRANS|PERFDEF|SEMIDEP|TO_BE|TO_BEING|TRANS|X)';
    const voice = '\s+(ACTIVE|PASSIVE|X)';
    const which = '\s+(\d)';

    public $dictionary = 'whitaker';
    public $batchFiles = array('entry.sql', 'word.sql');
    public $dataFiles = array('entry' => 'entry.txt', 'word' => 'word.txt');
    public $sourceFile = 'DICTPAGE.v2.RAW';

    public $emptyEntry;
    public $emptyWords;

    public $abbreviations = array(
        'auxiliary' => array(
            'sum'   => 'sum',
            'est'   => 'est',
            ),

        'case' => array(
            'ABL' => 'abl.',
            'ACC' => 'acc.',
            'DAT' => 'dat.',
            'GEN' => 'gen.',
            'LOC' => 'loc.',
            'NOM' => 'nom.',
            'VOC' => 'voc.',
            'X'   => '', // ignored
            ),

        'case-plus' => array(
            'ABL' => '+abl.',
            'ACC' => '+acc.',
            'DAT' => '+dat.',
            'GEN' => '+gen.',
            'LOC' => '+loc.',
            'NOM' => '+nom.',
            'VOC' => '+voc.',
            'X'   => '', // ignored
            ),

        'comparison' => array(
            'COMP'  => '', // unused
            'POS'   => '', // ignored
            'SUPER' => '', // unused
            'X'     => '', // ignored
            ),

        'conjugation' => array(
            '1' => '(1)',
            '2' => '(2)',
            '3' => '(3)',
            '4' => '(4)',
            ),

        'declension' => array(
            '1' => '(1)',
            '2' => '(2)',
            '3' => '(3)',
            '4' => '(4)',
            '5' => '(5)',
            ),

        'gender' => array(
            'C' => 'm. f.',
            'F' => 'f.',
            'M' => 'm.',
            'N' => 'n.',
            'X' => '', // ignored
            ),

        'mood' => array(
            'IMP' => 'imper.',
            'IND' => 'ind.',
            'INF' => 'inf.',
            'PPL' => '', // unused
            'SUB' => 'subj.',
            'X'   => '', // ignored
            ),

        'number' => array(
            'P' => 'pl.',
            'S' => 'sg.',
            'X' => '', // ignored
            ),

        'person' => array(
            '1' => '1°',
            '2' => '2°',
            '3' => '3°',
            ),

        'pof' => array(
            'ADJ'     => 'adj.',
            'ADV'     => 'adv.',
            'CONJ'    => 'conj.',
            'INTERJ'  => 'interj.',
            'N'       => '', // ignored, using gender is enough
            'NUM'     => 'num.',
            'PACK'    => '', // ignored
            'PREFIX'  => '', // unused
            'PREP'    => 'prep.',
            'PRON'    => 'pron.',
            'SUFFIX ' => '', // unused
            'SUPINE'  => '', // unused
            'TACKON'  => '', // unused
            'V'       => 'v.',
            'VPAR'    => '', // unused
            ),

        'pronoun' => array(
            'ADJECT' => 'adj.', // with PACK only
            'DEMONS' => '', // unused
            'INDEF'  => '', // unused
            'INTERR' => 'interr.', // with PACK only
            'PERS'   => 'pers.',
            'REFLEX' => 'refl.',
            'REL'    => '', // unused
            'X'      => '', // ignored
            ),

        'tense' => array(
            'FUT'  => 'fut.',
            'FUTP' => '', // unused
            'IMPF' => 'impf.',
            'PERF' => '', // unused
            'PLUP' => '', // unused
            'PRES' => 'pres.',
            'X'    => '', // ignored
            ),

        'variant' => '', // ignored
        'verb' => array(
            'ABL'      => '+abl.',
            'DAT'      => '+dat.',
            'DEP'      => 'dep.',
            'GEN'      => '+gen.',
            'IMPERS'   => 'impers.',
            'INTRANS'  => 'intr.',
            'PERFDEF'  => 'pf.',
            'SEMIDEP'  => 's.dep.',
            'TO_BE'    => '', // unused
            'TO_BEING' => '', // unused
            'TRANS'    => 'tr.',
            'X'        => '', // ignored
            ),

        'voice' => array(
            'ACTIVE'  => 'act.',
            'PASSIVE' => 'pass.',
            'X'       => '', // ignored
            ),

        'which' => '', // ignored
        );

    public $entryFields = array(
        // data in the Whitaker dictionary text file (sorted) excluding words
        'case',
        'comparison',
        'conjugation',
        'declension',
        'gender',
        'mood',
        'number',
        'person',
        'pof',
        'pronoun',
        'tense',
        'variant',
        'verb',
        'voice',
        'which',
        // data added to the original data
        'line', // entry dictionary line number
        'auxiliary', // verb auxiliary, e.g. "sum"
        'undeclined', // undeclined word flag
        'abbreviation', // abbreviation word flag
        'info', // information formatted for displaying purposes
        );

    public $endingFields = array(// attention: must remain in this order
        'age',
        'area',
        'geography',
        'frequency',
        'source',
        'definition',
        );

    public $lineFormat;

    public $fieldOrder = array(
        'auxiliary',

        'pof',

        'conjugation',
        'verb',
        'mood',
        'tense',
        'voice',

        'gender',
        'declension',

        'case',

        'person',
        'number',

        'pronoun',
        'case-plus',
        // ignored
        'comparison',
        'variant',
        'which',
        );

    public $detailsFix = array(
        'search' => array(
            ', sum',
            ', est',
            ', (',
            ', +',
            '(GEN)',
            ),
        'replace' => array(
            ' sum',
            ' est',
            ' (',
            ' +',
            '(gen.)',
            ),
        );

    public $string;

    public $transition = array(
        '-' => '',
        '(gen -ius)' => '',
        '(GEN)'      => '',
        '(gen.)'     => '',
        '(i)'        => 'i',
        '/'          => '',
        '/ii'        => 'vi',
        '/is'        => 'os',
        '-a'         => array('us', '-ae'),
        '-ae'        => array('i', 'o'),
        '-e'         => array('a', 'is'),
        '-es'        => 'es',
        '-ia'        => '-es',
        'o'          => '-ae',
        '-or'        => 'or',
        '-um'        => array('a', '-a'),
        '-us'        => '-or',
        );

    public $typo = array(
        'search' => array(
            '-or -u,'   , // #confidentiloquus, confidentiloqua -um, confidentiloquior -or -u, confidentiloquissimus -a  ADJ
            'us -a -u,' , // #undequinquaginta, undequinquagesimus -a -u, undequinquageni -ae -a, undequinquagie(n)s  NUM
            'us -a -u ' , // #adminiculatus, adminiculata -um, adminiculatior -or -us, adminiculatissimus -a -u  ADJ
            'us -a - '  , // #circumscriptus, circumscripta -um, circumscriptior -or -us, circumscriptissimus -a -  ADJ
            'us -a  '   , // #confidentiloquus, confidentiloqua -um, confidentiloquior -or -u, confidentiloquissimus -a  ADJ
            '(ii)'      , // #adeo, adire, adivi(ii), aditus  V
            ),
        'replace' => array(
            '-or -us,',
            'us -a -um,',
            'us -a -um ',
            'us -a -um ',
            'us -a -um  ',
            '/ii',
            ),
        );

    public function __construct($config, $verbose = false)
    {
        $this->setFormat();

        $fields = array_merge($this->entryFields, $this->endingFields);
        sort($fields);
        $this->emptyEntry = array_fill_keys($fields, '');

        foreach($this->transition as &$transition) {
            if (! empty($transition)) {
                settype($transition, 'array');
            }
        }

        parent::__construct($config, $verbose);
    }

    public function calcPofDistrib()
    {
        $pofs = 'N|PRON|PACK|ADJ|NUM|ADV|V|VPAR|SUPINE|PREP|CONJ|INTERJ|TACKON|PREFIX|SUFFIX';
        $distribution = array_fill_keys(explode('|', $pofs), 0);
        $distribution['not-identified'] = 0;
        $pattern = "~^.+?  ($pofs) ~";
        $file = file($this->sourceFile);
        foreach($file as $line) {
            if (preg_match($pattern, $line, $matches)) {
                $distribution[next($matches)]++;
            } else {
                $distribution['not-identified']++;
            }
        }

        arsort($distribution);
        print_r($distribution);
        // results
        /**
        * Array
        * (
        * [N] => 19647
        * [ADJ] => 9062
        * [V] => 7812
        * [ADV] => 2204
        * [NUM] => 127
        * [INTERJ] => 106
        * [CONJ] => 102
        * [PREP] => 93
        * [PRON] => 45
        * [not-identified] => 21
        * [PACK] => 16
        * [SUFFIX] => 0
        * [PREFIX] => 0
        * [VPAR] => 0
        * [SUPINE] => 0
        * [TACKON] => 0
        * )
        */
    }

    public function calcWordDistrib()
    {
        $distribution = array_fill_keys(range(1, 4), 0);
        $distribution['not-identified'] = 0;
        $pattern = "~^#([a-z]+)(?:, (-|[a-z]+))?(?:, (-|[a-z]+))?(?:, (-|[a-z]+))?\s+(?=[A-Z])~i";
        $file = file($this->sourceFile);
        foreach($file as $line) {
            if ($count = preg_match($pattern, $line, $matches)) {
                $distribution[count($matches)-1]++;
            } else {
                $distribution['not-identified']++;
            }
        }

        arsort($distribution);
        print_r($distribution);
        // results
        /**
        * Array
        * (
        * [2] => 18821
        * [3] => 8871
        * [4] => 6867
        * [not-identified] => 2393
        * [1] => 2277
        * )
        */
    }

    public function concatDetails($details, $lineNumber)
    {
        $concatenated = array();

        foreach($details as $field => $value) {
            empty($value) or
            isset($this->abbreviations[$field]) and
                ($this->abbreviations[$field] === '' or isset($this->abbreviations[$field][$value])) or
            $this->error("unknow details: $field => $value", true, $lineNumber);

            if (! empty($this->abbreviations[$field][$value])) {
                $concatenated[$field] = $this->abbreviations[$field][$value];
            }
        }

        $sorted = array();

        foreach($this->fieldOrder as $field) {
            if (isset($concatenated[$field])) {
                $sorted[$field] = $concatenated[$field];
            }
        }

        return implode(', ', $sorted);
    }

    public function expandWord($toInflection, $prevWord, $lineNumber)
    {
        if (! isset($this->transition[$toInflection])) {
            $this->error("unknow transition state: $toInflection", true, $lineNumber);
        }

        if (empty($this->transition[$toInflection])) {
            // no transition but just information: ignored
            return '';
        }

        foreach($this->transition[$toInflection] as $fromInflection) {
            // trims the "from" inflection off non alpha characters
            $fromInflection = trim($fromInflection, '()/-');
            // extracts the previous word inflection
            $length = strlen($fromInflection);
            $prevInflection = substr($prevWord, - $length);

            if ($prevInflection == $fromInflection) {
                // the transition is identified, e.g. os -> /is

                if ($toInflection{0} != '(') {
                    // extracts the stem if applicable
                    $prevWord = substr($prevWord, 0, - $length);
                }

                // adds the inflection trimmed off non alpha characters
                return $prevWord . trim($toInflection, '()/-');
            }
        }

        $this->error("not expecting $prevWord -> $toInflection transition", true, $lineNumber);
    }

    public function extractWord(&$fields, &$matches, $lineNumber)
    {
        // extracts the original mix of words and inflections
        $originalWords = array_shift($matches);
        // extracts words mixed with inflections
        $wordCount = array_shift($fields);
        $mixed = array_splice($matches, 0, $wordCount);

        foreach($mixed as $word) {
            if ($word == '(n)s') {
                // the special case: ie(n)s
                // updates the previous word instance, e.g. quatterdecies
                $words[count($words) - 1] .= 's';
                // sets a new word instance, e.g. quatterdeciens,
                $word = $prevWord . 'ns';
            } else if (in_array($word{0}, array('/', '(', '-'))) {
                // an inflection, expands the word
                $word = $this->expandWord($word, $prevWord, $lineNumber);
                if (empty($word)) {
                    // not an inflection, no expansion
                    continue;
                }
            } // else: a regular word, not an inflection
            $words[] = $prevWord = $word;
        }

        return array($words, $originalWords);
    }

    public function parseLine($line, $lineNumber)
    {
        $line = rtrim($line);
        // correct typos
        $line = str_replace($this->typo['search'], $this->typo['replace'], $line);

        foreach($this->lineFormat as $key => $format) {
            if ($count = preg_match($format['words']['pattern'], $line, $matches)) {
                // the words are a match
                array_shift($matches);
                // extracts the entry details and the words
                $remainder = array_pop($matches);
                $words = $matches;

                foreach($format['details'] as $subFormat) {
                    if (preg_match($subFormat['pattern'], $remainder, $matches)) {
                        // the line is a match
                        array_shift($matches);
                        // resets the entry data
                        $entryData = $this->emptyEntry;
                        // extracts the entry ending
                        $endingValues = array_splice($matches, - count($this->endingFields));
                        $ending = array_combine($this->endingFields, $endingValues);
                        $entryData = array_merge($entryData, $ending);

                        if ($key == 'other') {
                            // extracts the words mixed with inflections if applicable
                            list($words, $original) = $this->extractWord($subFormat['fields'], $matches, $lineNumber);
                        }

                        // extracts the rest of the entry details
                        // note: the last field may have no match if optional
                        if (count($matches) == (count($subFormat['fields']) - 1)) {
                            $matches[] = '';
                        }

                        $fields = array_combine($subFormat['fields'], $matches);
                        $entryData = array_merge($entryData, $fields);
                        // sets undeclined word and abbreviations
                        $entryData = $this->setUndeclined($entryData, $words);

                        // adds the concatenated words to the entry data
                        if ($key == 'other') {
                            $info = $original;
                        } else {
                            $info = implode(', ', $words);
                        }

                        if ($details = $this->concatDetails($fields, $lineNumber)) {
                            $info .= ", $details";
                        }

                        $entryData['info'] = str_replace($this->detailsFix['search'],
                            $this->detailsFix['replace'], $info);
                        // adds the line number to the entry data
                        $entryData['line'] = $lineNumber;

                        if (isset($entryData['case-plus'])) {
                            // changes the "case-plus" field to "case"
                            $entryData['case'] = $entryData['case-plus'];
                        }

                        unset($entryData['case-plus']);

                        return array(
                            'entry' => implode('|', $entryData),
                            'word'  => $this->setWordsData($words, $lineNumber),
                            );
                    }
                }
                // no match
                break;
            }
        }

        $this->error('wrong format', true, $lineNumber);
    }

    public function setFormat()
    {
        $this->lineFormat = array(
            // undeclined words
            // attention: must be tried before "2 words"
            'undeclined' => array(
                'words' => array(
                    'pattern' => '([a-z]+), (undeclined)',
                    ),
                'details' => array(
                    // nouns
                    array(// e.g. #Abba, undeclined  N M
                        'pattern' => '(N)' . self::gender,
                        'fields'  => array('pof', 'gender'),
                        ),
                    // adjectives
                    array(// e.g. #adinstar, undeclined  ADJ
                        'pattern' => '(ADJ)',
                        'fields'  => array('pof'),
                        ),
                    // verbs
                    array(// #effatha, undeclined  V TRANS
                        'pattern' => '(V)' . self::verb . '?',
                        'fields'  => array('pof', 'verb'),
                        ),
                    ),
                ),
            // 2 words
            'wordsX2' => array(
                'words' => array(
                    'pattern' => '([a-z]+), (-|[a-z]+)',
                    ),
                'details' => array(
                    // nouns
                    array(// e.g. #abrotonites, abrotonitae  N M
                        'pattern' => '(N)' . self::gender,
                        'fields'  => array('pof', 'gender'),
                        ),
                    array(// e.g.
                        'pattern' => '(N)' . self::declension . self::gender,
                        'fields'  => array('pof', 'declension', 'gender'),
                        ),
                    // adjectives, averbs
                    array(// e.g. #deterius, deterrime  ADV
                        'pattern' => '(ADJ|ADV)',
                        'fields'  => array('pof'),
                        ),
                    // pronouns
                    array(// e.g. #ego, mei PRON PERS
                        'pattern' => '(PRON)' . self::pronoun,
                        'fields'  => array('pof', 'pronoun'),
                        ),
                    ),
                ),
            // 3 words
            'wordsX3' => array(
                'words' => array(
                    'pattern' => '([a-z]+), (-|[a-z]+), (-|[a-z]+)',
                    ),
                'details' => array(
                    // adjectives, adverbs, pronouns
                    array(// e.g. #abactius, abactia, abactium  ADJ
                        'pattern' => '(ADJ|ADV|PRON)',
                        'fields'  => array('pof'),
                        ),
                    // verbs
                    array(// e.g. #aio, -, -  V
                        'pattern' => '(V)' . self::verb . '?',
                        'fields'  => array('pof', 'verb'),
                        ),
                    array(// e.g. #admoderor, admoderari, -  V (1st) DEP
                        'pattern' => '(V)' . self::conjugation . self::verb,
                        'fields'  => array('pof', 'conjugation', 'verb'),
                        ),
                    array(// e.g. #abolefio, aboleferi, abolefactus sum  V SEMIDEP
                        'pattern' => self::auxiliary . '(V)' . self::verb,
                        'fields'  => array('auxiliary', 'pof', 'verb'),
                        ),
                    array(// e.g. #abominor, abominari, abominatus sum  V (1st) DEP
                        'pattern' => self::auxiliary . '(V)' . self::conjugation . self::verb,
                        'fields'  => array('auxiliary', 'pof', 'conjugation', 'verb'),
                        ),
                    // pack
                    array(// e.g. #quicumque, quaecumque, quodcumque  PACK
                        'pattern' => '(PACK)' . self::pronoun . '?',
                        'fields'  => array('pof', 'pronoun'),
                        ),
                    ),
                ),
            // 4 words
            'wordsX4' => array(
                'words' => array(
                    'pattern' => '([a-z]+), (-|[a-z]+), (-|[a-z]+), (-|[a-z]+)',
                    ),
                'details' => array(
                    // verbs
                    array(// e.g. #abfero, abferre, -, -  V TRANS
                        'pattern' => '(V)' . self::verb . '?',
                        'fields'  => array('pof', 'verb'),
                        ),
                    array(// e.g. #abaestuo, abaestuare, abaestuavi, abaestuatus  V (1st) INTRANS
                        'pattern' => '(V)' . self::conjugation . self::verb . '?',
                        'fields'  => array('pof', 'conjugation', 'verb'),
                        ),
                    array(// e.g. #accidt, accidere, accidit, accisus est  V (3rd) IMPERS
                        'pattern' => self::auxiliary . '(V)' . self::conjugation . self::verb . '?',
                        'fields'  => array('auxiliary', 'pof', 'conjugation', 'verb'),
                        ),
                    array(// e.g. #interest, interesse, interfuit, interfutus est  V IMPERS
                        'pattern' => self::auxiliary . '(V)' . self::verb,
                        'fields'  => array('auxiliary', 'pof', 'verb'),
                        ),
                    ),
                ),
            // 1 word
            'wordsX1' => array(
                'words' => array(
                    'pattern' => '([a-z]+)',
                    ),
                'details' => array(
                    // nouns
                    array(// e.g. #bobus  N  3 1 ABL P C
                        'pattern' => '(N)' . self::which . self::variant . self::case1 . self::number . self::gender,
                        'fields'  => array('pof', 'which', 'variant', 'case', 'number', 'gender'),
                        ),
                    array(// e.g. #multae  N  F
                        'pattern' => '(N)' . self::gender,
                        'fields'  => array('pof', 'gender'),
                        ),
                    // adjectives
                    array(// e.g. #exspes  ADJ 3 1 NOM S X POS
                        'pattern' => '(ADJ)' . self::which . self::variant . self::case1 . self::number . self::gender . self::comparison,
                        'fields'  => array('pof', 'which', 'variant', 'case', 'number', 'gender', 'comparison'),
                        ),
                    array(// 2 instances: #colossicon  N ADJ &  #curotrophoe F ADJ -> ignoring unexpected N|F!
                        'pattern' => '(?:F|N)\s+' . '(ADJ)',
                        'fields'  => array('pof'),
                        ),
                    // verbs
                    array(// e.g. #adesdum           V      5 1 PRES ACTIVE  IMP 2 S
                        'pattern' => '(V)' . self::which . self::variant . self::tense . self::voice . self::mood . self::person . self::number . self::verb . '?',
                        'fields'  => array('pof', 'which', 'variant', 'tense', 'voice', 'mood', 'person', 'number', 'verb'),
                        ),
                    // adverbs, conjunctions, interjections, numerals
                    array(// e.g. #a  INTERJ
                        'pattern' => '(ADV|CONJ|INTERJ|NUM)',
                        'fields'  => array('pof'),
                        ),
                    // prepositions
                    array(// e.g. #ab  PREP  ABL
                        'pattern' => '(PREP)' . self::case1,
                        'fields'  => array('pof', 'case-plus'),
                        ),
                    // pronouns
                    array(// e.g. #chodchod  PRON   1 7 ACC S N
                        'pattern' => '(PRON)' . self::which . self::variant . self::case1 . self::number . self::gender,
                        'fields'  => array('pof', 'which', 'variant', 'case', 'number', 'gender'),
                        ),
                    array(// e.g. #mei (GEN) PRON REFLEX
                        'pattern' => self::case2 . '(PRON)' . self::pronoun,
                        'fields'  => array('case', 'pof', 'pronoun'),
                        ),
                    // pack
                    array(// e.g. #quicum  PACK
                        'pattern' => '(PACK)',
                        'fields'  => array('pof'),
                        ),
                    ),
                ),
            // abreviations
            'abbreviation' => array(
                'words' => array(
                    'pattern' => '([a-z]+)\., (abb.)',
                    ),
                'details' => array(
                    // nouns
                    array(// e.g. #d., abb.  N C
                        'pattern' => '(N)' . self::gender,
                        'fields'  => array('pof', 'gender'),
                        ),
                    // adjectives
                    array(// e.g. #Apr., abb.  ADJ
                        'pattern' => '(ADJ)',
                        'fields'  => array('pof'),
                        ),
                    ),
                ),
            // words mixed with inflections
            'other' => array(
                'words' => array(
                    'pattern' => '',
                    ),
                'details' => array(
                    // nouns
                    array(// e.g. #acalanthis, acalanthidos/is  N F
                        'pattern' => '(([a-z]+), ([a-z]+)(/is))\s+(N)' . self::gender,
                        'fields'  => array(3, 'pof', 'gender'),
                        ),
                    array(// e.g. #absinthium, absinthi(i)  N (2nd) N
                        'pattern' => '(([a-z]+), ([a-z]+)(\(i\)))\s+(N)' . self::declension . self::gender,
                        'fields'  => array(3, 'pof', 'declension', 'gender'),
                        ),
                    // adjective
                    array(// e.g. #caesius, caesia -um, -, caesissumus -a -um  ADJ
                        'pattern' => '(([a-z]+), ([a-z]+) (-um), (-), ([a-z]+) (-a) (-um))\s+(ADJ)',
                        'fields'  => array(7, 'pof'),
                        ),
                    array(// e.g. #abjectus, abjecta -um, abjectior -or -us, abjectissimus -a -um  ADJ
                        'pattern' => '(([a-z]+), ([a-z]+) (-um|-e), ([a-z]+) (-or) (-us), ([a-z]+) (-a) (-um))\s+(ADJ)',
                        'fields'  => array(9, 'pof'),
                        ),
                    array(// e.g. #asper, aspra -um, asprior -or -us, -  ADJ
                        'pattern' => '(([a-z]+), ([a-z]+) (-um), ([a-z]+) (-or) (-us), (-))\s+(ADJ)',
                        'fields'  => array(7, 'pof'),
                        ),
                    array(// e.g. #adspectabilis, adspectabile, adspectabilior -or -us, adspectabilissimus -a -u  ADJ
                        'pattern' => '(([a-z]+), ([a-z]+), ([a-z]+) (-or) (-us), ([a-z]+) (-a) (-um))\s+(ADJ)',
                        'fields'  => array(8, 'pof'),
                        ),
                    array(// e.g. #deterior -or -us, deterrimus -a -um  ADJ
                        'pattern' => '(([a-z]+) (-or) (-us), ([a-z]+) (-a) (-um))\s+(ADJ)',
                        'fields'  => array(6, 'pof'),
                        ),
                    array(// e.g. #absens, (gen.), absentis  ADJ
                        'pattern' => '(([a-z]+), (\(gen\.\)), (-|[a-z]+))\s+(ADJ)',
                        'fields'  => array(3, 'pof'),
                        ),
                    array(// e.g. #abstinens, abstinentis (gen.), abstinentior -or -us, abstinentissimus -a -um  ADJ
                        'pattern' => '(([a-z]+), ([a-z]+) (\(gen\.\)), ([a-z]+) (-or) (-us), ([a-z]+) (-a) (-um))\s+(ADJ)',
                        'fields'  => array(9, 'pof'),
                        ),
                    array(// e.g. #metuens, metuentis (gen.), metuentior -or -us, -  ADJ
                        'pattern' => '(([a-z]+), ([a-z]+) (\(gen\.\)), ([a-z]+) (-or) (-us), (-))\s+(ADJ)',
                        'fields'  => array(7, 'pof'),
                        ),
                    array(// e.g. #par, paris (gen.), -, parissimus -a -um  ADJ
                        'pattern' => '(([a-z]+), ([a-z]+) (\(gen\.\)), (-), ([a-z]+) (-a) (-um))\s+(ADJ)',
                        'fields'  => array(7, 'pof'),
                        ),
                    array(// e.g. #nonnullus, nonnulla, nonnullum (gen -ius)  ADJ
                        'pattern' => '(([a-z]+), ([a-z]+), ([a-z]+) (\(gen -ius\)))\s+(ADJ)',
                        'fields'  => array(4, 'pof'),
                        ),
                    // verbs
                    array(// e.g. #odeo, odire, odivi(ii), -  V TRANS
                        'pattern' => '(([a-z]+), ([a-z]+), ([a-z]+)(/ii), (-|[a-z]+))\s+(V)' . self::verb . '?',
                        'fields'  => array(5, 'pof', 'verb'),
                        ),
                    // pronouns
                    array(// e.g. #mei (GEN) PRON REFLEX
                        'pattern' => '(([a-z]+) (\(GEN\)))\s+(PRON)' . self::pronoun,
                        'fields'  => array(2, 'pof', 'pronoun'),
                        ),
                    array(// e.g. #nos, nostrum/nostri PRON PERS
                        'pattern' => '(([a-z]+), ([a-z]+)(/)([a-z]+))\s+(PRON)' . self::pronoun,
                        'fields'  => array(4, 'pof', 'pronoun'),
                        ),
                    // numerals
                    array(// e.g. #quattuordecim, -, -, quatterdecie(n)s  NUM
                        'pattern' => '(([a-z]+), (-), (-), ([a-z]+)(\(n\)s))\s+(NUM)',
                        'fields'  => array(6, 'pof'),
                        ),
                    array(// e.g. #quattuor, quartus -a -um, quaterni -ae -a, -  NUM
                        'pattern' => '(([a-z]+), ([a-z]+) (-a) (-um), ([a-z]+) (-ae) (-a), (-))\s+(NUM)',
                        'fields'  => array(8, 'pof'),
                        ),
                    array(// e.g. #biscentum, biscentesimus -a -um, biscenteni -ae -a, biscentie(n)s  NUM
                        'pattern' => '(([a-z]+), ([a-z]+) (-a) (-um), ([a-z]+) (-ae) (-a), ([a-z]+)(\(n\)s))\s+(NUM)',
                        'fields'  => array(10, 'pof'),
                        ),
                    array(// e.g. #ducenti -ae -a, ducentesimus -a -um, duceni -ae -a, ducentie(n)s  NUM
                        'pattern' => '(([a-z]+) (-ae) (-a), ([a-z]+) (-a) (-um), ([a-z]+) (-ae) (-a), ([a-z]+)(\(n\)s))\s+(NUM)',
                        'fields'  => array(12, 'pof'),
                        ),
                    array(// e.g. #duo -ae o, secundus -a -um, bini -ae -a, bis  NUM
                        'pattern' => '(([a-z]+) (-ae|-es) (o), ([a-z]+) (-a) (-um), ([a-z]+) (-ae) (-a), ([a-z]+))\s+(NUM)',
                        'fields'  => array(10, 'pof'),
                        ),
                    array(// e.g. #tres -es -ia, tertius -a -um, terni -ae -a, ter  NUM
                        'pattern' => '(([a-z]+) (-es|-a) (-ia|-um), ([a-z]+) (-a) (-um), ([a-z]+) (-ae) (-a), ([a-z]+))\s+(NUM)',
                        'fields'  => array(10, 'pof'),
                        ),
                    ),
                ),
            );

        foreach($this->lineFormat as &$format) {
            if ($format['words']['pattern']) {
                $format['words']['pattern'] .= self::char;
            }

            $format['words']['pattern'] = '~^#' . $format['words']['pattern'] . self::remainder . '$~i';

            foreach($format['details'] as &$subFormat) {
                $subFormat['pattern'] = '~^' . $subFormat['pattern'] . self::ending . '$~i';
            }
        }
    }

    public function setWordsData($words, $lineNumber)
    {
        foreach($words as $word) {
            if ($word != '-') {
                $data = array(
                    'original' => $word,
                    'latin'    => $this->string->toLatin($word),
                    'upper'    => $this->string->toUpper($word),
                    'line'     => $lineNumber,
                    );

                ksort($data);
                $wordsData[] = implode('|', $data);
            }
        }

        return implode("\n", $wordsData);
    }

    public function setUndeclined($entryData, &$words)
    {
        $entryData['undeclined'] = 0;
        $entryData['abbreviation'] = 0;

        if (count($words) == 2) {
            // a 2 word entry, captures if "undeclined" or an "abbreviation"
            if ($words[1] == 'undeclined') {
                $entryData['undeclined'] = 1;
                unset($words[1]);
            } else if ($words[1] == 'abb.') {
                $entryData['abbreviation'] = 1;
                unset($words[1]);
            }
        }

        return $entryData;
    }
}