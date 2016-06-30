<?php
/**
 * Based on example of https://github.com/hassankhan/config
 * configuration based on *.ini file currently in standard form. 
 * Please use anoother configuration file for high level usage. Recommended is PHP and Yaml config type
 * 
 * @copyright	Copyright (c) 2016 Ibnu Syuhada
 */

namespace IS\Slim\LiteConfiguration\FileWriter;

use IS\Slim\LiteConfiguration\FileWriter\AbstractFileWriter;
use IS\Slim\LiteConfiguration\FileWriter\InterfaceFileWriter;
use IS\Slim\LiteConfiguration\Exception\FailWriteFileException;

class Ini extends AbstractFileWriter implements InterfaceFileWriter
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
		$clean = $this->write_ini_file($this->content);
		if(!$this->getFileSystem()->put(array_pop($pathinfo), $clean))
		{
			throw new FailWriteFileException(
						"Fail to write or update a file configuration.
						 Please check the permission or make sure the directory is exist.
						"
					);
		}
	}
	
	/**
	 * 
	 * @param array $assoc_arr
	 * @return string
	 */
	private function write_ini_file($assoc_arr) {
		$content = "";
		$temp = "";
		foreach ($assoc_arr as $key=>$elem) {
			if (is_array($elem))
			{
				$content .= "[".$key."]\n";
				foreach ($elem as $key2=>$elem2) {
					if(is_array($elem2))
					{
					}
					else if($elem2=="") $content .= $key2." = \n";
					else {						
						if(is_numeric($key2)) 
						{
							$content = str_replace("[".$key."]\n", "", $content);
							$content .= $key."[] = $elem2" . "\n";
						}else{
							$content .= $key2." = \"".$elem2."\"\n";
						}
					}
				}
			}else if($elem=="") $content .= $key." = \n";
			else $content .= $key." = \"".$elem."\"\n";
		}
		return $content;
	}
}