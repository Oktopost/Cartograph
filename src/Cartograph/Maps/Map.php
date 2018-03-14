<?php
namespace Cartograph\Maps;


use Cartograph\Base\IMap;


class Map implements IMap
{
	/** @var MapCollection */
	private $collection;
	
	private $from;
	
	private $keepIndexes = false;
	
	
	public function __construct(MapCollection $collection)
	{
		$this->collection = $collection;
	}
	
	public function from($source): IMap
	{
		$this->from = $source;
		
		return $this;
	}
	
	public function fromArray(array $source): IMap
	{
		// TODO: Implement fromArray() method.
	}
	
	public function fromEach(array $source): IMap
	{
		// TODO: Implement fromEach() method.
	}
	
	public function keepIndexes(): IMap
	{
		$this->keepIndexes = true;
		return $this;
	}
	
	/**
	 * @param string $target
	 * @return mixed
	 */
	public function into(string $target)
	{
		
	}
}