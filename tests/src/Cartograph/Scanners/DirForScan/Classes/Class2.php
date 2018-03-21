<?php
namespace Cartograph\Scanners\DirForScan\Classes;


use Cartograph\Base\IMapper;


class Class2 implements IMapper
{
	/**
	 * @map
	 */
	public static function map(int $a): array
	{
		return [1,];
	} 
}