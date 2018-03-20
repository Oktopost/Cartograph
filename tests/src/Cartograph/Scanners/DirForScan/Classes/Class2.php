<?php

use Cartograph\Base\IMapper;


class Class2 implements IMapper
{
	/**
	 * @map
	 * @mapperSource integer
	 * @mapperTarget array
	 */
	public static function map(int $a): array
	{
		return [1,];
	} 
}