<?php
namespace Cartograph\Scanners;


use PHPUnit\Framework\TestCase;

use Cartograph\Exceptions\Scanners\WrongArgumentException;

use Cartograph\Scanners\DirForScan\Classes\Class2;
use Cartograph\Scanners\DirForScan\WrongMethodSignature\HasMoreThanTwoParams;
use Cartograph\Scanners\DirForScan\WrongMethodSignature\HasNoParams;
use Cartograph\Scanners\DirForScan\WrongMethodSignature\HasNoReturnType;
use Cartograph\Scanners\DirForScan\WrongMethodSignature\HasTwoParamsAndSecondNotCartograph;


class ClassAnnotationScannerTest extends TestCase
{
	public function test_scan_withProperClass_returnsNotEmptyMapCollection()
	{
		require_once __DIR__ . '/DirForScan/Classes/Class2.php';
		
		$mapCollection = ClassAnnotationScanner::scan(Class2::class);
		$this->assertFalse($mapCollection->isEmpty());
	}
	
	public function test_scan_withMethodThatHasMoreThanTwoParams_throwsException()
	{
		require_once __DIR__ . '/DirForScan/WrongMethodSignature/HasMoreThanTwoParams.php';
		
		$this->expectException(WrongArgumentException::class);
		ClassAnnotationScanner::scan(HasMoreThanTwoParams::class);
	}
	
	public function test_scan_withMethodThatHasNoParams_throwsException()
	{
		require_once __DIR__ . '/DirForScan/WrongMethodSignature/HasNoParams.php';
		
		$this->expectException(WrongArgumentException::class);
		ClassAnnotationScanner::scan(HasNoParams::class);
	}
	
	public function test_scan_withMethodThatHasNoReturnType_throwsException()
	{
		require_once __DIR__ . '/DirForScan/WrongMethodSignature/HasNoReturnType.php';
		
		$this->expectException(WrongArgumentException::class);
		ClassAnnotationScanner::scan(HasNoReturnType::class);
	}
	
	public function test_scan_withMethodThatHasTwoParamsAndSecondNotCartograph_throwsException()
	{
		require_once __DIR__ . '/DirForScan/WrongMethodSignature/HasTwoParamsAndSecondNotCartograph.php';
		
		$this->expectException(WrongArgumentException::class);
		ClassAnnotationScanner::scan(HasTwoParamsAndSecondNotCartograph::class);
	}
}
