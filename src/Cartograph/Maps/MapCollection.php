<?php
namespace Cartograph\Maps;


class MapCollection
{
	/** @var array */
	private $collection = [];
	
	/** @var array */
	private $bulkCollection = [];
	
	
	private function getKey($from, $to): string
	{
		return $from . '|' . $to;
	} 
	
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
	
	
	public function add(string $from, string $to, callable $action): MapCollection
	{
		$this->collection[$this->getKey($from, $to)] = $action;
		return $this;
	}
	
	public function addBulk(string $from, string $to, callable $action): MapCollection
	{
		$this->bulkCollection[$this->getKey($from, $to)] = $action;
		return $this;
	}
	
	public function get(string $from, string $to): ?callable 
	{
		return $this->collection[$this->getKey($from, $to)] ?? null;
	}
	
	public function getBulk(string $from, string $to): ?callable 
	{
		$key = $this->getKey($from, $to);
		
		if (isset($this->bulkCollection[$key]))
			return $this->bulkCollection[$key];
		
		return $this->bulkEmptyFallback($from, $to);
	}
	
	public function merge(MapCollection $collection): void
	{
		$this->collection		=array_merge($this->collection, $collection->collection);
		$this->bulkCollection	=array_merge($this->bulkCollection, $collection->bulkCollection);
	}
	
	public function countBulk(): int
	{
		return count($this->bulkCollection);
	}
	
	public function countNonBulk(): int
	{
		return count($this->collection);
	}
	
	public function isEmpty(): bool
	{
		return empty($this->bulkCollection) && empty($this->collection);
	}
}