<?php
namespace Cartograph\Scanners\DirForScan\WrongMethodSignature;


use Cartograph\Base\IMapper;


class HasNoReturnType implements IMapper
{
	/**
	 * @map
	 */
	public static function mapMe(int $a, int $b)
	{
		return 1;
	}
}