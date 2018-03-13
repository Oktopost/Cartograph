<?php
namespace Cartograph\Maps;


class MapCollection
{
	/**
	 * @var array
	 */
	private $collection;
	
	/**
	 * @var array
	 */
	private $bulkCollection;
	
	private $isBulk = false;
	
	
	private function bulkEmptyFallback(string $from, string $to): ?callable
	{
		$single = $this->get($from, $to);
		
		if (!$single)
			return null;
		
		return function (array $source) use ($single): array
		{
			$res = [];
			
			foreach ($source as $item)
			{
				$res[] = $single($item);
			}
			
			return $res;
		};
	}
	
	
	public function __clone()
	{
		
	}
	
	
	public function add(string $from, string $to, callable $action): MapCollection
	{
		$this->collection[$from . $to] = $action;
		
		return $this;
	}
	
	public function addBulk(string $from, string $to, callable $action): MapCollection
	{
		$this->bulkCollection[$from . $to] = $action;
		
		return $this;
	}
	
	public function get(string $from, string $to): ?callable 
	{
		return $this->collection[$from . $to] ?? null;
	}
	
	public function getBulk(string $from, string $to): ?callable 
	{
		$key = $from . $to;
		
		if (isset($this->bulkCollection[$key]))
			return $this->bulkCollection[$key];
		
		return $this->bulkEmptyFallback($from, $to);
	}
	
	public function merge(MapCollection $collection): void
	{
		$this->isBulk ? array_merge($this->bulkCollection, $collection) : array_merge($this->collection, $collection);
	}
	
	public function count(): int
	{
		return $this->isBulk ? count($this->bulkCollection) : count($this->collection);
	}
	
	public function isEmpty(): bool
	{
		return $this->isBulk ? empty($this->bulkCollection) : empty($this->collection);
	}
}