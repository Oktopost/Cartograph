<?php

use Cartograph\Base\IMapper;


class Some implements IMapper
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