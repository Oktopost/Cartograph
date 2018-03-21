<?php
namespace Cartograph\Exceptions\Scanners;


use Cartograph\Exceptions\CartographException;


class WrongArgumentException extends CartographException
{
	public function __construct(string $className, string $methodName)
	{
		parent::__construct("$className has map annotation but method $methodName can't be parsed as mapper.");
	}
}