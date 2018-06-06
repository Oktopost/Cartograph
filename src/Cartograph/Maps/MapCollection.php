<?php
namespace Cartograph\Maps;


use Cartograph\Cartograph;
use Cartograph\Exceptions\Maps\MapperAlreadyExistsException;
use Cartograph\Exceptions\Maps\MapperNotSetException;


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
	
	private function generateBulkFallback(string $from, string $to): ?callable
	{
		$single = $this->get($from, $to);
		
		return function (array $source, Cartograph $c) use ($single): array
		{
			$res = [];
			
			foreach ($source as $item)
			{
				$res[] = $single($item, $c);
			}
			
			return $res;
		};
	}
	
	private function checkCollectionForDuplicates(string $from, string $to, $bulk = false): void
	{
		$key	= $this->getKey($from, $to);
		$hasKey	= $bulk ? isset($this->bulkCollection[$key]) : isset($this->collection[$key]);
		
		if ($hasKey)
			throw new MapperAlreadyExistsException($from, $to);
	}
	
	public function add(string $from, string $to, callable $action): MapCollection
	{
		$this->checkCollectionForDuplicates($from, $to, false);
		$this->collection[$this->getKey($from, $to)] = $action;
		return $this;
	}
	
	public function addBulk(string $from, string $to, callable $action): MapCollection
	{
		$this->checkCollectionForDuplicates($from, $to, true);
		$this->bulkCollection[$this->getKey($from, $to)] = $action;
		return $this;
	}
	
	public function get(string $from, string $to): ?callable 
	{
		$key = $this->getKey($from, $to);
		
		if (!isset($this->collection[$key]))
			throw new MapperNotSetException($from, $to);
		
		return $this->collection[$this->getKey($from, $to)] ?? null;
	}
	
	public function getBulk(string $from, string $to): ?callable 
	{
		$key = $this->getKey($from, $to);
		
		if (isset($this->bulkCollection[$key]))
			return $this->bulkCollection[$key];
		
		return $this->generateBulkFallback($from, $to);
	}
	
	public function merge(MapCollection $collection): void
	{
		$this->collection		= array_merge($this->collection, $collection->collection);
		$this->bulkCollection	= array_merge($this->bulkCollection, $collection->bulkCollection);
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
		return !$this->bulkCollection && !$this->collection;
	}
}