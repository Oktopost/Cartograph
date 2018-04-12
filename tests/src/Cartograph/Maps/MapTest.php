<?php
namespace Cartograph\Maps;


use Cartograph\Cartograph;
use Cartograph\Exceptions\CartographException;
use Cartograph\Exceptions\Maps\MapEmptyArgException;
use PHPUnit\Framework\TestCase;


class MapTest extends TestCase
{
	public function test_from_withScalarSource_definesSourceNameAsScalar()
	{
		$map = new Map(new MapCollection(), new Cartograph());
		$map->from('test');
		
		$reflection	= new \ReflectionClass($map);
		$property	= $reflection->getProperty('sourceName');
		$property->setAccessible(true);
		
		self::assertEquals('string', $property->getValue($map));
	}
	
	public function test_from_withArraySource_definesSourceNameAsArray()
	{
		$map = new Map(new MapCollection(), new Cartograph());
		$map->from([]);
		
		$reflection	= new \ReflectionClass($map);
		$property	= $reflection->getProperty('sourceName');
		$property->setAccessible(true);
		
		self::assertEquals('array', $property->getValue($map));
	}
	
	public function test_from_withObjectSource_definesSourceNameClassName()
	{
		$map = new Map(new MapCollection(), new Cartograph());
		$map->from(new \stdClass());
		
		$reflection	= new \ReflectionClass($map);
		$property	= $reflection->getProperty('sourceName');
		$property->setAccessible(true);
		
		self::assertEquals('stdClass', $property->getValue($map));
	}
	
	public function test_fromArray_withArraySet_definesSourceNameByFirstItem()
	{
		$map = new Map(new MapCollection(), new Cartograph());
		$map->fromArray([new \stdClass(), new \stdClass()]);
		
		$reflection	= new \ReflectionClass($map);
		$property	= $reflection->getProperty('sourceName');
		$property->setAccessible(true);
		
		self::assertEquals('stdClass', $property->getValue($map));
	}
	
	public function test_fromArray_withEmptyArray_definesSourceNameByFirstItem()
	{
		self::expectException(MapEmptyArgException::class);
		$map = new Map(new MapCollection(), new Cartograph());
		$map->fromArray([]);
	}
	
	public function test_fromEach_withArraySet_definesMapCaseAsSeriesOfAnyItems()
	{
		$map = new Map(new MapCollection(), new Cartograph());
		$map->fromEach([new \stdClass(),1,2,'test']);
		
		$reflection	= new \ReflectionClass($map);
		$property	= $reflection->getProperty('mapCase');
		$property->setAccessible(true);
		$mapCaseConst = $reflection->getConstant('SERIES_OF_ANY_ITEMS');
		
		self::assertEquals($mapCaseConst, $property->getValue($map));
	}
	
	public function test_keepIndexes_calling_setFlagToTrue()
	{
		$map = new Map(new MapCollection(), new Cartograph());
		$map->keepIndexes();
		
		$reflection	= new \ReflectionClass($map);
		$property	= $reflection->getProperty('keepIndexes');
		$property->setAccessible(true);
		
		self::assertTrue($property->getValue($map));
	}
	
	public function test_into_withOneItem_callsTransformSource()
	{
		$stub = self::createMock(MapCollection::class);
		$stub->method('get')->willReturn(function ($item) { return $item;});
		
		$map = new Map($stub, new Cartograph());
		self::assertEquals('A' ,$map->from('A')->into('B'));
	}
	
	public function test_into_withSeriesOfSameItems_callsTransformSameItems()
	{
		$stub = self::createMock(MapCollection::class);
		$stub->method('getBulk')->willReturn(function (array $items) { return $items;});
		
		$map = new Map($stub, new Cartograph());
		self::assertEquals([1,2] ,$map->fromArray([1,2])->into('B'));
	}
	
	public function test_into_withSeriesOfSameItemsAndKeepIndexes_returnsTransformedWithOriginKeys()
	{
		$stub = self::createMock(MapCollection::class);
		$stub->method('getBulk')->willReturn(function (array $items) { return $items;});
		
		$map = new Map($stub, new Cartograph());
		self::assertEquals(['a'=>1,'b'=>2] ,$map->keepIndexes()->fromArray(['a'=>1,'b'=>2])->into('B'));
	}
	
	public function test_into_withSeriesOfAnyItemsAndKeepIndexFlag_returnsTransformedAndKeepKeys()
	{
		$stub = self::createMock(MapCollection::class);
		$stub->method('get')->willReturn(function ($item) { return $item;});
		
		$map = new Map($stub, new Cartograph());
		self::assertEquals(['a'=>1,'b'=>2] ,$map->fromEach(['a'=>1,'b'=>2])->keepIndexes()->into('B'));
	}
	
	public function test_into_withSeriesOfAnyItems_returnsTransformedWithoutKeepingOriginKeys()
	{
		$stub = self::createMock(MapCollection::class);
		$stub->method('get')->willReturn(function ($item) { return $item;});
		
		$map = new Map($stub, new Cartograph());
		self::assertEquals([1,2] ,$map->fromEach(['a'=>1,'b'=>2])->into('B'));
	}
	
	public function test_into_withNoMapCaseDefined_throwsException()
	{
		self::expectException(CartographException::class);
		
		$map = new Map(new MapCollection(), new Cartograph());
		$map->into('B');
	}
	
	public function test_getGroup_withoutCallBack_willGroupByKey()
	{
		$map = new Map(new MapCollection(), new Cartograph());
		
		$data = [
			['a' => 'b', 'c' => 'd'],
			['a' => 'b', 'c' => 'e'],
			['a' => 'c', 'c' => 'f']
		];
		
		$expected = [
			'b' => [
				['a' => 'b', 'c' => 'd'],
				['a' => 'b', 'c' => 'e']
			],
			'c' => [
				['a' => 'c', 'c' => 'f']
			]
		];
		
		self::assertEquals($expected, $map->getGroup($data, 'a'));
	}
	
	public function test_getGroup_withCallBack_willGroupByKeyAndApplyCallback()
	{
		$data = [
			['a' => 'b', 'c' => 'd'],
			['a' => 'b', 'c' => 'e'],
			['a' => 'c', 'c' => 'f']
		];
		
		$expected = [
			'b' => [1,1],
			'c' => [1]
		];
		$map = new Map(new MapCollection(), new Cartograph());
		
		self::assertEquals($expected, $map->getGroup($data, 'a', function () {return 1;}));
	}
}
