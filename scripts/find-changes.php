<?php
/**
 * Dicfro
 *
 * Dictionary changes analysis between two versions
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2012 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'changes.php';

@list(, $dictionary) = $argv;
is_valid_dictionary($dictionary);

$config = get_changes_config($dictionary);

if (compare_files_hash($dictionary, $config['previous-zip-file'], $config['new-zip-file'])) {
    echo sprintf('no change for zip: %s = %s', $config['previous-zip-file'], $config['new-zip-file']);
    exit;
}

if (compare_files_hash($dictionary, $config['previous-txt-file'], $config['new-txt-file'])) {
    echo sprintf('no change for txt: %s = %s', $config['previous-txt-file'], $config['new-txt-file']);

} else {
    echo sprintf('txt changed: %s <> %s', $config['previous-txt-file'], $config['new-txt-file']);

    if (! compare_txt_files($dictionary, $config['current-txt-file'], $config['previous-txt-file'])) {
        echo "\n" . sprintf('txt had been fixed: %s <> %s', $config['current-txt-file'], $config['previous-txt-file']);
        echo "\n" . sprintf('apply fixes to %s', $config['new-txt-file']);
    }
}