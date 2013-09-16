<?php
/**
 * Dicfro
 *
 * Merges volumes entries
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

$merged = '';

foreach (range(1, 7) as $volume) {
    $file = __DIR__ . "/$volume/index.csv";

    if (file_exists($file)) {
        $entries = file_get_contents($file);

        if ($volume != 1) {
            // removes first line (headers) except for the first volume
            list(, $entries) = explode("\n", $entries, 2);
        }

        $merged .= $entries;
    }
}

$file = "index.csv";
file_put_contents(__DIR__ . "/$file", $merged);
echo "entries of volumes merged into $file";