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

require_once 'Base/String.php';

/**
 * Dictionary parser
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Parser
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

abstract class Model_Parser
{

    public $batchFiles        = 'word.sql';
    public $batchFileTemp     = 'temp.sql';
    public $batchFileTemplate = 'word.sql';
    public $dataBase          = 'dictionary.sqlite';
    public $dataFiles         = 'word.txt';
    public $dictionary;
    public $dictionaryConfig;
    public $directory;
    public $error;
    public $errorFile         = 'error.txt';
    public $noExitOnEror;
    public $sourceFile        = 'index.txt';
    public $search;
    public $string;
    public $verbose;

    public function __construct($directory, $properties = array(), $dictionaryConfig = array(), $noExitOnEror = false, $verbose = false)
    {
        $this->directory        = $directory;
        $this->dictionaryConfig = $dictionaryConfig;
        $this->noExitOnEror     = $noExitOnEror;
        $this->verbose          = (bool)$verbose;

        foreach($properties as $property => $value) {
            $this->$property = $value;
        }

        $this->batchFileTemplate = $directory . '/' . $this->batchFileTemplate;
        $this->batchFileTemp     = $this->addPathName($this->batchFileTemp);
        $this->dataBase          = $this->addPathName($this->dataBase);
        $this->errorFile         = $this->addPathName($this->errorFile);
        $this->sourceFile        = $this->addPathName($this->sourceFile);

        settype($this->batchFiles, 'array');
        settype($this->dataFiles, 'array');

        $this->string = new Base_String;
    }

    public function __destruct()
    {
        if ($this->errorFile and $this->error) {
            if (empty($this->error)) {
                $error = '';
            } else {
                $error = implode('', $this->error);
            }

            print "writing {$this->errorFile} ... ";
            @file_put_contents($this->errorFile, $error);
            print "done";
        }
    }

    public function addPathName($basename)
    {
        return $this->directory . '/' . $this->dictionary . '/' . $basename;
    }

    public function create($lineStart = null, $lineCount = null)
    {
        // reads and parses the dictionary
        list($lines, $lineStart) = $this->read($lineStart, $lineCount);
        $lines = $this->preProcessing($lines);
        $data = $this->parse($lines, $lineStart);
        $data = $this->postProcessing($data);
        // writes and imports the dictionary data files into the database
        $this->write($data);
        $this->import();
    }

    public function createBatchFile()
    {
        if (! $template = file_get_contents($this->batchFileTemplate)) {
            $this->error("cannot read $this->batchFileTemplate", true);
        }

        $dataFile = $this->addPathName(current($this->dataFiles));
        $dataFile = str_replace('\\', '/' , $dataFile);
        $content = sprintf($template, $dataFile);

        if (! @file_put_contents($this->batchFileTemp, $content)) {
            $this->error("cannot write $this->batchFileTemp", true);
        }
    }

    /**
     * @see Controller_Interface::createSearchObject()
     */
    public function createSearchObject()
    {
        if (isset($this->dictionaryConfig['search']['class'])) {
            $class = $this->dictionaryConfig['search']['class'];
        } else {
            $class = 'Model_Search_' . ucfirst($this->dictionaryConfig['type']);
        }

        $file = str_replace('_', '/', $class) . '.php';
        require_once $file;

        $this->search = new $class($this->directory, $this->dictionaryConfig['search']['properties'], $this->dictionaryConfig['query']);
    }

    /**
     * @see Controller_Interface::setDictionary()
     */
    public function setDictionary()
    {
        if (! isset($this->dictionaryConfig['query'])) {
            $this->dictionaryConfig['query'] = array();
        }

        if (! isset($this->dictionaryConfig['search'])) {
            $this->dictionaryConfig['search'] = array();
        } else if (is_string($this->dictionaryConfig['search'])) {
            $this->dictionaryConfig['search'] = array('properties' => array('url' => $this->dictionaryConfig['search']));
        }

        if (! isset($this->dictionaryConfig['search']['properties']['dictionary'])) {
            $this->dictionaryConfig['search']['properties']['dictionary'] = $this->dictionary;
        }
    }

    public function error($message, $isError, $lineNumber = null, $verbose = false)
    {
        if ($isError) {
            $errorType = 'Error';
        } else {
            $errorType = 'Warning';
        }

        $string = "\n$errorType! ";

        if (! is_null($lineNumber)) {
            $basename = basename($this->sourceFile);
            $string .= "($basename #$lineNumber) ";
        }

        $string .= "$message\n";

        if ($isError or $verbose or $this->verbose) {
            echo $string ;
        }

        if (! empty($this->errorFile)) {
            $this->error[] = $string;
        }

        if ($isError and ! $this->noExitOnEror) {
            exit(1);
        }
    }

    public function import()
    {
        print "creating database {$this->dataBase} ... \n" ;

        foreach($this->batchFiles as $basename) {
            $name = $this->addPathName($basename);
            print "reading $name ... " ;

            if (!($isBatchFile = file_exists($name))) {
                $name = $this->batchFileTemp;
                $this->createBatchFile();
            }

            $command = "echo .read $name | sqlite3 {$this->dataBase}";
            $command = str_replace('\\', '/' , $command);
            $lineCount = exec($command, $ouput, $returnVar);

            if ($returnVar) {
                $this->error("cannot execute $name (error: $returnVar)", true);
            }

            if (! is_numeric($lineCount)) {
                $this->error("cannot import via $name (error: $lineCount)", true);
            }

            echo "$lineCount lines imported\n";

            if (! $isBatchFile) {
                unlink($this->batchFileTemp);
            }
        }
    }

    public function isEndOfData($line)
    {
        return false;
    }

    public function isLineIgnored($line)
    {
        return false;
    }

    public function parse($lines, $lineNumber)
    {
        // parses the dictionary
        print "parsing {$this->sourceFile} ";

        $data = array_fill_keys(array_keys($this->dataFiles), '');

        foreach($lines as $line) {
            // parses the line, adds the lines to the data
            $parsed = $this->parseLine($line, $lineNumber);

            foreach($parsed as $name => $string) {
                if (! empty($string)) {
                    $data[$name] .= $string . "\n";
                }
            }

            $lineNumber++;

            if (! $lineNumber % 1000) {
                echo '.';
            }
        }

        print ' ' . count($lines) . " lines parsed\n";

        return $data;
    }

    abstract public function parseLine($line, $lineNumber);

    public function preProcessing($lines)
    {
        return $lines;
    }

    public function postProcessing($data)
    {
        return $data;
    }

    public function read($lineStart = null, $lineCount = null)
    {
        // reads the dictionary
        print "reading {$this->sourceFile} ... ";

        if (! $lines = @file($this->sourceFile)) {
            $this->error("cannot read or empty file {$this->sourceFile}", true);
        }

        print count($lines) . " lines read\n";

        if (empty($lineStart)) {
            $lineStart = 1;
        }

        if (empty($lineCount)) {
            $lineCount = 99999;
        }

        if ($lineStart !== 1 or $lineCount !== 99999) {
            // slices the dictionary (used primarily for debugging purposes)
            print "slicing {$this->sourceFile} ... ";
            $lines = array_slice($lines, $lineStart - 1, $lineCount);
            print count($lines) . " lines sliced\n";
        }

        return array($lines, $lineStart);
    }

    public function validateWordOrder($word, $lineNumber)
    {
        // validating the word order helps spots invalid entries where entries are expected
        // to be sorted in the source file, ex. gdf like Txt files
        // it should not be used for dictionaries that are not sorted, ex. ghostwords

        static $prevWord = null;

        if (! is_null($prevWord) and $prevWord > $word) {
            $this->error("bad word order: $prevWord > $word", true, $lineNumber);
        }

        $prevWord = $word;
    }

    public function write($data)
    {
        // writes the dictionary data file
        foreach($data as $name => $string) {
            $file = $this->addPathName($this->dataFiles[$name]);

            print "writing data file $file ... " ;

            if (! $bytesCount = @file_put_contents($file, $string)) {
                $this->error("cannot write file $file", true);
            }

            print "done\n";
        }
    }
}