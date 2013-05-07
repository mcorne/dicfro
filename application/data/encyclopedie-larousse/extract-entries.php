<?php
/**
 * Dicfro
 *
 * Extracts the first words and pages from a volume of encyclopedie
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once '../../Base/String.php';

function check_entry($entry, $page)
{
    static $previous_entry, $singulars, $used_entries, $used_words;

    $original = $entry;
    $entry = mb_strtolower($entry, 'UTF-8');
    list($word) = preg_split('~[ [-]~', $entry);

    if (isset($used_entries[$entry]) and $used_entries[$entry] != $page) {
        // note that array_unique() is done on page entries[]
        return "(already used) $page $original";
    }
    $used_entries[$entry] = $page;

    if (preg_match('~\p{L}s$~u', $word)) {
        $singular = substr($word, 0, -1);

        if (! isset($used_words[$singular]) and ! isset($used_words[$word])) {
            $used_words[$word] = true; // this is to avoid repetitions for false positives
            return "(check plural) $page $original";
        }
    }
    $used_words[$word] = true;

    if (preg_match('~[^\p{L}\d)\]]$~u', $entry)) {
        return "(check ending) $page $original";
    }

    if (preg_match('~[/:.]~', $entry)) {
        if (! preg_match('~[\p{L}()* -]+\. \d\d~u', $entry)) {
            // not a departement
            return "(check charac) $page $original";
        }
    }

    if (count(explode(' ', $entry)) > 7) {
        return "(long entry)   $page $original";
    }

    return null;
}

function convert_entry_to_ascii($entry)
{
    if (preg_match('~^[()]~', $entry)) {
        // the entry begins with a non character, ignores the entry
        return null;
    }

    if (preg_match('~^\(?\d+\)?~', $entry)) {
        // the entry begins with a date, eg 1450 etc., ignores the entry
        return null;
    }

    if (! $words = preg_split('~\P{L}~u', $entry, null, PREG_SPLIT_NO_EMPTY) or count($words) > 15) {
        // this is an entry with no character or too many words for an entry, ignores the entry
        return null;
    }

    $word_ascii = Base_String::_utf8toASCII($words[0]);

    // keeps the first 10 words only (to make it easier to exclude entries)
    $words = array_slice($words, 0, 10);
    $entry = implode(' ', $words);
    $entry_ascii = Base_String::_utf8toASCII($entry);

    return array($entry_ascii, $word_ascii);
}

function display_errors($errors)
{
    if ($errors) {
        $errors = implode("\n", $errors);
        echo  Base_String::_utf8ToInternalString($errors) . "\n";
    }
}

function exclude_item($page, $entry, $exclusion)
{
    if ($exclusion === true) {
        // ignores the entry
        return true;

    } else if (is_array($exclusion)) {
        if (in_array($page, $exclusion) or in_array($entry, $exclusion)) {
            // the entry is in a list of pages or entries to ignore
            return true;
        }

    } else {
        if ($exclusion == $page or $exclusion == $entry) {
            // the entry is in a page or a specific entry to ignore
            return true;
        }
    }

    return false;
}

function extract_entry($words, $page, $volume = null)
{
    static $excluded_entries, $excluded_words, $replaced_entries;
    static $first_entry_ascii, $first_word_ascii, $init, $last_entry_ascii;

    if ($words === 'init') {
        // initialization
        $excluded_entries = load_excluded_entries($volume);
        $excluded_words = load_excluded_words($volume);
        list($first_entry_ascii, $last_entry_ascii, $first_word_ascii) = load_end_entries($volume);
        $replaced_entries = load_replaced_entries($volume);

        return;
    }

    $words = array_map('trim', $words);
    $words = array_filter($words);
    $entry = implode(' ', $words);
    $entry = str_replace('- ', '-', $entry);

    if (! $ascii = convert_entry_to_ascii($entry)) {
        // this is not a proper entry or most likely a paragraph, ignores the entry
        return null;
    }

    list($entry_ascii, $word_ascii) = $ascii;

    if (isset($excluded_words[$word_ascii]) and exclude_item($page, $entry, $excluded_words[$word_ascii])) {
        return null;
    }

    if (isset($excluded_entries[$entry_ascii]) and exclude_item($page, $entry, $excluded_entries[$entry_ascii])) {
        return null;
    }

    if (isset($replaced_entries[$entry]) and $replaced = replace_entry($page, $entry, $replaced_entries[$entry])) {
        list($entry, $first_entry_ascii, $first_word_ascii) = $replaced;

        return $entry;
    }

    if (strlen($entry_ascii) > 2 and
        ($entry_ascii == $first_entry_ascii or $word_ascii >= $first_word_ascii) and
        $entry_ascii <= $last_entry_ascii)
    {
        // the entry is longer than 2 chararcters and
        // same as the previous entry or after the previous entry and
        // before the last entry
        $first_entry_ascii = $entry_ascii;
        $first_word_ascii = $word_ascii;

        return $entry;
    }

    return null;
}

function extract_entries($line, $page, $errors)
{
    $paragraphs = explode('<br><br>', $line);
    $entries = array();

    foreach ($paragraphs as $paragraph) {
        if (preg_match_all('~(?: <br>)?<span class="PAG_\d+_ST\d+">([\p{L}\'‘’,.()/\d:* -]+)</span>~u', $paragraph, $matches)) {
            if ($entry = extract_entry($matches[1], $page)) {
                if ($error = check_entry($entry, $page)) {
                    $errors[] = $error;
                }

                $entries[] = $entry;
            }
        }
    }

    $entries = array_unique($entries);
    $entries = implode('; ', $entries);

    return array($entries, $errors);
}

function extract_file($volume)
{
    $input      = "encyclopedie-larousse-$volume.htm";
    $input_path = __DIR__ . "/../../../sources/encyclopedie-larousse/$input";

    $basename = basename($input, '.htm');
    $output = "$volume/$basename.csv";
    $output_path = __DIR__ . "/$output";

    if (! file_exists($input_path)) {
        die("missing file $input\n");
    }

    $directory = dirname($output_path);

    if (! file_exists($directory)) {
        mkdir($directory);
        file_put_contents("$directory/excluded-entries.php", "<?php return array();");
        file_put_contents("$directory/excluded-words.php",   "<?php return array();");
        file_put_contents("$directory/replaced-entries.php", "<?php return array();");
    }

    $lines = file($input_path, FILE_SKIP_EMPTY_LINES);
    list($entries, $errors) = extract_page_entries($volume, $lines);
    display_errors($errors);

    if (empty($entries)) {
        die("no entries found in $input\n");
    }

    file_put_contents($output_path, $entries);
    echo "file added $output\n";
}

function extract_files()
{
    $files = glob(__DIR__ . '/*', GLOB_ONLYDIR);
    $volumes = array_map('basename', $files);
    sort($volumes, SORT_NUMERIC);
    array_map('extract_file', $volumes);
}

function extract_page_entries($volume, $lines)
{
    list($first_page, $last_page) = load_end_pages($volume);

    $pattern = sprintf('~<br><br><span class="PAG_(\d+)_ST\d+">La</span> <span class="PAG_\d+_ST\d+">Grande</span> <span class="PAG_\d+_ST\d+">Encyclopédie</span> <span class="PAG_\d+_ST\d+">Larousse</span> <span class="PAG_\d+_ST\d+">-</span> <span class="PAG_\d+_ST\d+">Vol\.</span> <span class="PAG_\d+_ST\d+">%u</span> <br><br><span class="PAG_\d+_ST\d+">(\d+)</span>~', $volume);
    extract_entry('init', null, $volume);

    $entries = "page\tentries\timage\tvolume\n";
    $expected_page  = null;
    $expected_image = null;
    $errors = array();

    foreach ($lines as $line) {
        if (preg_match($pattern, $line, $match)) {
            list(, $image, $page) = $match;
            $image = (int) $image;

            if ($page < $first_page) {
                continue;
            }

            if ($page > $last_page) {
                break;
            }

            if (! isset($expected_page)) {
                $expected_page  = $page;
                $expected_image = $image;

            } else {
                while (isset($expected_page) and $page > $expected_page) {
                    // adds blank entry if page missing
                    $entries .= sprintf("%u\t\t%u\t%u\n", $expected_page, $expected_image, $volume);
                    $expected_page++;
                    $expected_image++;
                }
            }

            list($extracted_entries, $errors) = extract_entries($line, $page, $errors);

            $entries .= sprintf("%u\t%s\t%u\t%u\n", $page, $extracted_entries, $image, $volume);
            $expected_page++;
            $expected_image++;
        }
    }

    while (isset($expected_page) and $expected_page < $last_page) {
        // adds blank entry if page missing
        $entries .= sprintf("%u\t\t%u\t%u\n", $expected_page, $expected_image, $volume);
        $expected_page++;
        $expected_image++;
    }

    return array($entries, $errors);
}

function fix_loaded_entries($entries)
{
    $fixed = array();

    foreach ($entries as $key => $value) {
        if (is_numeric($key)) {
            $fixed[$value] = true;
        } else {
            $fixed[$key] = $value;
        }
    }

    return $fixed;
}

function load_end_entries($volume)
{
    $end_entries = require __DIR__ . '/volumes-end-entries.php';

    if (! isset($end_entries[$volume])) {
        die("missing volume first and last entries\n");
    }

    list($first_entry, $last_entry) = $end_entries[$volume];

    $first_entry_ascii = Base_String::_utf8toASCII($first_entry);
    $last_entry_ascii  = Base_String::_utf8toASCII($last_entry);

    list($word) = preg_split('~\P{L}~u', $first_entry, null, PREG_SPLIT_NO_EMPTY);
    $first_word_ascii = Base_String::_utf8toASCII($word);

    return array($first_entry_ascii, $last_entry_ascii, $first_word_ascii);
}

function load_end_pages($volume)
{
    $end_pages = require __DIR__ . '/volumes-end-pages.php';

    if (! isset($end_pages[$volume])) {
        die("missing volume first and last pages\n");
    }

    return $end_pages[$volume];
}

function load_excluded_entries($volume)
{
    $file = __DIR__ . "/$volume/excluded-entries.php";

    if (! file_exists($file)) {
        return array();
    }

    $excluded_entries = require $file;
    $excluded_entries = fix_loaded_entries($excluded_entries);

    $excluded_entries_ascii = array();

    foreach ($excluded_entries as $excluded_entry => $page) {
        list($excluded_entry) = convert_entry_to_ascii($excluded_entry);
        $excluded_entries_ascii[$excluded_entry] = $page;
    }

    return $excluded_entries_ascii;
}

function load_excluded_words($volume)
{
    $file = __DIR__ . "/$volume/excluded-words.php";

    if (! file_exists($file)) {
        return array();
    }

    $excluded_words = require $file;
    $excluded_words = fix_loaded_entries($excluded_words);

    $excluded_words_ascii = array();

    foreach ($excluded_words as $excluded_word => $page) {
        list(, $excluded_word) = convert_entry_to_ascii($excluded_word);
        $excluded_words_ascii[$excluded_word] = $page;
    }

    return $excluded_words_ascii;
}

function load_replaced_entries($volume)
{
    $file = __DIR__ . "/$volume/replaced-entries.php";

    if (! file_exists($file)) {
        return array();
    }

    $replaced_entries =  require $file;
    $replaced_entries = fix_loaded_entries($replaced_entries);

    foreach ($replaced_entries as $entry => &$replacement) {
        if ($replacement === true) {
            // eg replaces "Abeilles sociales" with "Abeille[s] sociale[s]"
            $replacement = preg_replace('~(.+?)(s)(?!\p{L})~i', '$1[$2]', $entry);
        }
    }

    return $replaced_entries;
}

function replace_entry($page, $entry, $replacement)
{
    if (is_array($replacement)) {
        // this is an entry to replace in a specific page, eg "canadienne" => array("litérature canadienne", 123)
        list($replacement, $target_page) = $replacement;

    } else if (is_numeric($replacement)) {
        // this is a default plural replacement in a specific page, eg "mouches" => 123
        $target_page = $replacement;
        $replacement = true;

    } else {
        // this is an entry to replace regardless of the page, eg "byzantine" => "litérature byzantine"
        $target_page = true;
    }

    if ($replacement === true) {
        // this is a default plural replacement, eg replaces "Abeilles sociales" with "Abeille[s] sociale[s]"
        $replacement = preg_replace('~(.+?)(s)(?!\p{L})~i', '$1[$2]', $entry);
    }

    if ($target_page !== true and $target_page != $page) {
        return null;
    }

    // replaces the entry
    $entry = $replacement;

    // removes the part not used for sorting
    list($usable_entry_part) = explode('[', $entry);
    list($first_entry_ascii, $first_word_ascii) = convert_entry_to_ascii($usable_entry_part);

    return array($entry, $first_entry_ascii, $first_word_ascii);
}

@list(, $volume) = $argv;

if (empty($volume)) {
    extract_files();
} else {
    extract_file($volume);
}