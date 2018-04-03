<?php
namespace Cartograph\Utilities;


use Traitor\TStaticClass;
use Objection\LiteObject;

use Cartograph\Base\INormalizable;
use Cartograph\Exceptions\CartographFatalException;


class Narrow
{
	use TStaticClass;
	
	
	/**
	 * @param LiteObject|INormalizable|\stdClass|array $target
	 * @param string $path
	 * @return array
	 */
	private static function toArrayWithTrace($target, string $path): array
	{
		if (is_array($target) || $target instanceof \stdClass)
		{
			$target = (array)$target;
			
			foreach ($target as $key => $value)
			{
				if (is_null($value))
				{
					unset($target[$key]);
				}
				else if ($value instanceof INormalizable)
				{
					$target[$key] = self::toArrayWithTrace($value->normalize(), "$path.$key");
				}
				else if (is_array($value) || $value instanceof \stdClass || $value instanceof LiteObject)
				{
					$target[$key] = self::toArrayWithTrace($value, "$path.$key");
				}
				else if (!is_scalar($value))
				{
					$path = substr("$path.$key", 1);
					throw new CartographFatalException('Invalid value type at key ' . $path);
				}
			}
			
			return $target;
		}
		else if ($target instanceof INormalizable)
		{
			return self::toArrayWithTrace($target->normalize(), $path);
		}
		else if ($target instanceof LiteObject)
		{
			return self::toArrayWithTrace($target->toArray(), $path);
		}
		else 
		{
			throw new CartographFatalException('Invalid parameter supplied for function.');
		}
	}
	
	
	/**
	 * @param LiteObject|INormalizable|\stdClass|array $target
	 * @return array
	 */
	public static function toArray($target): array
	{
		return self::toArrayWithTrace($target, 'target');
	}
}