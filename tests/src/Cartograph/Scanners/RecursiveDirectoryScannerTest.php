<?php
namespace Cartograph\Scanners;


use Cartograph\Exceptions\Scanners\DirectoryScannerException;
use PHPUnit\Framework\TestCase;


class RecursiveDirectoryScannerTest extends TestCase
{
	public function test_scan_withExceptionsCaught_throwsException()
	{
		$consumerStub = $this->createMock(FileConsumer::class);
		$consumerStub->method('getExceptions')->willReturn([new \Exception()]);
		
		$this->expectException(DirectoryScannerException::class);
		RecursiveDirectoryScanner::scan(__DIR__ . '/../TempTestDir',$consumerStub);
	}
}

