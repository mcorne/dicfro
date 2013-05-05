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

$files = glob(__DIR__ . '/*', GLOB_ONLYDIR);
$volumes = array_map('basename', $files);
sort($volumes, SORT_NUMERIC);

$merged = '';

foreach ($volumes as $volume) {
    $file = __DIR__ . "/$volume/encyclopedie-larousse-$volume.csv";

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
printf("entries of volumes %u-%u merged into %s\n", $volumes[0], end($volumes), $file);