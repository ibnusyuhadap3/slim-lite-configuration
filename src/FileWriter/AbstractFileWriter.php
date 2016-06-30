<?php
/**
 * @copyright Copyright (c) 2016 Ibnu Syuhada
 */

namespace App\Middlewares\FileWriter;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

abstract class AbstractFileWriter
{
	/**
	 * Store data
	 * 
	 * @var array
	 */
	protected $content;
	
	/**
	 * Store path file in glob
	 * 
	 * @var string
	 */
	protected $fileinfo;
	
	/**
	 * 
	 * @var League\Flysystem\Adapter\Local
	 */
	protected $adapter;
	
	/**
	 * 
	 * @var League\Flysystem\Filesystem
	 */
	private $filesystem;
	
	/**
	 * 
	 * @param array $content
	 * @param string $fileinfo
	 */
	public function __construct(array $content,$fileinfo)
	{
		$this->fileinfo = $fileinfo;
		$this->content = $content;
		$this->adapter = new Local( dirname($fileinfo) );
		$this->filesystem = new Filesystem($this->adapter);
	}
	
	/**
	 * @return League\Flysystem\Filesystem
	 */
	protected function getFileSystem()
	{
		return $this->filesystem;
	}
}