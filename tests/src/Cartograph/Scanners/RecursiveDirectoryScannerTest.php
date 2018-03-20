<?php
namespace Cartograph\Scanners;


use PHPUnit\Framework\TestCase;
use Cartograph\Exceptions\Scanners\DirectoryScannerException;


class RecursiveDirectoryScannerTest extends TestCase
{
	public function test_scan_withExceptionsCaught_throwsException()
	{
		$this->expectException(DirectoryScannerException::class);
		RecursiveDirectoryScanner::scan(__DIR__ . '/DirForScan/ExceptionForScanner');
	}
}

