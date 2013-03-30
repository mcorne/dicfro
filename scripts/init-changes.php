<?php
/**
 * Dicfro
 *
 * Dictionary changes initialization
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

if (isset($config['previous-txt-file'])) {
    $messages[] = sprintf('not expecting previous txt entry in config: %s', $config['previous-txt-file']);

} else if (! isset($config['new-txt-file'])) {
    $messages[] = 'no txt to hash';

} else {
    get_file_hash($dictionary, $config['new-txt-file']);
    $messages[] = sprintf('txt hashed: %s', $config['new-txt-file']);
}

if (isset($config['previous-img-directory'])) {
    $messages[] = sprintf('not expecting previous img entry in config: %s', $config['previous-img-directory']);

} else if (! isset($config['new-img-directory'])) {
    $messages[] = 'no img to hash';

} else {
    get_directory_hash($dictionary, $config['new-img-directory']);
    $messages[] = sprintf('img hash: %s', $config['new-img-directory']);
}

display_messages($messages);