<?php

/**
 * Dicfro
 *
 * PHP 5
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Search
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'Model/Search.php';
// query classes are included as needed

/**
 * Search a generic dictionary
 *
 * @category   DicFro
 * @package    Model
 * @subpackage Search
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2008-2010 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_Search_Generic extends Model_Search
{
    /**
     * Template to create an image number
     */
    const BUILD_IMAGE_NUMBER_TPL = '%\'02s%s%\'04s';

    /**
     * Template to parse an image number
     */
    const PARSE_IMAGE_NUMBER_TPL = '~(\d\d)(?:0|1)(\d\d\d\d)~i';

    /**
     * Template of the name of an image file
     */
    const IMAGE_DEFAULT_PATH_TPL = 'dictionary/%s/mImg/%s.gif';

    /**
     * Additional digit used in some dictionaries, ex. in the "Godefroy Complement"
     * @var int
     */
    public $digit = 0;

    /**
     * Name of the dictionaries directory
     * @var string
     */
    public $directory = '.';

    /**
     * Name of the dictionary
     * @var string
     */
    public $dictionary;

    /**
     * Template of the image file names
     * @var string
     */
    public $imagePath;

    /**
     * Template of the errata file names
     * @var string
     */
    public $errataFiles;

    /**
     * Query object
     * @var object
     */
    public $query;

    /**
     * Flag to search or not the text errata
     * @var boolean
     */
    public $needErrataText = false;

    /**
     * Flag to search or not the image errata
     * @var boolean
     */
    public $needErrataImages = false;

    /**
     * Flag to search or not the ghostwords
     * @var boolean
     */
    public $needGhostwords = false;

    /**
     * Flag to search or not words in the Tobler
     * @var boolean
     */
    public $needTobler = false;

    /**
     * Flag to search or not words in the Whitaker
     * @var boolean
     */
    public $needWhitaker = false;

    /**
     * Flag to search or not words in the Tcaf
     * @var boolean
     */
    public $needTcaf = false;

    public function __construct($directory, $properties, $query = array())
    {
        isset($query['class']) or $query['class'] = 'Model_Query_Generic';
        parent::__construct($directory, $properties, $query);

        $this->directory = $directory;
    }

    /**
     * Extracts the volume and page numbers from the image number
     *
     * @param  string $imageNumber the image number
     * @return array  the volume and page numbers
     */
    public function extractVolumeAndPage($imageNumber)
    {
        return preg_match(self::PARSE_IMAGE_NUMBER_TPL, $imageNumber, $match)? array($match[1], $match[2]) : array();
    }

    /**
     * Goes to the next page
     *
     * @param  string $volume the dictionary volume
     * @param  string $page   the page in the volume to go from
     * @return array  the page details
     */
    public function goToNextPage($volume, $page)
    {
        $imageNumber = $this->setImageNumber($volume, $page);
        $result = $this->query->goToNextPage($imageNumber);

        return $this->updateResult($result);
    }

    /**
     * Goes to a given page
     *
     * @param  string $volume the dictionary volume
     * @param  string $page   the page in the volume to go to
     * @return array  the page details
     */
    public function goToPage($volume, $page)
    {
        $imageNumber = $this->setImageNumber($volume, $page);
        $result = $this->query->goToPage($imageNumber);

        return $this->updateResult($result);
    }

    /**
     * Goes to the previous page
     *
     * @param  string $volume the dictionary volume
     * @param  string $page   the page in the volume to go from
     * @return array  the page details
     */
    public function goToPreviousPage($volume, $page)
    {
        $imageNumber = $this->setImageNumber($volume, $page);
        $result = $this->query->goToPreviousPage($imageNumber);

        return $this->updateResult($result);
    }

    /**
     * Searches the errata of a given page
     *
     * @param  string $imageNumber the image number
     * @return array  the list of errata file names
     */
    public function searchErrata($imageNumber)
    {
        list($volume, $page) = $this->extractVolumeAndPage($imageNumber);
        $pattern = sprintf($this->errataFiles, $volume, (int)$page);

        return glob($pattern);
    }

    /**
     * Searches the ghostwords in a given page
     *
     * @param  array $foundWord the details of the word to search from, including this word
     * @param  array $nextWord  the details of the word to search to, excluding this word
     * @return mixed the list of ghostwords
     */
    public function searchGhostwords($foundWord, $nextWord)
    {
        require_once 'Model/Query/Ghostwords.php';
        $query = new Model_Query_Ghostwords($this->directory);

        return $query->searchWords($foundWord['ascii'], $nextWord['ascii']);
    }

    /**
     * Searches verbs in the Tcaf matching a word form
     *
     * @param  string $word the word form to search
     * @return array  the list of identified verbs
     */
    public function searchTcaf($word)
    {
        require_once 'Model/Query/Tcaf.php';
        $query = new Model_Query_Tcaf($this->directory);

        return $query->searchVerbs($word);
    }

    /**
     * Searches words in the Tobler matching a word form
     *
     * @param  string $word the word form to search
     * @return object the list of identified words
     */
    public function searchTobler($word)
    {
        require_once 'Model/Query/Tobler.php';
        $query = new Model_Query_Tobler($this->directory);

        return $query->searchWords($word);
    }

    /**
     * Searches words in the Whitaker matching a word form
     *
     * @param  string $word the word form to search
     * @return object the list of identified words
     */
    public function searchWhitaker($word)
    {
        require_once 'Model/Query/Whitaker.php';
        $query = new Model_Query_Whitaker($this->directory);

        return $query->searchWords($word);
    }

    /**
     * Searches a word in the dictionary
     *
     * @param  string $word the word to search
     * @return array  the word details
     */
    public function searchWord($word)
    {
        $result = $this->query->searchWord($word);

        return $this->updateResult($result, $word);
    }

    /**
     * Sets the image number from the volume and page numbers
     *
     * @param  string  $volume the dictionary volume
     * @param  string  $page   the page in the volume to go from
     * @return string  the image number
     */
    public function setImageNumber($volume, $page)
    {
        return sprintf(self::BUILD_IMAGE_NUMBER_TPL, $volume, $this->digit, $page);
    }

    /**
     * Sets the image path
     *
     * @param  array  $foundWord the word details
     * @return string the image path
     */
    public function setImagePath($foundWord)
    {
        if ($this->imagePath) {
            $path = sprintf($this->imagePath, $foundWord['image']);
        } else {
            $path = sprintf(self::IMAGE_DEFAULT_PATH_TPL, $this->dictionary, $foundWord['image']);
        }

        return $path;
    }

    /**
     * Updates the result or the word search or page change
     *
     * @param  array  $result the result set
     * @param  string $word   the word to search
     * @return array  the updated result set
     */
    public function updateResult($result, $word = null)
    {
        @list($foundWord, $nextWord) = $result;

        list($volume, $page) = $this->extractVolumeAndPage($foundWord['image']);
        $volume = (int)$volume or $volume = '';
        $page = (int)$page or $page = '';

        return array(
            $this->setImagePath($foundWord),
            $this->needErrataImages? $this->searchErrata($foundWord['image']) : null,
            $this->needErrataText? $foundWord['errata'] : null,
            $this->needGhostwords? $this->searchGhostwords($foundWord, $nextWord) : null,
            ($word and $this->needTobler)? $this->searchTobler($word) : null,
            ($word and $this->needTcaf)? $this->searchTcaf($word) : null,
            ($word and $this->needWhitaker)? $this->searchWhitaker($word) : null,
            $volume,
            $page,
            $foundWord['original'],
        );
    }
}