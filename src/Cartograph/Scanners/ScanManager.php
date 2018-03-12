<?php
namespace Cartograph\Scanners;


use Cartograph\Base\IMapSource;
use Cartograph\Maps\MapCollection;


class ScanManager
{
	/** @var MapCollection */
	private $collection;
	
	
	public function __construct()
	{
		$this->collection = new MapCollection();
	}
	
	public function __clone()
	{
		$this->collection = clone $this->collection;
	}
	
	
	/**
	 * @param IMapSource|string $item
	 * @param string|null $value
	 * @param callable|null $c
	 */
	public function add($item, ?string $value = null, ?callable  $c = null): void
	{
		
	}
	
	/**
	 * @param string[]|string $path
	 */
	public function addDir(...$path): void
	{
		
	}
	
	
	public function getCollection(): MapCollection
	{
		return $this->collection;
	}
}