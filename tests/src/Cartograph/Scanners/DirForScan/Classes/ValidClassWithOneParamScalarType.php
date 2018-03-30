<?php
namespace Cartograph\Scanners\DirForScan\Classes;


use Cartograph\Base\IMapper;


class ValidClassWithOneParamScalarType implements IMapper
{
	/**
	 * @map
	 */
	public static function map(int $a): array
	{
		return [1,];
	} 
}