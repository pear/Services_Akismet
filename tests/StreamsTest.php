<?php

/**
 * Base test case class
 */
require_once 'TestCase.php';

/**
 * Tests the streams HTTP client implementation of Services_Akismet
 *
 * @category  Services
 * @package   Services_Akismet
 * @author    Michael Gauthier <mike@silverorange.com>
 * @copyright 2008 silverorange
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @link      http://pear.php.net/package/Services_Akismet
 */
class StreamsTest extends TestCase
{
	// {{{ getHttpClientImplementation()

	protected function getHttpClientImplementation()
	{
		return 'streams';
	}

	// }}}
}

?>
