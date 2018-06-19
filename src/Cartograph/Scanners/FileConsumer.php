<?php
namespace Cartograph\Scanners;


use Itarator\IConsumer;


class FileConsumer implements IConsumer
{
	/** @var \Throwable[] */
	private $exceptions = []; 
	
	
	/**
	 * @param string $path
	 */
	public function consume($path)
	{
		try
		{
			/** @noinspection PhpIncludeInspection */
			require_once "$path";
			
		}
		catch (\Throwable $t)
		{
			$this->exceptions[] = $t;
		}
	}
	
	/**
	 * @return \Throwable[]
	 */
	public function getExceptions(): array 
	{
		return $this->exceptions; 
	}
}