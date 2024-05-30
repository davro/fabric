<?php

/**
 * Slim Framework (https://slimframework.com)
 *
 * @license https://github.com/slimphp/PHP-View/blob/3.x/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace Fabric\Tests;

//use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Fabric\Fabric;
//use Slim\Psr7\Headers;
//use Slim\Psr7\Response;
//use Slim\Psr7\Stream;
//use Slim\Views\Exception\PhpTemplateNotFoundException;
//use Slim\Views\PhpRenderer;
//use Throwable;

class FabricTest extends TestCase
{
    public function testFabricVersion(): void
    {
        $fabric = new Fabric();
        $this->assertEquals(0.1, $fabric->version());
    }

    public function testRetrievingFabricationEngine(): void
    {
        $fabric = new Fabric();
        $this->assertIsObject($fabric->getEngine());
    }

    public function testFabricationEngineTemplate(): void
    {
        $fabric = new Fabric();
        $engine = $fabric->getEngine();
        $engine->setOption('doctype', 'html.5');
//        $engine->run('TEST'); // defaults to p
        $engine->run('<div>TEST</div>');
        
        $this->assertEquals('<!DOCTYPE HTML><html><body><div>TEST</div></body></html>', $engine->saveFabric());
    }
    
    public function testFabricationEngineHTMXScript(): void
    {
        $fabric = new Fabric();
        $engine = $fabric->getEngine();
        $engine->setOption('doctype', 'html.5');
        $engine->input('#hello', 'world');
        
$template = <<<EOT
<html>
<head>
    <script src="https://unpkg.com/htmx.org@1.9.4"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <div id="hello"></div>
</body>
</html>
EOT;
        $engine->run($template);

        // Assertions.
        $this->assertEquals(
            $engine->query('//html/head/script[contains(@src, "htmx")]')->item(0)->getAttribute('src'),
            'https://unpkg.com/htmx.org@1.9.4'
        );
        
        $this->assertEquals($engine->output('#hello'), 'world');
        $this->assertEquals('world', $engine->saveHTML('//div[@id="hello"]/text()'));
      
    }
    
    /**
     * HTMX 
     * 
     * @return void
     */
    public function testFabricationEngineHTMXTemplate(): void
    {
        $fabric = new Fabric();
        $engine = $fabric->getEngine();
        $engine->setOption('doctype', 'html.5');
        
// Template Layout
$template = <<<EOT
<html>
<head>
    <script src="https://unpkg.com/htmx.org@1.9.4"></script>
</head>
<body>
  <div hx-target="this" hx-swap="outerHTML">
    <label>Email:
      <input id="email" type="email" name="email" hx-post="/email" value="">
    </label>
    <div id="message"></div>
  </div>
        
  <div hx-target="this" hx-swap="outerHTML">
    <label>Username:
      <input id="username" type="text" name="username" hx-post="/username" value="">
    </label>
    <div id="username"></div>
  </div>
</body>
</html>
EOT;

        // Execute the template
        $engine->run($template);
        
        // Assertions.
        $this->assertEquals(
            $engine->query('//html/head/script[contains(@src, "htmx")]')->item(0)->getAttribute('src'), 
            'https://unpkg.com/htmx.org@1.9.4'
        );
        
        $engine->dump($engine->query('//*[@hx-target]'));
        
//
//// Construct the HTML template, with conditional error message
//$engine->query('//div[@id="message"]')
//    ->item(0)
//    ->nodeValue = "Default Message";
//
//// Query call after run function call
//$engine->query('//input[@id="email"]')
//    ->item(0)
//    ->setAttribute('value', 'test@test.com');
//
//
//echo $engine->saveFabric();
    }
    
