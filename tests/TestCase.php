<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PHPUnit3.2 test framework script for the Services_Akismet package.
 *
 * These tests require the PHPUnit 3.2 package to be installed. PHPUnit is
 * installable using PEAR. See the
 * {@link http://www.phpunit.de/pocket_guide/3.2/en/installation.html manual}
 * for detailed installation instructions.
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
 * @copyright 2008-2011 silverorange
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Services_Akismet
 */

/**
 * Akismet class to test
 */
require_once 'Services/Akismet.php';

/**
 * Akismet comment class
 */
require_once 'Services/Akismet/Comment.php';

/**
 * Base class for testing Services_Akismet
 *
 * @category  Services
 * @package   Services_Akismet
 * @author    Michael Gauthier <mike@silverorange.com>
 * @copyright 2008-2011 silverorange
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @link      http://pear.php.net/package/Services_Akismet
 */
abstract class Services_Akismet_TestCase extends PHPUnit_Framework_TestCase
{
    // {{{ protected properties

    /**
     * @var Services_Akismet
     */
    protected $akismet = null;

    // }}}
    // {{{ setUp()

    public function setUp()
    {
        $configFilename = dirname(__FILE__).'/config.php';

        if (!file_exists($configFilename)) {
            $this->markTestSkipped('Unit test configuration is missing. ' .
                'Please read the documentation in TestCase.php and create a ' .
                'configuration file. See the configuration in ' .
                '\'config.php.dist\' for an example.');
        }

        include $configFilename;

        if (   !isset($GLOBALS['Services_Akismet_Unittest_Config'])
            || !is_array($GLOBALS['Services_Akismet_Unittest_Config'])
            || !isset($GLOBALS['Services_Akismet_Unittest_Config']['blogUri'])
            || !isset($GLOBALS['Services_Akismet_Unittest_Config']['apiKey'])
        ) {
            $this->markTestSkipped('Unit test configuration is incorrect. ' .
                'Please read the documentation in TestCase.php and fix the ' .
                'configuration file. See the configuration in ' .
                '\'config.php.dist\' for an example.');
        }

        $this->akismet = new Services_Akismet(
            $GLOBALS['Services_Akismet_Unittest_Config']['blogUri'],
            $GLOBALS['Services_Akismet_Unittest_Config']['apiKey'],
            $this->getHttpClientImplementation());
    }

    // }}}
    // {{{ tearDown()

    public function tearDown()
    {
        unset($this->akismet);
    }

    // }}}
    // {{{ getHttpClientImplementation()

    abstract protected function getHttpClientImplementation();

    // }}}

    // tests
    // {{{ testIsSpam()

    public function testIsSpam()
    {
        $spamComment = new Services_Akismet_Comment();
        $spamComment->setAuthor('viagra-test-123');
        $spamComment->setAuthorEmail('test@example.com');
        $spamComment->setAuthorUri('http://example.com/');
        $spamComment->setContent('Spam, I am.');
        $spamComment->setUserIp('127.0.0.1');
        $spamComment->setUserAgent('Services_Akismet unit tests');
        $spamComment->setHttpReferer('http://example.com/');

        $isSpam = $this->akismet->isSpam($spamComment);
        $this->assertTrue($isSpam);

        $comment = new Services_Akismet_Comment();
        $comment->setAuthor('Services_Akismet unit tests');
        $comment->setAuthorEmail('test@example.com');
        $comment->setAuthorUri('http://example.com/');
        $comment->setContent('Hello, World!');
        $comment->setUserIp('127.0.0.1');
        $comment->setUserAgent('Services_Akismet unit tests');
        $comment->setHttpReferer('http://example.com/');

        $isSpam = $this->akismet->isSpam($comment);
        $this->assertFalse($isSpam);
    }

    // }}}
    // {{{ testSubmitSpam()

    public function testSubmitSpam()
    {
        $spamComment = new Services_Akismet_Comment();
        $spamComment->setAuthor('viagra-test-123');
        $spamComment->setAuthorEmail('test@example.com');
        $spamComment->setAuthorUri('http://example.com/');
        $spamComment->setContent('Spam, I am.');
        $spamComment->setUserIp('127.0.0.1');
        $spamComment->setUserAgent('Services_Akismet unit tests');
        $spamComment->setHttpReferer('http://example.com/');

        $this->akismet->submitSpam($spamComment);
    }

    // }}}
    // {{{ testSubmitFalsePositive()

    public function testSubmitFalsePositive()
    {
        $comment = new Services_Akismet_Comment();
        $comment->setAuthor('Services_Akismet unit tests');
        $comment->setAuthorEmail('test@example.com');
        $comment->setAuthorUri('http://example.com/');
        $comment->setContent('Hello, World!');
        $comment->setUserIp('127.0.0.1');
        $comment->setUserAgent('Services_Akismet unit tests');
        $comment->setHttpReferer('http://example.com/');

        $this->akismet->submitFalsePositive($comment);
    }

    // }}}
    // {{{ testInvalidApiKeyException()

    /**
     * @expectedException Services_Akismet_InvalidApiKeyException
     */
    public function testInvalidApiKeyException()
    {
        $badApiKey = 'asdf';
        $akismet = new Services_Akismet(
            $GLOBALS['Services_Akismet_Unittest_Config']['blogUri'],
            $badApiKey, $this->getHttpClientImplementation());
    }

    // }}}
}

?>
