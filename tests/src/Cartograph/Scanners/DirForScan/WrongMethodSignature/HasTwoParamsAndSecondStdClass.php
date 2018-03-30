<?php
namespace src\Cartograph\Scanners\DirForScan\WrongMethodSignature;


use Cartograph\Base\IMapper;


class HasTwoParamsAndSecondStdClass implements IMapper
{
	/**
	 * @map
	 */
	public static function mapMe(int $a, \stdClass $b): int
	{
		return 1;
	}
}