<?php
namespace IS\Slim\LiteConfiguration\Tests;


use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Http\Headers;
use Slim\Http\Body;
use Slim\Http\Request;
use Slim\Http\Response;
use IS\Slim\LiteConfiguration\Configuration;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ConfigurationTest extends \PHPUnit_Framework_TestCase 
{
	public function testLoadMultipleFilesInsideFolder()
	{
		$app = $this->appFactory();
		$container = $app->getContainer();
		$container["config"] = function ($container){
			return new Configuration(__DIR__ . "/mocks",$container);
		};
		
		$app->get("/foo", function(ServerRequestInterface $request, ResponseInterface $response){
			$settings = $this->get("settings");
			return $response->write($settings["db"]["dbname"]);
		});
		
		$app->add($container->get("config"));
		$resOut = $app->run(true);
		$resOut->getBody()->rewind();
		$this->assertEquals("exampleapp",$resOut->getBody()->getContents());
	}
	
	public function testSafeMemoryUsageSettingsStillWorks()
	{
		$app = $this->appFactory();
		$container = $app->getContainer();
		$container["config"] = function ($container){
			return new Configuration(__DIR__ . "/mocks",$container);
		};
		
		$app->add($container->get("config"));
		$app->get("/foo", function(ServerRequestInterface $request, ResponseInterface $response){
			$settings = $this->get("settings");
			return $response->write($settings["whoCreate"]);
		});
		$container->offsetUnset("config");
		$resOut = $app->run(true);
		$resOut->getBody()->rewind();
		
		// check if Slim settings still exists with request configuration
		// even the config container has been deleted
		$this->assertEquals("Slim",$resOut->getBody()->getContents());
	}
	
	public function testLoadOneFile()
	{
		$app = $this->appFactory();
		$container = $app->getContainer();
		$container["config"] = function ($container){
			return new Configuration(__DIR__ . "/mocks/config.yaml",$container);
		};
		
		$app->get("/foo", function(ServerRequestInterface $request, ResponseInterface $response){
			$settings = $this->get("settings");
			return $response->write($settings["whoCreate"]);
		});
		
		$app->add($container->get("config"));
		$resOut = $app->run(true);
		$resOut->getBody()->rewind();
		$this->assertEquals("Slim",$resOut->getBody()->getContents());		
	}
	
	public function appFactory()
	{
		$app = new App([
				"settings" => [
						"displayErrorDetails" => true
				]
		]);
		// Prepare request and response objects
		$env = Environment::mock([
				'SCRIPT_NAME' => '/index.php',
				'REQUEST_URI' => '/foo',
				'REQUEST_METHOD' => 'GET',
		]);
		$uri = Uri::createFromEnvironment($env);
		$headers = Headers::createFromEnvironment($env);
		$cookies = [];
		$serverParams = $env->all();
		$body = new Body(fopen('php://temp', 'r+'));
		$req = new Request('GET', $uri, $headers, $cookies, $serverParams, $body);
		$res = new Response();
		$app->getContainer()['request'] = $req;
		$app->getContainer()['response'] = $res;
		return $app;
	}
	
}