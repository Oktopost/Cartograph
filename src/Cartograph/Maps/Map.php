<?php
namespace Cartograph\Maps;


use Cartograph\Base\IMap;
use Cartograph\Exceptions\InvalidMapperSource;


class Map implements IMap
{
	private const ONE_ITEM				= 1;
	private const SERIES_OF_SAME_ITEM	= 2;
	private const SERIES_OF_ANY_ITEM	= 3;
	
	
	/** @var MapCollection */
	private $collection;
	/** @var array|object */
	private $payload;
	
	private $from;
	private $keepIndexes = false;
	private $mapCase;
	
	
	private function transformSource(string $target)
	{
		$callback = $this->collection->get($this->from, $target);
		
		if(!$callback)
			return null;
		
		return $callback($this->payload);
	}
	
	private function transformSameItems(string $target)
	{
		$callback = $this->collection->getBulk($this->from, $target);
		
		if (!$callback)
			return null;
		
		return $callback($this->payload);
	}
	
	private function transformAnyItems(string $target)
	{
		$res = [];
		
		foreach ($this->payload as $key => $item)
		{
			$callback = $this->collection->get(get_class($item), $target);
			if ($callback)
			{
				$this->keepIndexes ? $res[$key] = $callback($item) : $res[] = $callback($item); 
			}
		}
		
		return $res;
	}
	
	
	public function __construct(MapCollection $collection)
	{
		$this->collection = $collection;
	}
	
	public function from($source): IMap
	{
		if ((is_string($source) && !class_exists($source)) || !is_object($source))
			throw new InvalidMapperSource($source);
			
		$this->from		= is_string($source) ? $source : get_class($source);
		$this->payload	= $source;
		$this->mapCase	= self::ONE_ITEM;
		
		return $this;
	}
	
	public function fromArray(array $source): IMap
	{
		$this->from		= get_class($source[0]);
		$this->payload	= $source;
		$this->mapCase	= self::SERIES_OF_SAME_ITEM;
		
		return $this;
	}
	
	public function fromEach(array $source): IMap
	{
		$this->payload	= $source;
		$this->mapCase	= self::SERIES_OF_ANY_ITEM;
		
		return $this;
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
		switch ($this->mapCase)
		{
			case self::ONE_ITEM :				return $this->transformSource($target);
			case self::SERIES_OF_SAME_ITEM :	return $this->transformSameItems($target);
			case self::SERIES_OF_ANY_ITEM :		return $this->transformAnyItems($target);
			default:							return null;
		}
	}
}