<?php
namespace Cartograph\Maps;


use PHPUnit\Framework\TestCase;


class MapCollectionTest extends TestCase
{
	public function test_addMetohod_saveNewItemToCollection()
	{
		$mapCollection = new MapCollection();
		
		$from	= 'A';
		$to		= 'B';
		$func = function (){};
		
		$mapCollection->add($from, $to, $func);
		
		$callable = $mapCollection->get($from, $to);
		
		$this->assertEquals($callable, $func);
	}
}
