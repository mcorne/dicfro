<?php
/**
 * Dicfro
 *
 * Extracts the first words and pages from the different Century XML files of a given volume
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

function extract_file($input)
{
    $input_path = __DIR__ . "/../../../sources/century/$input";

    $basename = basename($input, '.xml');
    $output = dirname($input) . "/$basename.csv";
    $output_path = __DIR__ . "/$output";

    list($book) = explode('_djvu', $basename);
    $pattern = sprintf('<PARAM name="PAGE" value="%s_0(\d\d\d).djvu"/>', $book);

    if (! file_exists($input_path)) {
        die("missing file $input\n");
    }


    if (file_exists($output_path)) {
        echo "file exists $output\n";
        return;
    }

    $directory = dirname($output_path);
    if (! file_exists($directory)) {
        mkdir($directory);
    }

    $lines = file($input_path, FILE_SKIP_EMPTY_LINES);

    $entries = '';
    $first_word = null;

    foreach ($lines as $line) {

        if (preg_match($pattern, $line, $match)) {
            list(, $image) = $match;
            $image = (int) $image;

            if ($first_word === '') {
                // no first word
                $entries .= "\n";
            } else {
                // resets first word
                $first_word = '';
            }

            $entries .= "$image\t";

        } else if ($first_word === '') {
            $line = html_entity_decode($line, ENT_QUOTES, 'UTF-8');
            $line = str_replace('&apos;', "'", $line);

            if (preg_match('~<WORD coords="[\d,]+">([\p{L}\' -]+)</WORD>~u', $line, $match)) {
                list(, $first_word) = $match;
                $first_word = trim($first_word);
                $entries .= "$first_word\n";
            }
        }
    }

    if (empty($entries)) {
        die("no entries found in $input\n");
    }

    file_put_contents($output_path, $entries);
    echo "file added $output\n";
}

function extract_files($volume)
{
    $files = glob(__DIR__ . "/../../../sources/century/$volume/*.xml");
    $files = array_map('basename', $files);

    foreach ($files as $file) {
        extract_file("$volume/$file");
    }
}

function extract_volumes()
{
    $dirs = glob(__DIR__ . "/../../../sources/century/*", GLOB_ONLYDIR);

    return array_map('basename', $dirs);
}

@list(, $volume) = $argv;

$volumes = extract_volumes();

if (! in_array($volume, $volumes)) {
    $volumes = implode(', ', $volumes);
    die("invalid volume, expecting one of $volumes");
}

extract_files($volume);