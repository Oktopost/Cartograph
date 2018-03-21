<?php
namespace Cartograph\Scanners\DirForScan\WrongMethodSignature;


use Cartograph\Base\IMapper;


class HasTwoParamsAndSecondNotCartograph implements IMapper
{
	/**
	 * @map
	 */
	public static function mapMe(int $a, int $b): int
	{
		return 1;
	}
}