<?php
namespace Cartograph\Scanners;


use Annotation\Flag;
use Cartograph\Cartograph;
use Cartograph\Maps\MapCollection;
use Cartograph\Exceptions\Scanners\WrongArgumentException;


class ClassAnnotationScanner
{
	private const MAPPER_METHOD	= 'map';
	
	
	/**
	 * @param string $className
	 * @param \ReflectionParameter[] $params
	 * @param \ReflectionMethod $method
	 */
	private static function checkSignature(string $className, array $params, \ReflectionMethod $method): void
	{
		$paramsCount = count($params);
		if (!$paramsCount ||
			$paramsCount > 2 ||
			!$method->getReturnType() ||
			$paramsCount == 2 && (is_null($params[1]->getClass()) || $params[1]->getClass()->name !== Cartograph::class)
		)
			throw new WrongArgumentException($className, $method->name);
	}
	
	
	public static function scan(string $className): MapCollection
	{
		$mapCollection = new MapCollection();
		
		$class = new \ReflectionClass($className);
		
		$methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
		
		foreach ($methods as $method)
		{
			if ($method->isStatic() && !$method->isAbstract() && Flag::hasFlag($method, self::MAPPER_METHOD))
			{
				$params	= $method->getParameters();
				
				self::checkSignature($className, $params, $method);
				
				$mapCollection->add(
					$params[0]->getType(), 
					$method->getReturnType(), 
					$method->class . '::' . $method->name
				);
			}
		}
		
		return $mapCollection;
	}
}