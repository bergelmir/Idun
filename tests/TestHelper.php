<?php

error_reporting(E_ALL);
date_default_timezone_set('Europe/Berlin');

$rootDirectory    = realpath(dirname(dirname(__FILE__)));
$libraryDirectory = $rootDirectory . '/library';
$testsDirectory   = $rootDirectory . '/tests';

set_include_path(implode(PATH_SEPARATOR, array(
    $libraryDirectory,
    $testsDirectory,
    get_include_path()
)));

if (is_readable($testsDirectory . DIRECTORY_SEPARATOR . 'TestConfiguration.php')) {
    require_once $testsDirectory . DIRECTORY_SEPARATOR . 'TestConfiguration.php';
} else {
    require_once $testsDirectory . DIRECTORY_SEPARATOR . 'TestConfiguration.php.dist';
}

unset($rootDirectory, $libraryDirectory, $testsDirectory);
