<?php
/**
 * Dicfro
 *
 * Compares the first words extracted from the Century XML files of a given volume
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

$volumes_first_page = array(
    1 => 1,
    2 => 881,
    3 => 1777,
);

function calculate_first_word_frequency($entry)
{
    $first_words = array();

    foreach ($entry as $book => $book_entry) {
        $first_words[] = $book_entry['first_word'];
    }

    $first_word_frequency = array_count_values($first_words);
    arsort($first_word_frequency);

    return $first_word_frequency;
}

function compare_first_words($volume, $first_page)
{
    $books_entries = read_files($volume);
    $books = array_keys($books_entries);
    $entries = merge_entries($books_entries);

    $books_accurary = array_fill_keys($books, 0);
    $first_words_frequency = array();

    foreach ($entries as $number => $entry) {
        $first_words_frequency[$number] = calculate_first_word_frequency($entry);
        $books_accurary = update_books_accuracy($books_accurary, $entry, $first_words_frequency[$number]);
    }

    arsort($books_accurary);

    write_file($volume, $first_page, $books, $entries, $first_words_frequency, $books_accurary);
}

function extract_volumes()
{
    $dirs = glob(__DIR__ . "/*", GLOB_ONLYDIR);

    return array_map('basename', $dirs);
}

function fix_first_word($first_word, $entry, $first_word_frequency, $frequency, $selected_book)
{
    if (isset($entry[$selected_book])) {
        $selected_book_first_word = $entry[$selected_book]['first_word'];

        if ($first_word_frequency[$selected_book_first_word] == $frequency) {
            // picks selected book first word in case of tie
            return $selected_book_first_word;
        }
    }

    return $first_word;
}

function merge_entries($books_entries)
{
    $entries = array();

    foreach ($books_entries as $book => $book_entries) {
        foreach ($book_entries as $number => $entry) {
            $entries[$number][$book] = $entry;
        }
    }

    return $entries;
}

function read_file($file)
{
    $lines = file($file, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
    $entries = array();

    foreach ($lines as $line) {
        list($image, $first_word) = explode("\t", $line);
        $entries[] = array('image' => $image, 'first_word' => $first_word);
    }

    return $entries;
}

function read_files($volume)
{
    $files = glob(__DIR__ . "/$volume/*_djvu.csv");
    $books_entries = array();

    foreach ($files as $file) {
        $book = basename($file);
        $books_entries[$book] = read_file($file);
    }

    return $books_entries;
}

function set_check($books, $entry, $first_word_frequency, $selected_book)
{
    if (empty($first_word_frequency)) {
        $first_word = null;
        $frequency =  null;
        $check = 'missing';

    } else {
        list($first_word, $frequency) = each($first_word_frequency);
        $check = null;

        if (count($first_word_frequency) >= 2) {
            list(, $next_frequency) = each($first_word_frequency);

            if ($next_frequency == $frequency) {
                // top 2 words with same frequency
                $check = 'tie';
                $first_word = fix_first_word($first_word, $entry, $first_word_frequency, $frequency, $selected_book);

            } else if ($frequency <= count($books) / 2) {
                // word in less than half the books
                $check = 'weak';
            }
        }
    }

    return array($first_word, $frequency, $check);
}

function set_image($entry, $selected_book)
{
    if (isset($entry[$selected_book]['image'])) {
        $image = $entry[$selected_book]['image'];
    } else {
        $image = null;
    }

    return $image;
}

function update_books_accuracy($books_accurary, $entry, $first_word_frequency)
{
    foreach ($entry as $book => $book_entry) {
        $first_word = $book_entry['first_word'];
        $books_accurary[$book] += $first_word_frequency[$first_word];
    }

    return $books_accurary;
}

function write_file($volume, $page, $books, $entries, $first_words_frequency, $books_accurary)
{
    $books_count = count($books);
    $lines[] = write_headers($books);
    $selected_book = key($books_accurary);

    foreach ($entries as $number => $entry) {
        if ($number < $books_count) {
            list($book, $accuracy) = each($books_accurary);
        } else {
            $book = null;
            $accuracy = null;
        }

        $lines[] = write_line($volume, $page++, $books, $entry, $first_words_frequency[$number], $book, $accuracy, $selected_book);
    }

    $content = implode("\n", $lines);
    $output = "$volume/entries.csv";
    file_put_contents(__DIR__ . "/$output", $content);
    echo "file created $output\n";
}

function write_headers($books)
{
    $cells[] = 'page';
    $cells[] = 'entries';
    $cells[] = 'image';
    $cells[] = 'volume';
    $cells[] = 'frequency';
    $cells[] = 'check';
    $cells[] = 'books';
    $cells[] = 'accuracy';

    foreach ($books as $book) {
        $cells[] = $book;
        $cells[] = 'frequency';
        $cells[] = 'image';
    }

    return implode("\t", $cells);
}

function write_line($volume, $page, $books, $entry, $first_word_frequency, $book_name, $accuracy, $selected_book)
{
    list($first_word, $frequency, $check) = set_check($books, $entry, $first_word_frequency, $selected_book);
    $image = set_image($entry, $selected_book);

    $cells[] = $page;
    $cells[] = $first_word;
    $cells[] = $image;
    $cells[] = $volume;
    $cells[] = $frequency;
    $cells[] = $check;
    $cells[] = $book_name;
    $cells[] = $accuracy;

    foreach ($books as $book) {
        if (isset($entry[$book])) {
            $first_word = $entry[$book]['first_word'];
            $image = $entry[$book]['image'];
            $frequency = $first_word_frequency[$first_word];
        } else {
            $first_word = null;
            $image = null;
            $frequency = null;
        }

        $cells[] = $first_word;
        $cells[] = $frequency;
        $cells[] = $image;
    }

    return implode("\t", $cells);
}

@list(, $volume) = $argv;

$volumes = extract_volumes();

if (! in_array($volume, $volumes)) {
    $volumes = implode(', ', $volumes);
    die("invalid volume, expecting one of $volumes");
}

if (! isset($volumes_first_page[$volume])) {
    die('first page not set');
}

compare_first_words($volume, $volumes_first_page[$volume]);