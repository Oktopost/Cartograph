<?php
namespace Cartograph\Exceptions;


class MapperAlreadyExistsException extends CartographException
{
	public function __construct(string $from, string $to)
	{
		parent::__construct("Mapper from $from to $to already defined");
	}
}