<?php
/**
 * @copyright	Copyright (c) 2016 Ibnu Syuhada
 */

namespace IS\Slim\LiteConfiguration\FileWriter;

use Symfony\Component\Yaml\Yaml as YamlService;
use IS\Slim\LiteConfiguration\FileWriter\AbstractFileWriter;
use IS\Slim\LiteConfiguration\FileWriter\InterfaceFileWriter;
use IS\Slim\LiteConfiguration\Exception\FailWriteFileException;


class Yaml extends AbstractFileWriter implements InterfaceFileWriter
{
	/**
	 * 
	 * {@inheritDoc}
	 * @see IS\Slim\LiteConfiguration\FileWriter\InterfaceFileWriter::writeRequestFile()
	 * @throws FailWriteFileException
	 */
	public function writeRequestFile()
	{
		$yaml = YamlService::dump($this->content);
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