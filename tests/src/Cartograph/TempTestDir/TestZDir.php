<?php
namespace TestZdir;

class TestZDir implements \Cartograph\Base\IMapper
{
	/**
	 * @map
	 * @mapperSource array
	 * @mapperTarget int
	 */
	public static function test(array $b): int
	{
		return 1;
	}
}