<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This is the package.xml generator for Services_Akismet
 *
 * PHP version 5
 *
 * LICENSE:
 *
 * Copyright (c) 2007-2008 silverorange
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category  Services
 * @package   Services_Akismet
 * @author    Michael Gauthier <mike@silverorange.com>
 * @copyright 2008 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @link      http://pear.php.net/package/Services_Akismet
 */

require_once 'PEAR/PackageFileManager2.php';
PEAR::setErrorHandling(PEAR_ERROR_DIE);

$release_version = '0.5.0';
$release_state   = 'beta';
$release_notes   =
    "Cleaned up example code in documentation. Marked as package as beta. " .
    "no code changes since 0.4.1.";

$description =
    "This package provides an object-oriented interface to the Akismet REST " .
    "API. Akismet is used to detect and to filter spam comments posted on " .
    "weblogs. Though the use of Akismet is not specific to Wordpress, you " .
    "will need a Wordpress API key from http://wordpress.com/api-keys/ to " .
    "use this package.\n\n" .
    "Akismet is free for personal use and a license may be purchased for " .
    "commercial or high-volume applications.\n\n" .
    "This package is derived from the miPHP Akismet class written by Bret " .
    "Kuhns for use in PHP 4. This package requires PHP 5.2.1.";

$package = new PEAR_PackageFileManager2();

$package->setOptions(array(
    'filelistgenerator'      => 'cvs',
    'simpleoutput'           => true,
    'baseinstalldir'         => '/Services',
    'packagedirectory'       => './',
    'dir_roles'              => array(
        'Akismet'            => 'php',
        'Akismet/HttpClient' => 'php',
        'tests'              => 'test'
    ),
    'exceptions'             => array(
        'Akismet.php'        => 'php'
    ),
));

$package->setPackage('Services_Akismet');
$package->setSummary('PHP client for the Akismet REST API');
$package->setDescription($description);
$package->setChannel('pear.php.net');
$package->setPackageType('php');
$package->setLicense('MIT',
    'http://www.opensource.org/licenses/mit-license.html');

$package->setNotes($release_notes);
$package->setReleaseVersion($release_version);
$package->setReleaseStability($release_state);
$package->setAPIVersion('0.5.0');
$package->setAPIStability('beta');

$package->addIgnore('package.php');
$package->addIgnore('package-2.0.xml');
$package->addIgnore('*.tgz');

$package->addMaintainer('lead', 'gauthierm', 'Mike Gauthier',
    'mike@silverorange.com');

$package->addReplacement('Akismet.php', 'package-info', '@API-VERSION@',
    'api-version');

$package->addReplacement('Akismet.php', 'package-info', '@NAME@',
    'name');

$package->setPhpDep('5.2.1');
$package->addExtensionDep('optional', 'curl');
$package->setPearinstallerDep('1.4.0');
$package->generateContents();

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
    $package->writePackageFile();
} else {
    $package->debugPackageFile();
}

?>
