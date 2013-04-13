<?php
/**
 * Dicfro
 *
 * First word and page extraction from the Petit Larousse noms propres HTML file
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

// $input   = __DIR__ . '/../../../sources/petit-larousse-np/larousse_petit_1906_e_djvu.xml';
// $pattern = '~<PARAM name="PAGE" value="larousse_petit_1906_e_0(\d\d\d).djvu"/>~';

$input   = __DIR__ . '/../../../sources/petit-larousse-np/larousse_petit_1906_f_djvu.xml';
$pattern = '~<PARAM name="PAGE" value="larousse_petit_1906_f_0(\d\d\d).djvu"/>~';

$lines = file($input, FILE_SKIP_EMPTY_LINES);
$entries = '';
$first_word = null;

foreach ($lines as $line) {
    $line = str_replace('fc', 'É', $line);
    $line = str_replace('fi', 'É', $line);

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

        if (! isset($expected) ) {
            $expected = $image;
        }

        while ($image > $expected) {
           // adds blank entry if page missing
           $entries .= "$expected\t\n";
           $expected++;
        }

        $entries .= "$image\t";
        $expected++;

    } else if ($first_word === '' and preg_match('~<WORD coords="[\d,]+">(\p{Lu}{3})</WORD>~u', $line, $match)) {
        list(, $first_word) = $match;
        $entries .= "$first_word\n";
    }
}

$output = basename($input, '.xml');
file_put_contents(__DIR__ . "/$output.csv", $entries);