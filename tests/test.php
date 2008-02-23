<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

if (!array_key_exists($_SERVER['argv'][1])) {
    echo "specify your Akismet API key as the first argument.\n";
    exit(1);
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'Services/Akismet/Comment.php';
require_once 'Services/Akismet.php';

$spam_comment = new Services_Akismet_Comment();
$spam_comment->setAuthor('viagra-test-123');
$spam_comment->setAuthorEmail('test@example.com');
$spam_comment->setAuthorUri('http://example.com/');
$spam_comment->setContent('Spam, I am.');

$spam_comment->setUserIp('127.0.0.1');
$spam_comment->setUserAgent('Services_Akismet test');
$spam_comment->setHttpReferer('http://example.com');

echo $spam_comment;

$comment = new Services_Akismet_Comment();
$comment->setAuthor('Example Author');
$comment->setAuthorEmail('test@example.com');
$comment->setAuthorUri('http://example.com/');
$comment->setContent('Hello, World!');

$comment->setUserIp('127.0.0.1');
$comment->setUserAgent('Services_Akismet test');
$comment->setHttpReferer('http://example.com');

echo $comment;

$bad_key = 'asdf';
$key = $_SERVER['argv'][1];
$uri = 'http://example.com';
$implementations = array(
    'sockets',
    'streams',
    'curl'
);

foreach ($implementations as $implementation) {
    echo "\n== Testing implementation {$implementation} ==\n";

    $akismet = new Services_Akismet($uri, $key, $implementation);

    if ($akismet->isSpam($spam_comment)) {
        echo "Passed: Spam detected.\n";
    } else {
        echo "Failed: Spam not detected.\n";
    }

    if ($akismet->isSpam($comment)) {
        echo "Failed: False-positive.\n";
    } else {
        echo "Passed: Not spam.\n";
    }

    try {
        $akismet = new Services_Akismet($uri, $bad_key, $implementation);
        echo "Failed: Bad API key not detected.\n";
    } catch (Services_Akismet_InvalidApiKeyException $e) {
        echo "Passed: Caught bad API key.\n";
    }
}

?>
