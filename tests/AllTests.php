<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PHPUnit 3.2 AllTests suite for the Services_Akismet package.
 *
 * These tests require the PHPUnit 3.2 package to be installed. PHPUnit is
 * installable using PEAR. See the
 * {@link http://www.phpunit.de/pocket_guide/3.2/en/installation.html manual}
 * for detailed installation instructions.
 *
 * This test suite follows the PEAR AllTests conventions as documented at
 * {@link http://cvs.php.net/viewvc.cgi/pear/AllTests.php?view=markup}.
 *
 * Note:
 *
 *   These tests require a private API key from Wordpress.com. Enter your API
 *   key in config.php to run these tests. If config.php is missing, these
 *   tests will refusse to run. A sample configuration is provided in the file
 *   config.php.dist.
 *
 * LICENSE:
 *
 * This library is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation; either version 2.1 of the
 * License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @category  Services
 * @package   Services_Akismet
 * @author    Michael Gauthier <mike@silverorange.com>
 * @copyright 2008 silverorange
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Services_Akismet
 */

chdir(dirname(__FILE__));

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Services_Akismet_AllTests::main');
}

require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'SocketsTest.php';
require_once 'StreamsTest.php';
require_once 'CurlTest.php';


/**
 * AllTests suite testing Services_Akismet
 *
 * @category  Services
 * @package   Services_Akismet
 * @author    Michael Gauthier <mike@silverorange.com>
 * @copyright 2008 silverorange
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @link      http://pear.php.net/package/Services_Akismet
 */
class Services_Akismet_AllTests
{
    // {{{ main()

    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    // }}}
    // {{{ suite()

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Services_Akismet Tests');
        $suite->addTestSuite('SocketsTest');
        $suite->addTestSuite('StreamsTest');
        $suite->addTestSuite('CurlTest');
        return $suite;
    }

    // }}}
}

if (PHPUnit_MAIN_METHOD == 'Services_Akismet_AllTests::main') {
    Services_Akismet_AllTests::main();
}

?>
