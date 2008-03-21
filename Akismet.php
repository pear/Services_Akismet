<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Services_Akismet is a package to use Akismet spam-filtering from PHP
 *
 * This package provides an object-oriented interface to the Akismet REST
 * API. Akismet is used to detect and to filter spam comments posted on
 * weblogs. Though the use of Akismet is not specific to Wordpress, you will
 * need a Wordpress API key from {@link http://wordpress.com} to use this
 * package.
 *
 * Akismet is free for personal use and a license may be purchased for
 * commercial or high-volume applications.
 *
 * This package is derived from the miPHP Akismet class written by Bret Kuhns
 * for use in PHP 4. This package requires PHP 5.2.1.
 *
 * Example usage:
 * <code>
 *
 * /**
 *  * Handling user-posted comments
 *  {@*}
 *
 * $comment = new Services_Akismet_Comment();
 * $comment->setAuthor('Test Author');
 * $comment->setAuthorEmail('test@example.com');
 * $comment->setAuthorUri('http://example.com/');
 * $comment->setContent('Hello, World!');
 *
 * try {
 *     $api_key = 'AABBCCDDEEFF';
 *     $akismet = new Services_Akismet('http://blog.example.com/', $api_key);
 *     if ($akismet->isSpam($comment)) {
 *         // rather than simply ignoring the spam comment, it is recommended
 *         // to save the comment and mark it as spam in case the comment is a
 *         // false positive.
 *     } else {
 *         // save comment as normal comment
 *     }
 * } catch (Services_Akismet_InvalidApiKeyException $key_exception) {
 *     echo 'Invalid API key!';
 * } catch (Services_Akismet_CommunicationException $comm_exception) {
 *     echo 'Error communicating with Akismet API server: ' .
 *         $com_exception->getMessage();
 * } catch (Services_Akismet_InvalidCommentException $comment_exception) {
 *     echo 'Specified comment is missing one or more required fields.' .
 *         $comment_exception->getMessage();
 * }
 *
 * /**
 *  * Submitting a comment as known spam
 *  {@*}
 *
 * $comment = new Services_Akismet_Comment();
 * $comment->setAuthor('Test Author');
 * $comment->setAuthorEmail('test@example.com');
 * $comment->setAuthorUri('http://example.com/');
 * $comment->setContent('Hello, World!');
 *
 * try {
 *     $api_key = 'AABBCCDDEEFF';
 *     $akismet = new Services_Akismet('http://blog.example.com/', $api_key);
 *     $akismet->submitSpam($comment);
 * } catch (Services_Akismet_InvalidApiKeyException $key_exception) {
 *     echo 'Invalid API key!';
 * } catch (Services_Akismet_CommunicationException $comm_exception) {
 *     echo 'Error communicating with Akismet API server: ' .
 *         $com_exception->getMessage();
 * } catch (Services_Akismet_InvalidCommentException $comment_exception) {
 *     echo 'Specified comment is missing one or more required fields.' .
 *         $comment_exception->getMessage();
 * }
 *
 * /**
 *  * Submitting a comment as a false positive
 *  {@*}
 *
 * $comment = new Services_Akismet_Comment();
 * $comment->setAuthor('Test Author');
 * $comment->setAuthorEmail('test@example.com');
 * $comment->setAuthorUri('http://example.com/');
 * $comment->setContent('Hello, World!');
 *
 * try {
 *     $api_key = 'AABBCCDDEEFF';
 *     $akismet = new Services_Akismet('http://blog.example.com/', $api_key);
 *     $akismet->submitFalsePositive($comment);
 * } catch (Services_Akismet_InvalidApiKeyException $key_exception) {
 *     echo 'Invalid API key!';
 * } catch (Services_Akismet_CommunicationException $comm_exception) {
 *     echo 'Error communicating with Akismet API server: ' .
 *         $com_exception->getMessage();
 * } catch (Services_Akismet_InvalidCommentException $comment_exception) {
 *     echo 'Specified comment is missing one or more required fields.' .
 *         $comment_exception->getMessage();
 * }
 *
 * </code>
 *
 * PHP version 5
 *
 * LICENSE:
 *
 * Copyright (c) 2007-2008 Bret Kuhns, silverorange
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
 * @author    Bret Kuhns
 * @copyright 2007-2008 Bret Kuhns, 2008 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Services_Akismet
 * @link      http://akismet.com/
 * @link      http://akismet.com/development/api/
 * @link      http://www.miphp.net/blog/view/php4_akismet_class
 */

/**
 * Comment class definition.
 */
require_once 'Services/Akismet/Comment.php';

/**
 * Simple HTTP client for accessing the Akismet API.
 */
require_once 'Services/Akismet/HttpClient.php';

/**
 * Exception thrown when an invalid API key is used.
 */
require_once 'Services/Akismet/InvalidApiKeyException.php';

// {{{ class Services_Akismet

/**
 * Class to use Akismet API from PHP
 *
 * Example usage:
 * <code>
 *
 * /**
 *  * Handling user-posted comments
 *  {@*}
 *
 * $comment = new Services_Akismet_Comment();
 * $comment->setAuthor('Test Author');
 * $comment->setAuthorEmail('test@example.com');
 * $comment->setAuthorUri('http://example.com/');
 * $comment->setContent('Hello, World!');
 *
 * try {
 *     $api_key = 'AABBCCDDEEFF';
 *     $akismet = new Services_Akismet('http://blog.example.com/', $api_key);
 *     if ($akismet->isSpam($comment)) {
 *         // rather than simply ignoring the spam comment, it is recommended
 *         // to save the comment and mark it as spam in case the comment is a
 *         // false positive.
 *     } else {
 *         // save comment as normal comment
 *     }
 * } catch (Services_Akismet_InvalidApiKeyException $key_exception) {
 *     echo 'Invalid API key!';
 * } catch (Services_Akismet_CommunicationException $comm_exception) {
 *     echo 'Error communicating with Akismet API server: ' .
 *         $com_exception->getMessage();
 * } catch (Services_Akismet_InvalidCommentException $comment_exception) {
 *     echo 'Specified comment is missing one or more required fields.' .
 *         $comment_exception->getMessage();
 * }
 *
 * /**
 *  * Submitting a comment as known spam
 *  {@*}
 *
 * $comment = new Services_Akismet_Comment();
 * $comment->setAuthor('Test Author');
 * $comment->setAuthorEmail('test@example.com');
 * $comment->setAuthorUri('http://example.com/');
 * $comment->setContent('Hello, World!');
 *
 * try {
 *     $api_key = 'AABBCCDDEEFF';
 *     $akismet = new Services_Akismet('http://blog.example.com/', $api_key);
 *     $akismet->submitSpam($comment);
 * } catch (Services_Akismet_InvalidApiKeyException $key_exception) {
 *     echo 'Invalid API key!';
 * } catch (Services_Akismet_CommunicationException $comm_exception) {
 *     echo 'Error communicating with Akismet API server: ' .
 *         $com_exception->getMessage();
 * } catch (Services_Akismet_InvalidCommentException $comment_exception) {
 *     echo 'Specified comment is missing one or more required fields.' .
 *         $comment_exception->getMessage();
 * }
 *
 * /**
 *  * Submitting a comment as a false positive
 *  {@*}
 *
 * $comment = new Services_Akismet_Comment();
 * $comment->setAuthor('Test Author');
 * $comment->setAuthorEmail('test@example.com');
 * $comment->setAuthorUri('http://example.com/');
 * $comment->setContent('Hello, World!');
 *
 * try {
 *     $api_key = 'AABBCCDDEEFF';
 *     $akismet = new Services_Akismet('http://blog.example.com/', $api_key);
 *     $akismet->submitFalsePositive($comment);
 * } catch (Services_Akismet_InvalidApiKeyException $key_exception) {
 *     echo 'Invalid API key!';
 * } catch (Services_Akismet_CommunicationException $comm_exception) {
 *     echo 'Error communicating with Akismet API server: ' .
 *         $com_exception->getMessage();
 * } catch (Services_Akismet_InvalidCommentException $comment_exception) {
 *     echo 'Specified comment is missing one or more required fields.' .
 *         $comment_exception->getMessage();
 * }
 *
 * </code>
 *
 * @category  Services
 * @package   Services_Akismet
 * @author    Michael Gauthier <mike@silverorange.com>
 * @author    Bret Kuhns
 * @copyright 2007-2008 Bret Kuhns, 2008 silverorange
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @link      http://pear.php.net/package/Services_Akismet
 */
class Services_Akismet
{
    // {{{ private properties

    /**
     * The port to use to connect to the Akismet API server
     *
     * Defaults to 80.
     *
     * @var integer
     */
    private $_api_port    = 80;

    /**
     * The Akismet API server name
     *
     * Defaults to 'rest.akismet.com'.
     *
     * @var string
     */
    private $_api_server  = 'rest.akismet.com';

    /**
     * The Akismet API version to use
     *
     * Defaults to '1.1'.
     *
     * @var string
     */
    private $_api_version = '1.1';

    /**
     * The URI of the webblog for which Akismet services will be used
     *
     * @var string
     *
     * @see Services_Akismet::__construct()
     */
    private $_blog_uri = '';

    /**
     * The Wordpress API key to use to access Akismet services
     *
     * @var string
     *
     * @see Services_Akismet::__construct()
     */
    private $_api_key  = '';


    private $_http_client = null;

    // }}}
    // {{{ __construct()

    /**
     * Creates a new Akismet object
     *
     * @param string $blog_uri                   the URI of the webblog
     *                                           homepage.
     * @param string $api_key                    the Wordpress API key to use
     *                                           for Akismet services.
     * @param string $http_client_implementation optional. The name of the HTTP
     *                                           client implementation to use.
     *                                           This must be one of the
     *                                           implementations specified by
     *                                           {@link Services_Akismet_HttpClient}.
     *                                           If not specified, defaults to
     *                                           'sockets'.
     *
     * @throws Services_Akismet_InvalidApiKeyException if the provided
     *         Wordpress API key is not valid.
     *
     * @throws Services_Akismet_CommunicationException if there is an error
     *         communicating with the Akismet API server.
     *
     * @throws PEAR_Exception if the specified HTTP client implementation may
     *         not be used with this PHP installation or if the specified HTTP
     *         client implementation does not exist.
     */
    public function __construct($blog_uri, $api_key,
        $http_client_implementation = 'sockets')
    {
        $this->_blog_uri = $blog_uri;
        $this->_api_key  = $api_key;

        // build http client
        $this->setHttpClientImplementation($http_client_implementation);

        // make sure the API key is valid
        if (!$this->_isApiKeyValid($this->_api_key)) {
            throw new Services_Akismet_InvalidApiKeyException('The specified ' .
                'Wordpress API key is not valid. Key used was: "' .
                $this->_api_key . '".', 0, $this->_api_key);
        }
    }

    // }}}
    // {{{ isSpam()

    /**
     * Checks whether or not a comment is spam
     *
     * @param Services_Akismet_Comment $comment the comment to check.
     *
     * @return boolean true if the comment is spam and false if it is not.
     *
     * @throws Services_Akismet_CommunicationException if there is an error
     *         communicating with the Akismet API server.
     *
     * @throws Services_Akismet_InvalidCommentException if the specified comment
     *         is missing required fields.
     */
    public function isSpam(Services_Akismet_Comment $comment)
    {
        $post_data = $comment->getPostData();
        $post_data = 'blog=' . urlencode($this->_blog_uri) . '&' . $post_data;
        $response  = $this->_request('comment-check', $post_data);
        return ($response == 'true');
    }

    // }}}
    // {{{ submitSpam()

    /**
     * Submits a comment as an unchecked spam to the Akismet server
     *
     * Use this method to submit comments that are spam but are not detected
     * by Akismet.
     *
     * @param Services_Akismet_Comment $comment the comment to submit as spam.
     *
     * @return void
     *
     * @throws Services_Akismet_CommunicationException if there is an error
     *         communicating with the Akismet API server.
     *
     * @throws Services_Akismet_InvalidCommentException if the specified comment
     *         is missing required fields.
     */
    public function submitSpam(Services_Akismet_Comment $comment)
    {
        $post_data = $comment->getPostData();
        $post_data = 'blog=' . urlencode($this->_blog_uri) . '&' . $post_data;
        $this->_request('submit-spam', $post_data);
    }

    // }}}
    // {{{ submitFalsePositive()

    /**
     * Submits a false-positive comment to the Akismet server
     *
     * Use this method to submit comments that are detected as spam but are not
     * actually spam.
     *
     * @param Services_Akismet_Comment $comment the comment that is
     *                                          <em>not</em> spam.
     *
     * @return void
     *
     * @throws Services_Akismet_CommunicationException if there is an error
     *         communicating with the Akismet API server.
     *
     * @throws Services_Akismet_InvalidCommentException if the specified comment
     *         is missing required fields.
     */
    public function submitFalsePositive(Services_Akismet_Comment $comment)
    {
        $post_data = $comment->getPostData();
        $post_data = 'blog=' . urlencode($this->_blog_uri) . '&' . $post_data;
        $this->_request('submit-ham', $post_data);
    }

    // }}}
    // {{{ setHttpClientImplementation()

    /**
     * Sets the HTTP client implementation to use for this Akismet object
     *
     * Available implementations are:
     * - sockets
     * - streams
     * - curl
     *
     * @param string $implementation the name of the HTTP client implementation
     *                               to use. This must be one of the
     *                               implementations specified by
     *                               {@link Services_Akismet_HttpClient}.
     *
     * @throws PEAR_Exception if the specified HTTP client implementation may
     *         not be used with this PHP installation or if the specified HTTP
     *         client implementation does not exist.
     *
     * @see Services_Akismet_HttpClient
     */
    public function setHttpClientImplementation($implementation)
    {
        $services_akismet_name    = '@NAME@';
        $services_akismet_version = '@API-VERSION@';

        $user_agent = sprintf('%s/%s | Akismet/%s',
            $services_akismet_name,
            $services_akismet_version,
            $this->_api_version);

        $this->_http_client =
            Services_Akismet_HttpClient::factory($this->_api_server,
                $this->_api_port, $user_agent, $implementation);
    }

    // }}}
    // {{{ _isApiKeyValid()

    /**
     * Checks with the Akismet server to determine if a Wordpress API key is
     * valid
     *
     * @param string $key the Wordpress API key to check.
     *
     * @return boolean true if the key is valid and false if it is not valid.
     *
     * @throws Services_Akismet_CommunicationException if there is an error
     *         communicating with the Akismet API server.
     */
    private function _isApiKeyValid($key)
    {
        $post_data = sprintf('key=%s&blog=%s',
            urlencode($key),
            urlencode($this->_blog_uri));

        $response = $this->_request('verify-key', $post_data);
        return ($response == 'valid');
    }

    // }}}
    // {{{ _request()

    /**
     * Calls a method on the Akismet API server using a HTTP POST request
     *
     * @param string $method_name the name of the Akismet method to call.
     * @param string $content     the post content of the request. This contains
     *                            Akismet method parameters.
     *
     * @return string the HTTP response content.
     *
     * @throws Services_Akismet_CommunicationException if there is an error
     *         communicating with the Akismet API server.
     */
    private function _request($method_name, $content)
    {
        $path = sprintf('/%s/%s', $this->_api_version, $method_name);
        $response = $this->_http_client->post($path, $content, $this->_api_key);
        return $response;
    }

    // }}}
}

// }}}

?>
