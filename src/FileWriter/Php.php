<?php
/**
 * @copyright	Copyright (c) 2016 Ibnu Syuhada
 */

namespace IS\Slim\LiteConfiguration\FileWriter;

use IS\Slim\LiteConfiguration\FileWriter\AbstractFileWriter;
use IS\Slim\LiteConfiguration\FileWriter\InterfaceFileWriter;
use IS\Slim\LiteConfiguration\Exception\FailWriteFileException;

class Php extends AbstractFileWriter implements InterfaceFileWriter
{
	/**
	 * 
	 * {@inheritDoc}
	 * @see IS\Slim\LiteConfiguration\FileWriter\InterfaceFileWriter::writeRequestFile()
	 * @throws FailWriteFileException
	 */
	public function writeRequestFile()
	{
		$pathinfo = explode(dirname($this->fileinfo),$this->fileinfo);
		$clean = "<?php" . "\n";
		$clean .= "return ";
		$clean .= var_export($this->content,true);
		$clean .= ";";
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