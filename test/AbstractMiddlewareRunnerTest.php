<?php

namespace PhpMiddlewareTest\PhpDebugBar;

use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractMiddlewareRunnerTest extends PHPUnit_Framework_TestCase
{
    final public function testAppendJsIntoHtmlContent()
    {
        $response = $this->dispatchApplication([
            'REQUEST_URI' => '/hello',
            'REQUEST_METHOD' => 'GET',
            'HTTP_ACCEPT' => 'text/html',
        ]);

        $responseBody = (string) $response->getBody();

        $this->assertContains('var phpdebugbar = new PhpDebugBar.DebugBar();', $responseBody);
        $this->assertContains('Hello!', $responseBody);
        $this->assertContains('"/phpdebugbar/debugbar.js"', $responseBody);
    }

    final public function testGetStatics()
    {
        $response = $this->dispatchApplication([
            'DOCUMENT_ROOT' => __DIR__,
            'REMOTE_ADDR' => '127.0.0.1',
            'REMOTE_PORT' => '40226',
            'SERVER_SOFTWARE' => 'PHP 7.0.8-3ubuntu3 Development Server',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'SERVER_NAME' => '0.0.0.0',
            'SERVER_PORT' => '8080',
            'REQUEST_URI' => '/phpdebugbar/debugbar.js',
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_NAME' => '/phpdebugbar/debugbar.js',
            'SCRIPT_FILENAME' => __FILE__,
            'PHP_SELF' => '/phpdebugbar/debugbar.js',
            'HTTP_HOST' => '0.0.0.0:8080',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        ]);

        $contentType = $response->getHeaderLine('Content-type');

        $this->assertContains('text/javascript', $contentType);
    }

    /**
     * @return ResponseInterface
     */
    abstract protected function dispatchApplication(array $server);
}
