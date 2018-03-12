<?php
namespace Cartograph;


use Cartograph\Maps\Map;
use Cartograph\Base\IMap;
use Cartograph\Base\IMapSource;
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
	 * @param IMapSource|string $item
	 * @param string|null $value
	 * @param callable|null $c
	 * @return Cartograph
	 */
	public function add($item, ?string $value = null, ?callable $c = null): Cartograph
	{
		$this->scanner->add($item, $value, $c);
		return $this;
	}
	
	/**
	 * @param string[]|string $path
	 * @return Cartograph
	 */
	public function addDir(... $path): Cartograph
	{
		// TODO: 
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