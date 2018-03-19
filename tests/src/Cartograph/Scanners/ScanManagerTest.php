<?php
/**
 * Created by PhpStorm.
 * User: maksymlaktionov
 * Date: 3/19/18
 * Time: 6:36 PM
 */
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
	
	public function testAddDir()
	{
		$mapCollection = $this->createMock(MapCollection::class);
		$mapCollection->expects($this->exactly(2))->method('merge');
		$scanner = new ScanManager($mapCollection);
		$scanner->addDir('1', '2');
	}
	
	public function test_getCollection_returnMapCollection()
	{
		$mapCollection = new MapCollection();
		$scanner = new ScanManager($mapCollection);
		$this->assertEquals($mapCollection, $scanner->getCollection());
	}
}
