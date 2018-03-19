<?php

class Some implements \Cartograph\Base\IMapper
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