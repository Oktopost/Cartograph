<?php
namespace Cartograph\Maps;


class MapCollection
{
	public function __clone()
	{
		
	}
	
	
	public function add(string $from, string $to, callable $action): MapCollection
	{
		
	}
	
	public function addBulk(string $from, string $to, callable $action): MapCollection
	{
		
	}
	
	public function get(string $from, string $to): ?callable 
	{
		
	}
	
	public function getBulk(string $from, string $to): ?callable 
	{
		
	}
	
	public function merge(MapCollection $collection): void
	{
		
	}
	
	public function count(): int
	{
		
	}
	
	public function isEmpty(): bool
	{
		
	}
}