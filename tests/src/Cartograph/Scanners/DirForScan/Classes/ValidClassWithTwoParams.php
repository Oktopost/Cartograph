<?php
namespace Cartograph\Scanners\DirForScan\Classes;


use Cartograph\Cartograph;
use Cartograph\Base\IMapper;


class ValidClassWithTwoParams implements IMapper
{
	/**
	 * @map
	 */
	public static function map(int $a, Cartograph $c): int
	{
		return 1;
	}
}