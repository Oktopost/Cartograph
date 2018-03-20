<?php
/**
 * Created by PhpStorm.
 * User: maksymlaktionov
 * Date: 3/20/18
 * Time: 2:00 PM
 */
namespace Cartograph\Scanners;


use PHPUnit\Framework\TestCase;


class FileConsumerTest extends TestCase
{
	
	public function test_consume_loadDirWithException_willCatchExceptionAndStoreIt()
	{
		$consumer = new FileConsumer();
		$consumer->setRoot(__DIR__);
		$consumer->consume('/DirForScan/ExceptionForConsumer/exception.php');
		$this->assertTrue(count($consumer->getExceptions()) > 0);
	}
}
