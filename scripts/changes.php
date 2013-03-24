<?php
/**
 * Dicfro
 *
 * Dictionary changes analysis functions
 *
 * PHP 5
 *
 * @author    Michel Corne <mcorne@yahoo.com>
 * @copyright 2008-2012 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

/**
 * Compare two files
 *
 * @param string $file1
 * @param string $file2
 * @return boolean
 */
function compare_files($file1, $file2)
{
    $content1 = read_file($file1);
    $content2 = read_file($file2);

    return $content1 === $content2;
}

/**
 * Compares two files hashes
 *
 * @param string $dictionary
 * @param string $file1
 * @param string $file2
 * @return boolean
 */
function compare_files_hash($dictionary, $file1, $file2)
{
    $content1 = get_file_hash($dictionary, $file1);
    $content2 = get_file_hash($dictionary, $file2);

    return $content1 === $content2;
}

/**
 * Compares two txt files
 *
 * @param string $dictionary
 * @param string $current_txt_file
 * @param string $previous_txt_file
 * @return boolean
 */
function compare_txt_files($dictionary, $current_txt_file, $previous_txt_file)
{
    $current_txt_path  = set_data_file_path($dictionary, $current_txt_file);
    $previous_txt_path = set_sources_file_path($dictionary, $previous_txt_file);

    return compare_files($current_txt_path, $previous_txt_path);
}

/**
 * Returns the dictionary changes congiguration
 *
 * @param string $dictionary
 */
function get_changes_config($dictionary)
{
    $path = get_changes_directory($dictionary) . '/config.php';

    if (! file_exists($path)) {
        die("file missing $path");
    }

    return require $path;
}

/**
 * Returns the dictionary changes directory
 *
 * @param string $dictionary
 * @return string
 */
function get_changes_directory($dictionary)
{
    return get_data_directory($dictionary) . '/changes';
}

/**
 * Returns the dictionary data directory
 *
 * @param string $dictionary
 * @return string
 */
function get_data_directory($dictionary)
{
    return __DIR__ . "/../application/data/$dictionary";
}

/**
 * Returns the list of dictionaries subject to changes (listed in the sources directory)
 *
 * @return array
 */
function get_dictionaries()
{
    $files = glob(__DIR__ . '/../sources/*', GLOB_ONLYDIR);

    return array_map('basename', $files);
}

/**
 * Returns the hash of a source file
 *
 * The hash is calculated from the source file the first time and stored in the changes directory.
 *
 * @param string $dictionary
 * @param string $file
 * @return string
 */
function get_file_hash($dictionary, $file)
{
    $hash_path = set_hash_file_path($dictionary, $file);

    if (file_exists($hash_path)) {
        $hash = read_file($hash_path);

    } else {
        $path = set_sources_file_path($dictionary, $file);

        if (! file_exists($path)) {
            exit("file missing $path");
        }

        if (! $hash = @md5_file($path)) {
            exit("cannot hash $path");
        }

        write_file($hash_path, $hash);
    }

    return $hash;
}

/**
 * Returns the source files directory
 *
 * @param string $dictionary
 * @return string
 */
function get_sources_directory($dictionary)
{
    return __DIR__ . "/../sources/$dictionary";
}

/**
 * Checks if the dictionary is in the list of dictionaries subject to changes
 *
 * @param string $dictionary
 */
function is_valid_dictionary($dictionary)
{
    $dictionaries = get_dictionaries();

    if (empty($dictionary) or ! in_array($dictionary, $dictionaries)) {
        die('you must choose a valid dictionary among: '. implode(', ', $dictionaries));
    }
}

/**
 * Returns the content of a file
 *
 * @param string $file
 * @return string
 */
function read_file($file)
{
    if (! $content = @file_get_contents($file)) {
        exit("cannot read $file");
    }

    return $content;
}

/**
 * Sets the path of a file in the data dictionary
 *
 * @param string $dictionary
 * @param string $file
 * @return string
 */
function set_data_file_path($dictionary, $file)
{
    return sprintf('%s/%s', get_data_directory($dictionary), $file);
}

/**
 * Sets the path of a hash file
 *
 * @param string $dictionary
 * @param string $file
 * @return string
 */
function set_hash_file_path($dictionary, $file)
{
    list($version) = explode('/', $file);

    return sprintf('%s/%s/%s.md5', get_changes_directory($dictionary), $version, basename($file));
}

/**
 * Sets the path of a source file
 *
 * @param string $dictionary
 * @param string $file
 * @return string
 */
function set_sources_file_path($dictionary, $file)
{
    return sprintf('%s/%s', get_sources_directory($dictionary), $file);
}

/**
 * Writes content to a file
 *
 * @param string $file
 * @param string $content
 */
function write_file($file, $content)
{
    if (! @file_put_contents($file, $content)) {
        exit("cannot write $file");
    }
}