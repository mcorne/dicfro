<?php
/**
 * Dicfro
 *
 * First word and page extraction from the Petit Larousse HTML file
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

$lines = file(__DIR__ . '/../../../sources/petit-larousse/Telechargement en mode texte.htm', FILE_SKIP_EMPTY_LINES);
$pattern = '~<span class="PAG_0000(\d\d\d\d)_ST\d+">([\p{Lu}*-]{4,})</span>~u';
$entries = '';

foreach ($lines as $line) {
    if (preg_match($pattern, $line, $match)) {
        // first word identified
        list(, $image, $first_word) = $match;
        $image = (int) $image;
        $first_word = trim($first_word);

        if (isset($image)) {
            if (! isset($expected) or $image <= 6) {
                // no check before page 6
                $expected = $image;
            }

           while ($image > $expected) {
               // adds blank entry if page missing
               $entries .= "$expected\t\n";
               $expected++;
           }
        }

        $entries .= "$image\t$first_word\n";
        $expected++;
    }
}

file_put_contents(__DIR__ . '/entries.csv', $entries);