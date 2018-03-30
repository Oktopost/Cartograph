<?php
namespace Cartograph\Scanners;


use PHPUnit\Framework\TestCase;

use Cartograph\Exceptions\Scanners\WrongArgumentException;

use Cartograph\Scanners\DirForScan\Classes\ValidClassWithTwoParams;
use Cartograph\Scanners\DirForScan\Classes\ValidClassWithOneParamScalarType;
use Cartograph\Scanners\DirForScan\WrongMethodSignature\HasMoreThanTwoParams;
use Cartograph\Scanners\DirForScan\WrongMethodSignature\HasNoParams;
use Cartograph\Scanners\DirForScan\WrongMethodSignature\HasNoReturnType;
use Cartograph\Scanners\DirForScan\WrongMethodSignature\HasTwoParamsAndSecondScalar;


class ClassAnnotationScannerTest extends TestCase
{
	public function test_scan_withProperClass_returnsNotEmptyMapCollection()
	{
		require_once __DIR__ . '/DirForScan/Classes/ValidClassWithOneParamScalarType.php';
		
		$mapCollection = ClassAnnotationScanner::scan(ValidClassWithOneParamScalarType::class);
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
		require_once __DIR__ . '/DirForScan/WrongMethodSignature/HasTwoParamsAndSecondScalar.php';
		
		$this->expectException(WrongArgumentException::class);
		ClassAnnotationScanner::scan(HasTwoParamsAndSecondScalar::class);
	}
	
	public function test_scan_withMethodThatHasTwoParamsAndSecondStdClass_throwsException()
	{
		require_once __DIR__ . '/DirForScan/WrongMethodSignature/HasTwoParamsAndSecondScalar.php';
		
		$this->expectException(WrongArgumentException::class);
		ClassAnnotationScanner::scan(HasTwoParamsAndSecondScalar::class);
	}
	
	public function test_scan_withMethodThatHasTwoParamsAndSecondCartograph_notThrowsException()
	{
		require_once __DIR__ . '/DirForScan/Classes/ValidClassWithTwoParams.php';
		
		$mapCollection = ClassAnnotationScanner::scan(ValidClassWithTwoParams::class);
		$this->assertFalse($mapCollection->isEmpty());
	}
}
