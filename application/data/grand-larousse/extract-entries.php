<?php
/**
 * Dicfro
 *
 * Extracts the first words and pages from a volume
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once '../../Base/String.php';

function extract_entries($number)
{
    mb_internal_encoding('UTF-8');

    $volumes = array(
        1 => array('first_page' => 1   , 'last_page' => 722 , 'first_image' => 105, 'last_image' => 826 , 'last_word' => 'cippe'),
        2 => array('first_page' => 723 , 'last_page' => 1686, 'first_image' => 9  , 'last_image' => 972 , 'last_word' => 'érythrose'), // page 1686 is blank
        3 => array('first_page' => 1687, 'last_page' => 2560, 'first_image' => 9  , 'last_image' => 882 , 'last_word' => 'incuse'), // page 2560 is blank
        4 => array('first_page' => 2561, 'last_page' => 3618, 'first_image' => 9  , 'last_image' => 1066, 'last_word' => 'nystagmus'),
        5 => array('first_page' => 3619, 'last_page' => 4648, 'first_image' => 9  , 'last_image' => 1038, 'last_word' => 'psittacus'),
        6 => array('first_page' => 4649, 'last_page' => 5768, 'first_image' => 9  , 'last_image' => 1128, 'last_word' => 'survolteur'), // page 5768 is blank
        7 => array('first_page' => 5769, 'last_page' => 6528, 'first_image' => 9  , 'last_image' => 768 , 'last_word' => 'zythum'),
    );

    if (! isset($volumes[$number])) {
        exit('volume number must be between 1 and 7');
    }

    $volume = $volumes[$number];
    $entries = init_volume_entries($number, $volume['first_page'], $volume['last_page'], $volume['first_image'], $volume['last_image']);
    $entries = extract_volume_entries($number, $entries, $volume['last_word']);
    write_index($entries, $number);
}

function extract_volume_entries($volume, $entries, $last_word)
{
    $lines = load_volume($volume);
    $excluded_entries = load_excluded_entries($volume);
    $replaced_entries = load_replaced_entries($volume);
    $last_ascii = Base_String::_utf8toASCII($last_word);
    $prev_ascii = null;
    $fixes = array();

    foreach($lines as $line) {
        $line = trim($line);

        if (ctype_digit($line)) {
            // this is a page number
            $page = $line;

        } else if (isset($replaced_entries[$page])) {
            // there is a replacement for this page entry
            $word = $replaced_entries[$page];
            $entries[$page]['word'] = $word;
            $prev_ascii = Base_String::_utf8toASCII($word);

        } else if (! isset($entries[$page]['word']) and
                   preg_match('~^([^[\]]+) +\[[øœ̃œɑ̃ɑɔ̃ɔəɛ̃ɛɥɲʁʃʒabdefgijklmnoprstuvwyz(), -]+\]~u', $line, $match)) {
            // this is an entry, a head word followed by its pronunciation, ex. psophométrie [psfometri]
            $word = $match[1];

            if (preg_match('~^(\d)\. +(.+)~u', $word, $match)) {
                if ($match[1] == 1) {
                    // this is the first occurence of a word, ex. "1. punch", fixes word, ex. "punch 1"
                    $word = $match[2];
                } else {
                    // ignores subsequent occurences
                    // $fixes[$page] = "ignored $word";
                    continue;
                }
            }

            list($word, $fixes) = fix_word($word, $fixes, $page);

            // excludes inflexions, ex. "passé,e" to keep "passé" only instead of "passée" because there is a the word "passé" after
            list($base_word) = explode(',', $word);
            $ascii = Base_String::_utf8toASCII($base_word);

            $fixes = set_warnings($word, $base_word, $fixes, $page, $ascii, $prev_ascii);

            if (preg_match('~[«»<>]~u', $word) or              // excludes citations etc.
                isset($excluded_entries[$word]) and            // excludes specific words, possibly for a given page
                    ($excluded_entries[$word] === true or in_array($page, $excluded_entries[$word])) or
                isset($prev_ascii) and $ascii <= $prev_ascii or // excludes word before previous word
                $ascii > $last_ascii)                          // excludes word after the last word
            {
                // this is a false positive
                unset($fixes[$page]);
                continue;
            }

            $entries[$page]['word'] = $word;
            $prev_ascii = $ascii;
        }
    }

    if (empty($entries)) {
        die("no entries found in volume $volume");
    }

    echo print_fixes($fixes);

    return $entries;
}

function fix_loaded_entries($entries)
{
    $fixed = array();

    foreach ($entries as $key => $value) {
        if (is_numeric($key)) {
            $fixed[$value] = true;
        } else {
            $fixed[$key] = (array) $value;
        }
    }

    return $fixed;
}

function fix_word($word, $fixes, $page)
{
    if ($word[0] == '*') {
        // removes * prefix, ex. "*haché"
        $copy = $word;
        $word = substr($word, 1);
        $fixes[$page] = "fixed: $copy => $word";
    }

    if (preg_match('~^([a-z]) \\1$~', $word, $match)) {
        // this is a double entry for a letter, ex. "i i"
        $copy = $word;
        $word = $match[1];
        $fixes[$page] = "fixed: $copy => $word";
    }

    $words = explode(' ou ', $word);
    if (isset($words[1])) {
        // removes variants, ex. balluchon ou baluchon
        $copy = $word;
        $word = $words[0];
        $fixes[$page] = "fixed: $copy => $word";
    }

    if (strpos($word, '- ') or strpos($word, ' -')) {
        // removes spaces around "-"
        $copy = $word;
        $word = str_replace('- ', '-', $word);
        $word = str_replace(' -', '-', $word);
        $fixes[$page] = "fixed: $copy => $word";
    }

    return array($word, $fixes);
}

function implode_entry($entry)
{
    return sprintf("%u\t%s\t%u\t%u", $entry['page'], $entry['word'], $entry['image'], $entry['volume']);
}

function init_volume_entries($number, $first_page, $last_page, $first_image, $last_image)
{
    $pages = range($first_page, $last_page);
    $images = range($first_image, $last_image);
    $entries = array();

    foreach ($pages as $index => $page) {
        $entries[$page] = array('page' => $page, 'word' => null, 'image' => $images[$index], 'volume' => $number);
    }

    return $entries;
}

function load_excluded_entries($volume)
{
    $file = "$volume/excluded-entries.php";

    if (! file_exists($file)) {
        return null;
    }

    $entries = require $file;

    return fix_loaded_entries($entries);
}

function load_replaced_entries($volume)
{
    $file = "$volume/replaced-entries.php";

    if (! file_exists($file)) {
        return null;
    }

    return require $file;
}

function load_volume($volume)
{
    $basename = "sources/grand-larousse/grand-larousse-$volume.txt";
    $file = __DIR__ . "/../../../$basename";

    if (! file_exists($file)) {
        exit("missing file $basename");
    }

    return file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

function print_fixes($fixes)
{
    if (empty($fixes)) {
        return null;
    }

    foreach ($fixes as $page => &$fixe) {
        $fixe = "($page) $fixe";
    }

    $fixes = implode("\n", $fixes) . "\n";

    return Base_String::_utf8ToInternalString($fixes);
}

function set_warnings($word, $base_word, $fixes, $page, $ascii, $prev_ascii)
{
    if (strpos($base_word, ' ')) {
        $fixes[$page] = "space inside: $word";
    }

    if (substr($base_word, -1) == 's') {
        // $fixes[$page] = "plural: $word";
    }

    if (mb_substr($base_word, -1) == 'é' and $ascii != $prev_ascii) {
        // the entry is similar to a previous entry in a previous page
        // ex. "stylé, e" (SYTLE) in current page, "stylaire" (STYLAIRE) in previous page
        // but there is also "style" (STYLE) in previous page which will not be found when searching "style"
        $candidate = mb_substr($base_word, 0, -1) . 'e';
        $fixes[$page] = "change: $word if $candidate is a word and in a previous page";
    }

    return $fixes;
}

function write_index($entries, $number)
{
    $lines = array_map('implode_entry', $entries);
    $headers = "page\tentries\timage\tvolume";
    $content = implode("\n", $lines);
    $file = "$number/index.csv";
    file_put_contents($file, $headers . "\n" . $content . "\n");

    echo "file added $file";
}

@list(, $number) = $argv;
extract_entries($number);
