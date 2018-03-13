<?php
namespace Cartograph;


use Cartograph\Maps\Map;
use Cartograph\Base\IMap;
use Cartograph\Scanners\ScanManager;


class Cartograph
{
	/** @var ScanManager */
	private $scanner;
	
	
	public function __construct()
	{
		$this->scanner = new ScanManager();
	}
	
	public function __clone()
	{
		$this->scanner = clone $this->scanner;
	}
	
	
	/**
	 * @param object|string $item
	 * @return Cartograph
	 */
	public function addClass($item): Cartograph
	{
		$this->scanner->add($item);
		return $this;
	}
	
	/**
	 * @param string[]|string $path
	 * @return Cartograph
	 */
	public function addDir(... $path): Cartograph
	{
		$this->scanner->addDir(...$path);
		return $this;
	}
	
	/**
	 * @param array|mixed|null $source
	 * @return IMap
	 */
	public function map($source = null): IMap
	{
		/** @var IMap $m */
		$m = new Map($this->scanner->getCollection());
		
		if (is_array($source))
			$m->fromArray($source);
		else if ($source)
			$m->from($source);
		
		return $m;
	}
}