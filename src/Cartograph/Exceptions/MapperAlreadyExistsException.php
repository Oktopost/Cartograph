<?php
namespace Cartograph\Exceptions;


class MapperAlreadyExistsException extends \Exception
{
	public function __construct($from, $to)
	{
		$msg = "Key $from|$to already exists in collection";
		
		parent::__construct($msg);
	}
}