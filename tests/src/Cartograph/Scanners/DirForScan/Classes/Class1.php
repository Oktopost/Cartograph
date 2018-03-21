<?php
namespace Cartograph\Scanners\DirForScan\Classes;


use Cartograph\Base\IMapper;


class Class1 implements IMapper
{
	/**
	 * @map
	 */
	public static function test(array $b): int
	{
		return 1;
	}
}