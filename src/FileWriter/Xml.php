<?php
/**
 * Based on example of https://github.com/hassankhan/config
 * configuration based on *.xml file currently in standard form. 
 * Please use anoother configuration file for high level usage. Recommended is PHP and Yaml config type
 * 
 * @copyright	Copyright (c) 2016 Ibnu Syuhada
 */

namespace IS\Slim\LiteConfiguration\FileWriter;

use IS\Slim\LiteConfiguration\FileWriter\AbstractFileWriter;
use IS\Slim\LiteConfiguration\FileWriter\InterfaceFileWriter;
use IS\Slim\LiteConfiguration\Exception\FailWriteFileException;
use Spatie\ArrayToXml\ArrayToXml;

class Xml extends AbstractFileWriter implements InterfaceFileWriter
{
	/**
	 * 
	 * {@inheritDoc}
	 * @see IS\Slim\LiteConfiguration\FileWriter\InterfaceFileWriter::writeRequestFile()
	 * @throws FailWriteFileException
	 */
	public function writeRequestFile()
	{
		$clean = ArrayToXml::convert($this->content,"config");
		$pathinfo = explode(dirname($this->fileinfo),$this->fileinfo);
		if(!$this->getFileSystem()->put(array_pop($pathinfo), $clean))
		{
			throw new FailWriteFileException(
						"Fail to write or update a file configuration.
						 Please check the permission or make sure the directory is exist.
						"
					);
		}
	}
}