<?php
/**
 * Created by PhpStorm.
 * User: maksymlaktionov
 * Date: 3/20/18
 * Time: 3:43 PM
 */

use Cartograph\Exceptions\Scanners\DirectoryScannerException;
use PHPUnit\Framework\TestCase;


class DirectoryScannerExceptionTest extends TestCase
{
	public function test_exceptions_withPassedArray_willReturnThisArray()
	{
		$exception = new DirectoryScannerException([new \Exception()], 'test');
		$this->assertNotEmpty($exception->exceptions());
	}
}
