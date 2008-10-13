<?php

/**
 * Base test case class
 */
require_once 'TestCase.php';

/**
 * Tests the cURL HTTP client implementation of Services_Akismet
 *
 * @category  Services
 * @package   Services_Akismet
 * @author    Michael Gauthier <mike@silverorange.com>
 * @copyright 2008 silverorange
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @link      http://pear.php.net/package/Services_Akismet
 */
class CurlTest extends Services_Akismet_TestCase
{
	// {{{ getHttpClientImplementation()

	protected function getHttpClientImplementation()
	{
		return 'curl';
	}

	// }}}
}

?>
