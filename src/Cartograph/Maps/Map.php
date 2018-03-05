<?php
namespace Cartograph\Maps;


use Cartograph\Base\IMap;


class Map implements IMap
{
	public function __construct(MapCollection $collection)
	{
		
	}
	
	public function from($source): IMap
	{
		// TODO: Implement from() method.
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
		// TODO: Implement keepIndexes() method.
	}
	
	/**
	 * @param string $target
	 * @return mixed
	 */
	public function into(string $target)
	{
		// TODO: Implement into() method.
	}
}