//    public function testRenderer(): void
//    {
//        $renderer = new PhpRenderer(__DIR__ . '/_files/');
//        $headers = new Headers();
//        $body = new Stream(fopen('php://temp', 'r+'));
//        $response = new Response(200, $headers, $body);
//        $newResponse = $renderer->render($response, 'template.phtml', ['hello' => 'Hi']);
//        $newResponse->getBody()->rewind();
//        $this->assertEquals('Hi', $newResponse->getBody()->getContents());
//    }
//    
//    public function testRenderConstructor(): void
//    {
//        $renderer = new PhpRenderer(__DIR__ . '/_files');
//        $headers = new Headers();
//        $body = new Stream(fopen('php://temp', 'r+'));
//        $response = new Response(200, $headers, $body);
//        $newResponse = $renderer->render($response, 'template.phtml', ['hello' => 'Hi']);
//        $newResponse->getBody()->rewind();
//        $this->assertEquals('Hi', $newResponse->getBody()->getContents());
//    }
//
//    public function testAttributeMerging(): void
//    {
//
//        $renderer = new PhpRenderer(__DIR__ . '/_files/', [
//            'hello' => 'Hello'
//        ]);
//        $headers = new Headers();
//        $body = new Stream(fopen('php://temp', 'r+'));
//        $response = new Response(200, $headers, $body);
//        $newResponse = $renderer->render($response, 'template.phtml', [
//            'hello' => 'Hi'
//        ]);
//        $newResponse->getBody()->rewind();
//        $this->assertEquals('Hi', $newResponse->getBody()->getContents());
//    }
//
//    public function testExceptionInTemplate(): void
//    {
//        $renderer = new PhpRenderer(__DIR__ . '/_files/');
//        $headers = new Headers();
//        $body = new Stream(fopen('php://temp', 'r+'));
//        $response = new Response(200, $headers, $body);
//        try {
//            $newResponse = $renderer->render($response, 'exception_layout.phtml');
//        } catch (Throwable $t) {
//        // Simulates an error template
//            $newResponse = $renderer->render($response, 'template.phtml', [
//                'hello' => 'Hi'
//            ]);
//        }
//
//        $newResponse->getBody()->rewind();
//        $this->assertEquals('Hi', $newResponse->getBody()->getContents());
//    }
//
//    public function testExceptionForTemplateInData(): void
//    {
//        $renderer = new PhpRenderer(__DIR__ . '/_files/');
//        $headers = new Headers();
//        $body = new Stream(fopen('php://temp', 'r+'));
//        $response = new Response(200, $headers, $body);
//        $this->expectException(InvalidArgumentException::class);
//        $renderer->render($response, 'template.phtml', [
//            'template' => 'Hi'
//        ]);
//    }
//
//    public function testTemplateNotFound(): void
//    {
//        $renderer = new PhpRenderer(__DIR__ . '/_files/');
//        $headers = new Headers();
//        $body = new Stream(fopen('php://temp', 'r+'));
//        $response = new Response(200, $headers, $body);
//        $this->expectException(PhpTemplateNotFoundException::class);
//        $renderer->render($response, 'adfadftemplate.phtml', []);
//    }
//
//    public function testLayout(): void
//    {
//        $renderer = new PhpRenderer(__DIR__ . '/_files/', ['title' => 'My App']);
//        $renderer->setLayout('layout.phtml');
//        $headers = new Headers();
//        $body = new Stream(fopen('php://temp', 'r+'));
//        $response = new Response(200, $headers, $body);
//        $newResponse = $renderer->render($response, 'template.phtml', ['title' => 'Hello - My App', 'hello' => 'Hi']);
//        $newResponse->getBody()->rewind();
//        $this->assertEquals('<html><head><title>Hello - My App</title></head><body>Hi<footer>This is the footer'
//                            . '</footer></body></html>', $newResponse->getBody()->getContents());
//    }
//
//    public function testLayoutConstructor(): void
//    {
//        $renderer = new PhpRenderer(__DIR__ . '/_files', ['title' => 'My App'], 'layout.phtml');
//        $headers = new Headers();
//        $body = new Stream(fopen('php://temp', 'r+'));
//        $response = new Response(200, $headers, $body);
//        $newResponse = $renderer->render($response, 'template.phtml', ['title' => 'Hello - My App', 'hello' => 'Hi']);
//        $newResponse->getBody()->rewind();
//        $this->assertEquals('<html><head><title>Hello - My App</title></head><body>Hi<footer>This is the footer'
//                            . '</footer></body></html>', $newResponse->getBody()->getContents());
//    }
//
//    public function testExceptionInLayout(): void
//    {
//        $renderer = new PhpRenderer(__DIR__ . '/_files/');
//        $renderer->setLayout('exception_layout.phtml');
//        $headers = new Headers();
//        $body = new Stream(fopen('php://temp', 'r+'));
//        $response = new Response(200, $headers, $body);
//        try {
//            $newResponse = $renderer->render($response, 'template.phtml');
//        } catch (Throwable $t) {
//        // PHP 7+
//            // Simulates an error template
//            $renderer->setLayout('');
//            $newResponse = $renderer->render($response, 'template.phtml', [
//                'hello' => 'Hi'
//            ]);
//        }
//
//        $newResponse->getBody()->rewind();
//        $this->assertEquals('Hi', $newResponse->getBody()->getContents());
//    }
//
//    public function testLayoutNotFound(): void
//    {
//        $renderer = new PhpRenderer(__DIR__ . '/_files/');
//        $this->expectException(PhpTemplateNotFoundException::class);
//        $renderer->setLayout('non-existent_layout.phtml');
//    }
//
//    public function testContentDataKeyShouldBeIgnored(): void
//    {
//        $renderer = new PhpRenderer(__DIR__ . '/_files/');
//        $renderer->setLayout('layout.phtml');
//        $headers = new Headers();
//        $body = new Stream(fopen('php://temp', 'r+'));
//        $response = new Response(200, $headers, $body);
//        $newResponse = $renderer->render(
//            $response,
//            'template.phtml',
//            ['title' => 'Hello - My App', 'hello' => 'Hi', 'content' => 'Ho']
//        );
//        $newResponse->getBody()->rewind();
//        $this->assertEquals('<html><head><title>Hello - My App</title></head><body>Hi<footer>This is the footer'
//                            . '</footer></body></html>', $newResponse->getBody()->getContents());
//    }
//
//    public function testTemplateExists()
//    {
//        $renderer = new PhpRenderer(__DIR__ . '/_files/');
//        $this->assertTrue($renderer->templateExists('layout.phtml'));
//        $this->assertFalse($renderer->templateExists('non-existant-template'));
//    }
}


