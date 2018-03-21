<?php
namespace Cartograph\Scanners\DirForScan\WrongMethodSignature;


use Cartograph\Base\IMapper;


class HasMoreThanTwoParams implements IMapper
{
	/**
	 * @map
	 */
	public static function mapMe(int $a, int $b, int $c): int
	{
		return 1;
	}
}