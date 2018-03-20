<?php
namespace Cartograph\Scanners;


use Itarator\IConsumer;


class FileConsumer implements IConsumer
{
	private $exceptions = []; 
	private $root; 
	
	
	/**
	 * @param string $path
	 */
	public function consume($path)
	{
		try
		{
			/** @noinspection PhpIncludeInspection */
			require_once "{$this->root}/$path";
		}
		catch (\Throwable $t)
		{
			$this->exceptions[] = $t;
		}
	}
	
	public function setRoot(string $root): void
	{
		$this->root = $root;
	}
	
	public function getExceptions(): array 
	{
		return $this->exceptions; 
	}
}