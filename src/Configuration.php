<?php
/**
 * Slim Configuration Lite
 * 
 * This is the main file. Call this via middleware and register to container
 * of Slim Framework v3.0.0 or above
 * 
 * @copyright	Copyright (c) 2016 Ibnu Syuhada
 * 
 */

namespace IS\Slim\LiteConfiguration;

use Interop\Container\ContainerInterface;
use Noodlehaus\Config;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Noodlehaus\Exception\UnsupportedFormatException;
use Noodlehaus\Exception\EmptyDirectoryException;
use IS\Slim\LiteConfiguration\Exception\NotAllowedException;

class Configuration extends Config
{
    /**
     * Supported file based on https://github.com/hassankhan/config
     * @var array
     */
	private $supportedFileParsers = array(
        'Noodlehaus\FileParser\Php',
        'Noodlehaus\FileParser\Ini',
        'Noodlehaus\FileParser\Json',
        'Noodlehaus\FileParser\Xml',
        'Noodlehaus\FileParser\Yaml'
    );
	
	/**
	 * Path of file. This also can contain more than one path of files.
	 * This variable also receive directory path in want to read all files inside the directory.
	 * @var string|array
	 */
	private $configpath;
	
	/**
	 * 
	 * @var ContainerInterface
	 */
	private $container;
	
	/**
	 * 
	 * @param string|array $path				File(s) or directory of configuration
	 * @param ContainerInterface $container		Container interface
	 */
	public function __construct($path,ContainerInterface $container)
	{
		$this->configpath = $path;
		$this->container = $container;
	}
	
	/**
	 * 
	 * @param ServerRequestInterface $request 	PSR7 request
	 * @param ResponseInterface $response		PSR7 response
	 * @param callable $next					Next middleware
	 * 
	 * @return ResponseInterface
	 */
	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
	{
		$settings = $this->container->settings;
		
		parent::__construct($this->configpath);
		
		foreach ($this->all() as $key=>$val)
		{
			$settings[$key] = $val;
		}
		return $next($request,$response);
	}
	
	/**
	 * Write a file configuration if the request is not exists or update if otherwise
	 * 
	 * @param array $data
	 * @param string $file
	 * @throws NotAllowedException
	 * @throws EmptyDirectoryException
	 * @return void
	 */
	public function writeConfig(array $data, $file)
	{
		$dirname = dirname($file);
		if ( !file_exists(dirname($file)) ) throw new EmptyDirectoryException("The directory of config file is not exists");
		
		if ( !is_string($file) || !is_dir($file) ) throw new NotAllowedException("Only one file is allowed to write or update");
		
		$parser = $this->getParser(array_pop( explode(".",$file) ));
		
		$this->writeFile($parser,$data,$file);
	}
	
	/**
	 * 
	 * @param Noodlehaus\FileParser\FileParserInterface $object
	 * @param array $data
	 * @param string $fileinfo
	 * @return void
	 */
	private function writeFile($object,array $data, $fileinfo)
	{
		$call = "\\IS\\Slim\\LiteConfiguration\\FileWriter\\" . array_pop(explode("\\",get_class($object)));
		$target = new $call($data,$fileinfo);
		$target->writeRequestFile();
	}
	
	/**
	 * 
	 * @param string $extension
	 * @throws UnsupportedFormatException
	 * @return Noodlehaus\FileParser\FileParserInterface
	 */
	private function getParser($extension)
	{
		$parser = null;
		
		foreach ($this->supportedFileParsers as $fileParser) {
			$tempParser = new $fileParser;
			
			if (in_array($extension, $tempParser->getSupportedExtensions($extension))) {
				$parser = $tempParser;
				continue;
			}
		
		}
		
		// If none exist, then throw an exception
		if ($parser === null) {
			throw new UnsupportedFormatException('Unsupported configuration format');
		}
		
		return $parser;		
	}
}