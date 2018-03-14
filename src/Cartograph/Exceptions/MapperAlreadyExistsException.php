<?php
namespace Cartograph\Exceptions;


class MapperAlreadyExistsException extends \Exception
{
	public function __construct(string $key)
	{
		$msg = "Key $key already exists in collection";
		
		parent::__construct($msg);
	}
}