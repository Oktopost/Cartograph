<?php
namespace Cartograph\Exceptions\Maps;


use Cartograph\Exceptions\CartographException;


class InvalidMapperSource extends CartographException
{
	public function __construct($source)
	{
		$type = gettype($source);
		parent::__construct("The argument should be a string or object, $type was passed.");
	}
}