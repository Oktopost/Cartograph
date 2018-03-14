<?php
namespace Cartograph\Maps;


use PHPUnit\Framework\TestCase;


class MapCollectionTest extends TestCase
{
	public function test_addMethod_savesNewItemToCollection()
	{
		$from	= 'A';
		$to		= 'B';
		$func	= function (){};
		
		$mapCollection = new MapCollection();
		$mapCollection->add($from, $to, $func);
		
		$callable	= $mapCollection->get($from, $to);
		$count		= $mapCollection->countNonBulk();
		
		$this->assertEquals($callable, $func);
		$this->assertEquals(1, $count);
	}
	
	public function test_addBulkMethod_savesNewItemToBulkCollection()
	{
		$from	= 'A';
		$to		= 'B';
		$func	= function (){};
		
		$mapCollection = new MapCollection();
		$mapCollection->addBulk($from, $to, $func);
		
		$callable	= $mapCollection->getBulk($from, $to);
		$count		= $mapCollection->countBulk();
		
		$this->assertEquals($callable, $func);
		$this->assertEquals(1, $count);
	}
	
	public function test_getBulkMethod_returnNullIfNoFallbackWasDefinedAndNothingAdded()
	{
		$mapCollection = new MapCollection();
		
		$this->assertNull($mapCollection->getBulk('A', 'B'));
	}
	
	public function test_getBulkMethod_returnNotNullIfFallbackWasDefined()
	{
		$from	= 'A';
		$to		= 'B';
		$func	= function (int $item){return 1 + $item;};
		
		$mapCollection = new MapCollection();
		$mapCollection->add($from, $to, $func);
		
		$bulkFallBackFunc= $mapCollection->getBulk($from, $to);
		
		$this->assertEquals([2], $bulkFallBackFunc([1]));
	}
	
	public function test_mergeMethods_combinesCollections()
	{
		$from1	= 'A';
		$to1	= 'B';
		$func1	= function (){return 1;};
		
		$from2	= 'C';
		$to2	= 'D';
		$func2	= function (){return 2;};
		
		$mapCollection1 = new MapCollection();
		$mapCollection1->add($from1, $to1, $func1);
		
		$mapCollection2 = new MapCollection();
		$mapCollection2->add($from2, $to2, $func2);
		
		$mapCollection1->merge($mapCollection2);
		
		$this->assertEquals(2, $mapCollection1->countNonBulk());
	}
	
	public function test_isEmtptyMethod_checksEmptinesOfBothCollections()
	{
		$from	= 'A';
		$to	= 'B';
		$func = function (){return 1;};
		
		$mapCollection = new MapCollection();
		$this->assertTrue($mapCollection->isEmpty());
		
		$mapCollection->add($from, $to, $func);
		
		$this->assertEquals(0, $mapCollection->countBulk());
		$this->assertFalse($mapCollection->isEmpty());
	}
}
