<?php
namespace Cartograph\Scanners;


use Cartograph\Maps\MapCollection;
use PHPUnit\Framework\TestCase;


class ScanManagerTest extends TestCase
{
	
	public function test_addClass_withStringName_callsClassAnnotationScanner()
	{
		$mapCollection = $this->createMock(MapCollection::class);
		$mapCollection->expects($this->once())->method('merge');
		$scanner = new ScanManager($mapCollection);
		$scanner->addClass(\stdClass::class);
	}
	
	public function test_getCollection_returnMapCollection()
	{
		$mapCollection = new MapCollection();
		$scanner = new ScanManager($mapCollection);
		$this->assertEquals($mapCollection, $scanner->getCollection());
	}
	
	public function test_clone_returnMapCollection()
	{
		$mapCollection = new MapCollection();
		$scanner = new ScanManager($mapCollection);
		$scannerClone = clone $scanner;
		
		$this->assertEquals($scanner, $scannerClone);
	}
}