//
// TEST 1 - htmx email 
// 
//<?php
//
//require __DIR__ . '/vendor/autoload.php';
//
//$engine = new Fabric\FabricEngine();
//
//// Template Layout
//$template = <<<EOT
//<html>
//<head>
//    <script src="https://unpkg.com/htmx.org@1.9.4"></script>
//</head>
//<body>
//  <div hx-target="this" hx-swap="outerHTML">
//    <label>Email:
//      <input id="email" type="email" name="email" hx-post="/email" value="">
//    </label>
//    <div id="message"></div>
//  </div>
//</body>
//</html>
//EOT;
//
//// Execute the template
//$engine->run($template);
//
//// Get the email from the POST and validate for gmail only accounts
//$emailError = !preg_match("/@gmail\.com$/i", isset($_POST['email']) ? $_POST['email'] : '');
//
//// Construct the HTML template, with conditional error message
//$engine->query('//div[@id="message"]')
//    ->item(0)
//    ->nodeValue = $emailError ? 'Only Gmail addresses accepted!' : '';
//
//// Query call after run function call
//$engine->query('//input[@id="email"]')
//    ->item(0)
//    ->setAttribute('value', filter_var($email, FILTER_VALIDATE_EMAIL));
//
//
//echo $engine->saveFabric();




//
// TEST 2 - htmx email 
// 
//<?php
//
//require __DIR__ . '/vendor/autoload.php';
//
//$engine = new Fabrication\FabricationEngine();
//
//// Template Layout
//$template = <<<EOT
//<html>
//<head>
//    <script src="https://unpkg.com/htmx.org@1.9.4"></script>
//</head>
//<body>
//  <div hx-target="this" hx-swap="outerHTML">
//    <label>Email:
//      <input id="email" type="email" name="email" hx-post="/email" value="">
//    </label>
//    <div id="message"></div>
//  </div>
//</body>
//</html>
//EOT;
//
//// Execute the template
//$engine->run($template);
//
//// Construct the HTML template, with conditional error message
//$engine->query('//div[@id="message"]')
//    ->item(0)
//    ->nodeValue = "Default Message";
//
//// Query call after run function call
//$engine->query('//input[@id="email"]')
//    ->item(0)
//    ->setAttribute('value', 'test@test.com');
//
//
//echo $engine->saveFabric();