<?php
namespace Cartograph\Scanners\DirForScan\WrongMethodSignature;


use Cartograph\Base\IMapper;


class HasNoParams implements IMapper
{
	/**
	 * @map
	 */
	public static function mapMe(): int
	{
		return 1;
	}
}