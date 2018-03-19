<?php
namespace Cartograph\Maps;


use Cartograph\Exceptions\Maps\MapperAlreadyExistsException;
use Cartograph\Exceptions\Maps\MapperNotSetException;
use PHPUnit\Framework\TestCase;


class MapCollectionTest extends TestCase
{
	public function test_add_addNewItemToEmptyCollection_increasesCollectionCount()
	{
		$from	= 'A';
		$to		= 'B';
		$func	= function () {};
		
		$mapCollection = new MapCollection();
		$mapCollection->add($from, $to, $func);
		
		$this->assertEquals(1,	$mapCollection->countNonBulk());
		$this->assertEquals($func,		$mapCollection->get($from, $to));
	}
	
	public function test_add_addingSameValueToCollection_ThrowsException()
	{
		$this->expectException(MapperAlreadyExistsException::class);
		
		$mapCollection = new MapCollection();
		$mapCollection->add('A', 'B', function () {});
		$mapCollection->add('A', 'B', function () {});
	}
	
	public function test_add_addNewItemToEmptyBulkCollection_increasesBulkCollectionCount()
	{
		$from	= 'A';
		$to		= 'B';
		$func	= function () {};
		
		$mapCollection = new MapCollection();
		$mapCollection->addBulk($from, $to, $func);
		
		
		$this->assertEquals(1,	$mapCollection->countBulk());
		$this->assertEquals($func,		$mapCollection->getBulk($from, $to));
	}
	
	public function test_addBulk_addingSameValueToBulkCollection_ThrowsException()
	{
		$this->expectException(MapperAlreadyExistsException::class);
		
		$mapCollection = new MapCollection();
		
		$mapCollection->addBulk('A', 'B', function () {});
		$mapCollection->addBulk('A', 'B', function () {});
	}
	
	public function test_getBulk_withNothingAddedAndNoFallback_throwsException()
	{
		$this->expectException(MapperNotSetException::class);
		$mapCollection = new MapCollection();
		$mapCollection->getBulk('A', 'B');
	}
	
	public function test_getBulk_withNothingAddedToBulkAndFallbackDefined_returnsFallbackFunction()
	{
		$from	= 'A';
		$to		= 'B';
		$func	= function (int $item) {return 1 + $item;};
		
		$mapCollection = new MapCollection();
		$mapCollection->add($from, $to, $func);
		
		$bulkFallBackFunc= $mapCollection->getBulk($from, $to);
		
		$this->assertEquals([2], $bulkFallBackFunc([1]));
	}
	
	public function test_merge_MergingTwoMapCollections_increasesNumberOfCollection()
	{
		$mapCollection1 = new MapCollection();
		$mapCollection1->add('A', 'B', function () {return 1;});
		
		$mapCollection2 = new MapCollection();
		$mapCollection2->add('C', 'D', function () {return 2;});
		
		$mapCollection1->merge($mapCollection2);
		
		$this->assertEquals(2, $mapCollection1->countNonBulk());
	}
	
	public function test_isEmtpty_withEmptyCollection_returnsTrue()
	{
		$mapCollection = new MapCollection();
		$this->assertTrue($mapCollection->isEmpty());
	}
	
	public function test_isEmtpty_withNonEmptyCollectionAndEmptyBulkCollection_returnsFalse()
	{
		$mapCollection = new MapCollection();
		
		$mapCollection->add('A','B', function () {});
		
		$this->assertEquals(0, $mapCollection->countBulk());
		$this->assertFalse($mapCollection->isEmpty());
	}
	
	public function test_isEmtpty_withEmptyCollectionAndNonEmptyBulkCollection_returnsFalse()
	{
		$mapCollection = new MapCollection();
		
		$mapCollection->addBulk('A','B', function () {});
		
		$this->assertEquals(0, $mapCollection->countNonBulk());
		$this->assertFalse($mapCollection->isEmpty());
	}
}
