<?php
namespace IS\Slim\LiteConfiguration\FileWriter;

interface InterfaceFileWriter
{
	/**
	 * Write or update file of configuration.
	 * Throw exception if write or update file is fail.
	 * @return void
	 */
	public function writeRequestFile();
}
