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
 * Parser of the Tobler-Lommatzsch lemma list
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Parser_Tobler extends Model_Parser
{
    const variant = '~([-\pL ()\'!]+)\d?~u'; // excluding number, e.g. a1
    const word = '([-\pL ()\'!,]*)\d?';

    public $dictionary = 'tobler';
    public $batchFiles = array('entry.sql', 'word.sql');
    public $dataFiles = array('entry' => 'entry.txt', 'word' => 'word.txt');
    public $sourceFile = 'tllemma.v1.csv';

    public $uniquePofFile = 'unique-pof.php';
    public $fixPofFile = 'fix-pof.php';

    public $uniquePof;
    public $fixPof;

    public $lineFormat = array(
        'word'     => self::word,
        'main'     => self::word,
        'pof'      => '([\pL .(),\d?]*)',
        'variants' => '([^;]*),?',
        );

    public $fix = array(
        'conque(s)...que'                => 'conque(s) que',
        'sal geme s.m.'                  => 'sal geme',
        'tipe .. tope'                   => 'tipe tope',
        'vis: a vis'                     => 'vis a vis',
        'me[r]cresdi, mercres, mescredi' => 'me(r)cresdi, mercres, mescredi',
        );

    public $special = array(
        'auberc (h)aubert'          => array('auberc', 'aubert', 'haubert'),
        'brelle (et) melle (mesle)' => array('brelle', 'melle', 'mesle'),
        'conque(s) que'             => array('conque', 'conques', 'que'),
        'keuve leu leu (a la)'      => array('keuve', 'leu'),
        'ma (m\')'                  => array('ma'),
        'ma(n)drago(i)re'           => array('madragore', 'mandragoire'),
        'ne (non) le'               => array('ne', 'non'),
        'puet ce (cel) estre'       => array('puet', 'estre'),
        'rechigne-chat (a)'         => array('rechigne', 'chat'),
        'Ta ça, ta ça'              => array('ta', 'ça'),
        'ta ta, ta ho'              => array('ta', 'ho'),
        'wara(e)ntis(s)ement'       => array('warantisement', 'waraentissement'),
        );

    public function __construct($config, $verbose = false)
    {
        $config['dictionaries']['tobler'] = array();

        parent::__construct($config, $verbose);

        $this->uniquePofFile = $this->addPathName($this->uniquePofFile);
        $this->fixPofFile = $this->addPathName($this->fixPofFile);
        $this->fixPof = require $this->fixPofFile;

        $this->lineFormat = '~^' . implode('\s*;\s*', $this->lineFormat) . ';~u';

        $this->fix = array(
            'search'  => array_keys($this->fix),
            'replace' => array_values($this->fix),
        );
    }

    public function parseLine($line, $lineNumber)
    {
        if ($lineNumber === 1) {
            return array();
        }
        // trims the line, converts to UTF-8, fixes typos
        $line = trim($line);
        $line = mb_convert_encoding($line, 'UTF-8', 'ISO-8859-1');
        $line = str_replace($this->fix['search'], $this->fix['replace'], $line);

        // extracts the word, the corresponding main word, the part of speech, the variant forms
        if (! preg_match($this->lineFormat, $line, $matches)) {
            $this->error('wrong format: ' . $this->string->utf8ToInternal($line), true, $lineNumber);
        }

        list(, $lemma, $main, $pof, $variants) = $matches;
        // removes trailing punctuation and space
        $lemma = trim($lemma, ', ');
        $main = trim($main, ', ');
        // parses the lemma, sets the word data
        list($words, $isMultiWord) = $this->parseLemma($lemma, $line, $lineNumber);
        $wordData = $this->setWordsData($words, $isMultiWord, $lineNumber);
        // parses the variants
        $variants = $this->parseVariants($variants, $line, $lineNumber);
        // parses the part of speech
        $pof = $this->parsePof($pof, $line, $lineNumber);
        // sets the entry info
        $info = $this->setInfo($lemma, $main, $pof, $variants, $line, $lineNumber);

        $entryData = array(
            'lemma'    => $lemma,
            'main'     => $main,
            'pof'      => $pof,
            'variants' => $variants,
            'info'     => $info,
            'line'     => $lineNumber,
            );
        ksort($entryData);

        return array(
            'entry' => implode('|', $entryData),
            'word'  => $wordData,
            );
    }

    public function parsePof($pof, $line, $lineNumber)
    {
        // captures part of speech
        $this->uniquePof[] = $pof;

        // fixes the part of speech if identified or removes it
        if (isset($this->fixPof[$pof])) {
            $pof = $this->fixPof[$pof];
        } else {
            $pof = '';
        }

        return $pof;
    }

    public function parseLemma($lemma, $line, $lineNumber)
    {
        // removes trailing punctuations, spaces, contracted articles
        $word = trim($lemma, '-!');
        $word = str_replace(array("d'", "l'", "n'"), '', $word);

        $isMultiWord = true;

        if (isset($this->special[$word])) {
            // special cases
            $words = $this->special[$word];
        } else if (preg_match('~^\pL+$~u', $word)) {
            // a single word, e.g. "aaatir"
            $words = array($word);
            $isMultiWord = false;
        } else if (preg_match('~^(\pL+)(?:[- ](\pL+))(?:[- ](\pL+))?(?:[- ](\pL+))?$~u', $word, $matches)) {
            // a composed word, e.g. "abat-quatre", extracts the single word
            // or a multi word, e.g. "a chief de foiz"
            array_shift($matches);
            $words = $matches;
        } else if (preg_match('~^(\pL*)\(\pL+\)(\pL*)$~u', $word, $matches)) {
            // a multi spelling word, e.g. "aier(es)", "apri(s)mier", '(en)scïentos"
            // extracts the word including the content between the brakets
            $longWord = array_shift($matches);
            $words[] = str_replace(array('(', ')'), '', $longWord);
            // extracts the word excluding the content between the brakets
            $words[] = implode('', $matches);
        } else if (preg_match('~^(\pL+), (\pL+)$~u', $word, $matches)) {
            // a multi word interjection, e.g. "cuisse, cuisse!", "hurte, belin!"
            list(, $words[], $words[]) = $matches;
            $words = array_unique($words);
        } else if (preg_match('~^(\pL+) \((a|au|que|en|a la|lo|en, a|a, au|ci, là, où|de|Saint|chanter de Rogier)\)$~u',
                $word, $matches)) {
            // a word used with a coordination or preposition, e.g. "chatons (a)"
            $words[] = next($matches);
        } else if (preg_match('~^(entr)\'(\pL+)$~u', $word, $matches)) {
            // a word used with the "entr" prefix, e.g. "entr'äatir"
            array_shift($matches);
            $words[] = implode('', $matches);
        } else if (preg_match('~^\'(\pL+)$~u', $word, $matches)) {
            // a word prefixed with "'", e.g. "'hesme"
            $words[] = next($matches);
        } else if (preg_match('~^([\pL ]+)! ?\1$~u', $word, $matches)) {
            // a repetitive interjection, e.g. "'pren la! pren la!"
            $words = explode(' ', next($matches));
        } else if (preg_match('~^(\pL+) \((\pL+)\)$~u', $word, $matches)) {
            // a word used with another word, e.g. "emende (esmende)"
            // ATTENTION! this test must remain at the end !
            array_shift($matches);
            $words = $matches;
        } else {
            // a few words are ignored, e.g. "l'", "qu'" etc...
            // see the report/error file
            $this->error("ignoring word: $line", false, $lineNumber);
            $words = array();
        }

        return array($words, $isMultiWord);
    }

    public function parseVariants($variants, $line, $lineNumber)
    {
        if (!empty($variants)) {
            // there are variants, extracts the variants
            $variants = explode(',', $variants);

            foreach($variants as &$variant) {
                // trims and validates the variant
                $variant = trim($variant);

                if (! empty($variant) and ! preg_match(self::variant, $variant)) {
                    $this->error('wrong format: ' . $this->string->utf8ToInternal($line), true, $lineNumber);
                }
            }

            // removes empty variants
            $variants = array_filter($variants);
            // concatenates the variants
            $variants = implode(', ', $variants);
        }

        return $variants;
    }

    public function postProcessing($data)
    {
        $this->writeUniquePof();

        return $data;
    }

    public function setInfo($lemma, $main, $pof, $variants, $line, $lineNumber)
    {
        // checks there are not both a main word and variants
        if (! empty($main) and ! empty($variants)) {
            $this->error('not expecting both main and variants: ' . $this->string->utf8ToInternal($line), true, $lineNumber);
        }

        // sets the entry information with the lemma and the part of speech
        $info = "$lemma";

        if (! empty($pof)) {
            $info .= ", $pof";
        }

        // adds the corresponding main entry or the variants
        if (!empty($main)) {
            $info .= " [princ.: $main]";
        } else if (!empty($variants)) {
            $info .= " [variant.: $variants]";
        }

        return $info;
    }

    public function setWordsData($words, $isMultiWord, $lineNumber)
    {
        $wordsData = array();

        foreach($words as $word) {
            $data = array(
                'original'  => $word,
                'ascii'     => $this->string->utf8toASCII($word),
                'line'      => $lineNumber,
                'multiword' => (int)$isMultiWord,
            );

            ksort($data);
            $wordsData[] = implode('|', $data);
        }

        return implode("\n", $wordsData);
    }

    public function writeUniquePof()
    {
        // counts, sorts, exports the values
        $values = array_count_values($this->uniquePof);
        arsort($values);
        $content = "<?php\nreturn " . var_export($values, true) . ";\n?>";
        // writes the field values
        print "writing {$this->uniquePofFile} ... ";
        file_put_contents($this->uniquePofFile, $content);
        print "done\n";
    }
}