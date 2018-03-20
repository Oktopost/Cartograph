<?php
namespace Cartograph\Scanners;


use PHPUnit\Framework\TestCase;


class ClassAnnotationScannerTest extends TestCase
{
	
	public function test_scan_withProperClass_returnsNotEmptyMapCollection()
	{
		$scanManager = new ScanManager();
		$scanManager->addDir(__DIR__.'/../TempTestDir');
		
		$mapCollection = ClassAnnotationScanner::scan('Some');
		$this->assertFalse($mapCollection->isEmpty());
	}
}
