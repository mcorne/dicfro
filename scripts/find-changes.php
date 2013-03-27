<?php
/**
 * Dicfro
 *
 * Dictionary changes analysis between two versions
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2013 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

require_once 'changes.php';

@list(, $dictionary) = $argv;
is_valid_dictionary($dictionary);

$config = get_changes_config($dictionary);

if (! isset($config['previous-zip-file']) or ! isset($config['new-zip-file'])) {
    $messages[] = 'no previous or new zip to check';

} else if (compare_files_hash($dictionary, $config['previous-zip-file'], $config['new-zip-file'])) {
    $messages[] = sprintf('no change for zip: %s = %s', $config['previous-zip-file'], $config['new-zip-file']);
}

if (! isset($config['previous-txt-file']) or ! isset($config['new-txt-file'])) {
    $messages[] = 'no previous or new txt to check';

} else if (compare_files_hash($dictionary, $config['previous-txt-file'], $config['new-txt-file'])) {
    $messages[] = sprintf('no change for txt: %s = %s', $config['previous-txt-file'], $config['new-txt-file']);

} else {
    $messages[] = sprintf('txt changed: %s <> %s', $config['previous-txt-file'], $config['new-txt-file']);

    if (! isset($config['current-txt-file'])) {
        $messages[] = 'no current txt to check';

    } else if (! compare_txt_files($dictionary, $config['current-txt-file'], $config['previous-txt-file'])) {
        $messages[] = sprintf('txt had been fixed: %s <> %s', $config['current-txt-file'], $config['previous-txt-file']);
        $messages[] = sprintf('apply fixes to %s', $config['new-txt-file']);
    }
}

if (! isset($config['previous-img-directory']) or ! isset($config['new-img-directory'])) {
    $messages[] = 'no previous or new img to check';

} else if (! $differences = compare_directory_hash($dictionary, $config['previous-img-directory'], $config['new-img-directory'])) {
    $messages[] = sprintf('no change for img: %s = %s', $config['previous-img-directory'], $config['new-img-directory']);

} else {
    $messages[] = sprintf('img changed: %s <> %s', $config['previous-img-directory'], $config['new-img-directory']);

    if (isset($differences['added-files'])) {
        $messages[] = sprintf('new img files: %s', implode(', ', $differences['added-files']));
    }

    if (isset($differences['removed-files'])) {
        $messages[] = sprintf('removed img files: %s', implode(', ', $differences['removed-files']));
    }

    if (isset($differences['changed-files'])) {
        $messages[] = sprintf('changed img files: %s', implode(', ', $differences['changed-files']));
    }
}

display_messages($messages);