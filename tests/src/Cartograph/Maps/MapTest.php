<?php
namespace Cartograph\Maps;


use Cartograph\Exceptions\CartographException;
use Cartograph\Exceptions\Maps\MapEmptyArgException;
use PHPUnit\Framework\TestCase;


class MapTest extends TestCase
{
	public function test_from_withScalarSource_definesSourceNameAsScalar()
	{
		$map = new Map(new MapCollection());
		$map->from('test');
		
		$reflection	= new \ReflectionClass($map);
		$property	= $reflection->getProperty('sourceName');
		$property->setAccessible(true);
		
		$this->assertEquals('string', $property->getValue($map));
	}
	
	public function test_from_withArraySource_definesSourceNameAsArray()
	{
		$map = new Map(new MapCollection());
		$map->from([]);
		
		$reflection	= new \ReflectionClass($map);
		$property	= $reflection->getProperty('sourceName');
		$property->setAccessible(true);
		
		$this->assertEquals('array', $property->getValue($map));
	}
	
	public function test_from_withObjectSource_definesSourceNameClassName()
	{
		$map = new Map(new MapCollection());
		$map->from(new \stdClass());
		
		$reflection	= new \ReflectionClass($map);
		$property	= $reflection->getProperty('sourceName');
		$property->setAccessible(true);
		
		$this->assertEquals('stdClass', $property->getValue($map));
	}
	
	public function test_fromArray_withArraySet_definesSourceNameByFirstItem()
	{
		$map = new Map(new MapCollection());
		$map->fromArray([new \stdClass(), new \stdClass()]);
		
		$reflection	= new \ReflectionClass($map);
		$property	= $reflection->getProperty('sourceName');
		$property->setAccessible(true);
		
		$this->assertEquals('stdClass', $property->getValue($map));
	}
	
	public function test_fromArray_withEmptyArray_definesSourceNameByFirstItem()
	{
		$this->expectException(MapEmptyArgException::class);
		$map = new Map(new MapCollection());
		$map->fromArray([]);
	}
	
	public function test_fromEach_withArraySet_definesMapCaseAsSeriesOfAnyItems()
	{
		$map = new Map(new MapCollection());
		$map->fromEach([new \stdClass(),1,2,'test']);
		
		$reflection	= new \ReflectionClass($map);
		$property	= $reflection->getProperty('mapCase');
		$property->setAccessible(true);
		$mapCaseConst = $reflection->getConstant('SERIES_OF_ANY_ITEMS');
		
		$this->assertEquals($mapCaseConst, $property->getValue($map));
	}
	
	public function test_keepIndexes_calling_setFlagToTrue()
	{
		$map = new Map(new MapCollection());
		$map->keepIndexes();
		
		$reflection	= new \ReflectionClass($map);
		$property	= $reflection->getProperty('keepIndexes');
		$property->setAccessible(true);
		
		$this->assertTrue($property->getValue($map));
	}
	
	public function test_into_withOneItem_callsTransformSource()
	{
		$stub = $this->createMock(MapCollection::class);
		$stub->method('get')->willReturn(function ($item) { return $item;});
		
		$map = new Map($stub);
		$this->assertEquals('A' ,$map->from('A')->into('B'));
	}
	
	public function test_into_withSeriesOfSameItems_callsTransformSameItems()
	{
		$stub = $this->createMock(MapCollection::class);
		$stub->method('getBulk')->willReturn(function (array $items) { return $items;});
		
		$map = new Map($stub);
		$this->assertEquals([1,2] ,$map->fromArray([1,2])->into('B'));
	}
	
	public function test_into_withSeriesOfSameItemsAndKeepIndexes_returnsTransformedWithOriginKeys()
	{
		$stub = $this->createMock(MapCollection::class);
		$stub->method('getBulk')->willReturn(function (array $items) { return $items;});
		
		$map = new Map($stub);
		$this->assertEquals(['a'=>1,'b'=>2] ,$map->keepIndexes()->fromArray(['a'=>1,'b'=>2])->into('B'));
	}
	
	public function test_into_withSeriesOfAnyItemsAndKeepIndexFlag_returnsTransformedAndKeepKeys()
	{
		$stub = $this->createMock(MapCollection::class);
		$stub->method('get')->willReturn(function ($item) { return $item;});
		
		$map = new Map($stub);
		$this->assertEquals(['a'=>1,'b'=>2] ,$map->fromEach(['a'=>1,'b'=>2])->keepIndexes()->into('B'));
	}
	
	public function test_into_withSeriesOfAnyItems_returnsTransformedWithoutKeepingOriginKeys()
	{
		$stub = $this->createMock(MapCollection::class);
		$stub->method('get')->willReturn(function ($item) { return $item;});
		
		$map = new Map($stub);
		$this->assertEquals([1,2] ,$map->fromEach(['a'=>1,'b'=>2])->into('B'));
	}
	
	public function test_into_withNoMapCaseDefined_throwsException()
	{
		$this->expectException(CartographException::class);
		
		$map = new Map(new MapCollection());
		$map->into('B');
	}
}
