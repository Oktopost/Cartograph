<?php
namespace Cartograph\Maps;


use Cartograph\Base\IMap;
use Cartograph\Cartograph;
use Cartograph\Exceptions\CartographException;
use Cartograph\Exceptions\Maps\MapEmptyArgException;


class Map implements IMap
{
	private const ONE_ITEM				= 1;
	private const SERIES_OF_SAME_ITEMS	= 2;
	private const SERIES_OF_ANY_ITEMS	= 3;
	
	
	/** @var Cartograph */
	private $cartograph;
	
	/** @var MapCollection */
	private $collection;
	
	/** @var array|object */
	private $payload;
	
	private $sourceName;
	private $keepIndexes = false;
	private $mapCase;
	
	
	private function getTypeName($source)
	{
		if (is_scalar($source))
		{
			return gettype($source);
		}
		else if ((is_array($source)))
		{
			return 'array';
		}
		else
		{
			return get_class($source);
		}
	}
	
	private function transformSource(string $target)
	{
		$callback = $this->collection->get($this->sourceName, $target);
		
		return $callback($this->payload, $this->cartograph);
	}
	
	private function transformSameItems(string $target)
	{
		$callback = $this->collection->getBulk($this->sourceName, $target);
		$response = $callback(array_values($this->payload));
		
		if ($this->keepIndexes)
			return array_combine(array_keys($this->payload),$response);
		else
			return $callback($this->payload, $this->cartograph);
	}
	
	private function transformAnyItems(string $target)
	{
		$res = [];
		
		if (!$this->keepIndexes)
			$this->payload = array_values($this->payload);
		
		foreach ($this->payload as $key => $item)
		{
			$callback	= $this->collection->get($this->getTypeName($item), $target);
			$res[$key]	= $callback($item,  $this->cartograph);
		}
		
		return $res;
	}
	
	
	public function __construct(MapCollection $collection, Cartograph $cartograph)
	{
		$this->collection = $collection;
		$this->cartograph = $cartograph;
	}
	
	public function from($source): IMap
	{
		$this->sourceName	= $this->getTypeName($source);
		$this->payload		= $source;
		$this->mapCase		= self::ONE_ITEM;
		
		return $this;
	}
	
	public function fromArray(array $source): IMap
	{
		if (!$source)
			throw new MapEmptyArgException();
		
		$this->sourceName	= $this->getTypeName(reset($source));
		$this->payload		= $source;
		$this->mapCase		= self::SERIES_OF_SAME_ITEMS;
		
		return $this;
	}
	
	public function fromEach(array $source): IMap
	{
		$this->payload	= $source;
		$this->mapCase	= self::SERIES_OF_ANY_ITEMS;
		
		return $this;
	}
	
	public function getGroup(array $data, $key, ?callable $callback = null): array 
	{
		$result = [];
		
		foreach ($data as $val) 
		{
			$result[$val[$key]][] = $callback ? $callback($val) : $val;
		}
		
		return $result;
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
			case self::ONE_ITEM :
				return $this->transformSource($target);
			case self::SERIES_OF_SAME_ITEMS :
				return $this->transformSameItems($target);
			case self::SERIES_OF_ANY_ITEMS :
				return $this->transformAnyItems($target);
			default:
				 throw new CartographException('Wrong map case');
		}
	}
}