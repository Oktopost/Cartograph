<?php
namespace Cartograph\Scanners;


use Cartograph\Maps\MapCollection;


class ScanManager
{
	/** @var MapCollection */
	private $collection;
	
	
	public function __construct(MapCollection $collection = null)
	{
		$this->collection = $collection ? $collection : new MapCollection();
	}
	
	public function __clone()
	{
		$this->collection = clone $this->collection;
	}
	
	/**
	 * @param object|string $item
	 */
	public function addClass($item): void
	{
		$item = is_object($item) ? get_class($item) : $item;
		$this->collection->merge(ClassAnnotationScanner::scan($item));
	}
	
	/**
	 * @param string[]|string $path
	 */
	public function addDir(...$path): void
	{
		foreach ($path as $dir)
		{
			$this->collection->merge(RecursiveDirectoryScanner::scan($dir));
		}
	}
	
	public function getCollection(): MapCollection
	{
		return $this->collection;
	}
}