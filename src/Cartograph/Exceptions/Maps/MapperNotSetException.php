<?php
namespace Cartograph\Exceptions\Maps;


use Cartograph\Exceptions\CartographException;


class MapperNotSetException extends CartographException
{
	public function __construct(string $from, string $to)
	{
		parent::__construct("Mapper for $from to $to doesn't exists.");
	}
}