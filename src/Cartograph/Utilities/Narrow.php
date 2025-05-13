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
	private static function toArrayWithTrace($target, string $path, bool $keepFalseLike = true): array
	{
		if (is_array($target) || $target instanceof \stdClass)
		{
			$target = (array)$target;
			
			foreach ($target as $key => $value)
			{
				if (is_null($value))
				{
					unset($target[$key]);
					continue;
				}
				else if ($value instanceof INormalizable)
				{
					$target[$key] = $value->normalize($keepFalseLike);
				}
				else if (is_array($value) || $value instanceof \stdClass || $value instanceof LiteObject)
				{
					$target[$key] = self::toArrayWithTrace($value, "$path.$key", $keepFalseLike);
				}
				else if (!is_scalar($value))
				{
					$path = substr("$path.$key", 1);
					throw new CartographFatalException('Invalid value type at key ' . $path);
				}
				
				if (!$keepFalseLike && !($target[$key]))
				{
					unset($target[$key]);
				}
			}
			
			return $target;
		}
		else if ($target instanceof INormalizable)
		{
			return $target->normalize($keepFalseLike);
		}
		else if ($target instanceof LiteObject)
		{
			return self::toArrayWithTrace($target->toArray(), $path, $keepFalseLike);
		}
		else 
		{
			throw new CartographFatalException('Invalid parameter supplied for function.');
		}
	}
	
	
	/**
	 * @param LiteObject|INormalizable|\stdClass|array $target
	 * @param bool $keepFalseLike
	 * @return array
	 */
	public static function toArray($target, bool $keepFalseLike = false): array
	{
		return self::toArrayWithTrace($target, 'target', $keepFalseLike);
	}
}