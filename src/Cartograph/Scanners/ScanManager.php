<?php
namespace Cartograph\Scanners;


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
	 * @param object|string $item
	 */
	public function add($item): void
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